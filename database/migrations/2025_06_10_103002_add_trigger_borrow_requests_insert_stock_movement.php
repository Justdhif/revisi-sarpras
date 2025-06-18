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
        DB::unprepared('
            CREATE TRIGGER after_borrow_approval
            AFTER UPDATE ON borrow_requests
            FOR EACH ROW
            BEGIN
                IF NEW.status = "approved" AND OLD.status != "approved" THEN
                    INSERT INTO stock_movements (
                        item_unit_id,
                        movement_type,
                        quantity,
                        description,
                        movement_date,
                        created_at,
                        updated_at
                    )
                    SELECT
                        bd.item_unit_id,
                        "out",
                        bd.quantity,
                        "Persetujuan peminjaman",
                        NOW(),
                        NOW(),
                        NOW()
                    FROM borrow_details bd
                    WHERE bd.borrow_request_id = NEW.id;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_borrow_approval');
    }
};
