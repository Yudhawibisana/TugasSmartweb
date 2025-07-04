<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('postingans', function (Blueprint $table) {
            if (!Schema::hasColumn('postingans', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }
        });

        // Assign default user_id
        DB::table('postingans')->whereNull('user_id')->update(['user_id' => 1]);

        Schema::table('postingans', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }




    public function down(): void
    {
        Schema::table('postingans', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
