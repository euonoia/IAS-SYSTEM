<?php

use Illuminate\Support\Facades\DB;

DB::table('migrations')->insert([
    'migration' => '2026_04_05_235959_create_system_settings_table',
    'batch' => DB::table('migrations')->max('batch') + 1,
]);

echo "Migration recorded!\n";
