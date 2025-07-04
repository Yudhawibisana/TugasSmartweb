<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('age')->nullable()->after('email');
            $table->string('class')->nullable()->after('age'); // opsional
            $table->string('school_or_university')->nullable()->after('class'); // opsional
        });
    }

    public function down() {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['age', 'class', 'school_or_university']);
        });
    }
};
