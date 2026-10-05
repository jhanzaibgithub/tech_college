<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The news bar gets its own table (title + date). News & Events no longer feed the bar, so any item
 * that was flagged "show in ticker" is copied over first and the flag column is then removed.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticker_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('item_date');
            $table->timestamps();
        });

        if (Schema::hasColumn('news_events', 'is_ticker')) {
            DB::table('news_events')->where('is_ticker', true)->orderBy('id')->get()->each(function ($row) {
                DB::table('ticker_items')->insert([
                    'title' => $row->title,
                    'item_date' => $row->event_date ?? substr((string) $row->created_at, 0, 10) ?: now()->toDateString(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

            Schema::table('news_events', function (Blueprint $table) {
                $table->dropColumn('is_ticker');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('news_events', 'is_ticker')) {
            Schema::table('news_events', function (Blueprint $table) {
                $table->boolean('is_ticker')->default(false)->after('is_active');
            });
        }

        Schema::dropIfExists('ticker_items');
    }
};
