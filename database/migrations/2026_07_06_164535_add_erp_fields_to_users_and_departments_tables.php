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
        Schema::table('departments', function (Blueprint $table) {
            $table->string('erp_code', 100)->nullable()->unique()->after('id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('erp_user_id', 100)->nullable()->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['erp_user_id']);
            $table->dropColumn('erp_user_id');
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropUnique(['erp_code']);
            $table->dropColumn('erp_code');
        });
    }
};
