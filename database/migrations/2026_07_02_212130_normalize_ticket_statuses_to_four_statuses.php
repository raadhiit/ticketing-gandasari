<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('tickets')
            ->where('status', 'ASSIGNED')
            ->update(['status' => 'OPEN']);

        DB::table('tickets')
            ->where('status', 'WAITING_USER')
            ->update(['status' => 'IN_PROGRESS']);

        DB::table('tickets')
            ->where('status', 'CANCELLED')
            ->update(['status' => 'CLOSED']);
    }

    public function down(): void
    {
        //
    }
};