<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DataViewerController extends Controller
{
    public function index()
    {
        // Get list of tables depending on database driver
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        switch ($driver) {
            case 'mysql':
            case 'mariadb':
                $tables = DB::select('SHOW TABLES');
                $key = array_key_first((array)$tables[0]);
                break;

            case 'pgsql':
                $tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname='public'");
                $key = 'tablename';
                break;

            case 'sqlite':
                $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table'");
                $key = 'name';
                break;

            default:
                die("Unsupported database driver: $driver");
        }

        // Fetch table rows
        $data = [];
        foreach ($tables as $table) {
            $tableName = $table->$key;

            // Skip system tables
            if (in_array($tableName, [
                'migrations', 'sqlite_sequence', 'failed_jobs',
                'password_reset_tokens', 'personal_access_tokens'
            ])) {
                continue;
            }

            // Fetch rows (limit to 50 for performance)
            $rows = DB::table($tableName)->limit(50)->get();
            $data[$tableName] = $rows;
        }

        return view('dataviewer.index', compact('data'));
    }
}
