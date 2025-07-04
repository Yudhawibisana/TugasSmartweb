<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('postingans', function (Blueprint $table) {
            $table->unsignedInteger('max_participants')->default(5)->after('views');
        });
    }

    public function down(): void
    {
        Schema::table('postingans', function (Blueprint $table) {
            $table->dropColumn('max_participants');
        });
    }
};
