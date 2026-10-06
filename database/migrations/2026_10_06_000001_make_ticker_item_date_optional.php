<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Other drivers (e.g. the in-memory SQLite used by tests) already get a nullable column from the create migration.
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE ticker_items MODIFY item_date DATE NULL');
        }
    }

    public function down(): void
    {
        DB::table('ticker_items')->whereNull('item_date')->update(['item_date' => now()->toDateString()]);

        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE ticker_items MODIFY item_date DATE NOT NULL');
        }
    }
};