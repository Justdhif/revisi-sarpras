<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE TRIGGER trg_after_insert_item_unit
            AFTER INSERT ON item_units
            FOR EACH ROW
            BEGIN
                INSERT INTO stock_movements (
                    item_unit_id,
                    movement_type,
                    quantity,
                    movement_date,
                    description,
                    created_at,
                    updated_at
                ) VALUES (
                    NEW.id,
                    'in',
                    NEW.quantity,
                    NOW(),
                    'Barang masuk ke gudang',
                    NOW(),
                    NOW()
                );
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_insert_item_unit");
    }
};
