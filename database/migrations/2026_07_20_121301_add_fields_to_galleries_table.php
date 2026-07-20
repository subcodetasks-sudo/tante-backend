<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            if (! Schema::hasColumn('galleries', 'title')) {
                $table->string('title')->nullable()->after('id');
            }
            if (! Schema::hasColumn('galleries', 'type')) {
                $table->string('type')->default('image')->after('title');
            }
            if (! Schema::hasColumn('galleries', 'image')) {
                $table->string('image')->nullable()->after('type');
            }
            if (! Schema::hasColumn('galleries', 'video_url')) {
                $table->string('video_url')->nullable()->after('image');
            }
            if (! Schema::hasColumn('galleries', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('video_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $columns = ['title', 'type', 'image', 'video_url', 'sort_order'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('galleries', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
