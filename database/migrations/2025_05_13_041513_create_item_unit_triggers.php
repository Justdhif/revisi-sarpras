<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        // Trigger: Tambah used_capacity saat insert item_unit
        DB::unprepared("
            CREATE TRIGGER trg_increase_warehouse_capacity
            AFTER INSERT ON item_units
            FOR EACH ROW
            BEGIN
                UPDATE warehouses
                SET used_capacity = used_capacity + NEW.quantity
                WHERE id = NEW.warehouse_id;
            END;
        ");

        // Trigger: Kurangi used_capacity saat hapus item_unit
        DB::unprepared("
            CREATE TRIGGER trg_decrease_warehouse_capacity
            AFTER DELETE ON item_units
            FOR EACH ROW
            BEGIN
                UPDATE warehouses
                SET used_capacity = used_capacity - OLD.quantity
                WHERE id = OLD.warehouse_id;
            END;
        ");

        // Optional: Tambah log aktivitas saat insert item_unit
        DB::unprepared("
            CREATE TRIGGER trg_log_item_unit_insert
            AFTER INSERT ON item_units
            FOR EACH ROW
            BEGIN
                INSERT INTO activity_logs (user_id, action, description, created_at, updated_at)
                VALUES (NULL, 'insert', CONCAT('Unit baru ditambahkan (SKU: ', NEW.sku, ')'), NOW(), NOW());
            END;
        ");

        // Trigger: Update used_capacity saat item_unit diupdate
        DB::unprepared("
            CREATE TRIGGER trg_update_warehouse_capacity
            AFTER UPDATE ON item_units
            FOR EACH ROW
            BEGIN
                -- Jika warehouse_id tidak berubah
                IF OLD.warehouse_id = NEW.warehouse_id THEN
                    UPDATE warehouses
                    SET used_capacity = used_capacity + (NEW.quantity - OLD.quantity)
                    WHERE id = NEW.warehouse_id;
                ELSE
                    -- Jika warehouse_id berubah, kurangi dari warehouse lama, tambahkan ke warehouse baru
                    UPDATE warehouses
                    SET used_capacity = used_capacity - OLD.quantity
                    WHERE id = OLD.warehouse_id;

                    UPDATE warehouses
                    SET used_capacity = used_capacity + NEW.quantity
                    WHERE id = NEW.warehouse_id;
                END IF;
            END;
        ");

        DB::unprepared('
            CREATE TRIGGER trg_log_item_unit_update
            AFTER UPDATE ON item_units
            FOR EACH ROW
            BEGIN
                DECLARE action_text TEXT;
                SET action_text = CONCAT(
                    "Updated item unit ID: ", OLD.id,
                    ", Quantity: ", OLD.quantity, " → ", NEW.quantity,
                    ", Warehouse: ", OLD.warehouse_id, " → ", NEW.warehouse_id
                );

                INSERT INTO activity_logs (user_id, action, description, created_at, updated_at)
                VALUES (
                    NULL, -- Tidak bisa ambil Auth::id() dari trigger MySQL
                    "update_item_unit",
                    action_text,
                    NOW(),
                    NOW()
                );
            END
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_increase_warehouse_capacity');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_decrease_warehouse_capacity');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_log_item_unit_insert');
    }
};
