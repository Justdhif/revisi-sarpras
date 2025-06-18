<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS getAllCategories;
            CREATE PROCEDURE getAllCategories()
            BEGIN
                SELECT * FROM categories ORDER BY created_at DESC;
            END;
        ");

        DB::unprepared("
            DROP PROCEDURE IF EXISTS createCategory;
            CREATE PROCEDURE createCategory(
                IN nameInput VARCHAR(255),
                IN descriptionInput TEXT
            )
            BEGIN
                INSERT INTO categories (name, description, created_at, updated_at)
                VALUES (nameInput, descriptionInput, NOW(), NOW());
            END;
        ");

        DB::unprepared("
            DROP PROCEDURE IF EXISTS updateCategory;
            CREATE PROCEDURE updateCategory(
                IN categoryId INT,
                IN nameInput VARCHAR(255),
                IN descriptionInput TEXT
            )
            BEGIN
                UPDATE categories
                SET name = nameInput,
                    description = descriptionInput,
                    updated_at = NOW()
                WHERE id = categoryId;
            END;
        ");

        DB::unprepared("
            DROP PROCEDURE IF EXISTS deleteCategory;
            CREATE PROCEDURE deleteCategory(
                IN categoryId INT
            )
            BEGIN
                DELETE FROM categories WHERE id = categoryId;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS getAllCategories;");
        DB::unprepared("DROP PROCEDURE IF EXISTS createCategory;");
        DB::unprepared("DROP PROCEDURE IF EXISTS updateCategory;");
        DB::unprepared("DROP PROCEDURE IF EXISTS deleteCategory;");
    }
};
