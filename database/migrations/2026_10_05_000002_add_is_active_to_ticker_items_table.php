<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticker_items', function (Blueprint $table) {
            // Existing entries keep showing in the bar; admins can switch any of them off.
            $table->boolean('is_active')->default(true)->after('item_date');
        });
    }

    public function down(): void
    {
        Schema::table('ticker_items', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
