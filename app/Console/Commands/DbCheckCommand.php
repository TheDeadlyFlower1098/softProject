<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DbCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show record counts for all database tables in the current connection';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("\n📊 Database record counts:\n");

        // Detect database driver
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
                $this->error("Unsupported database driver: {$driver}");
                return;
        }

        $hasEmpty = false;

        foreach ($tables as $table) {
            $tableName = $table->$key;

            // skip migrations table
            $skip = ['migrations','failed_jobs','personal_access_tokens','password_reset_tokens','sqlite_sequence'];
            if (in_array($tableName, $skip)) continue;

            $count = DB::table($tableName)->count();

            if ($count === 0) {
                $this->warn(str_pad($tableName, 25) . " : 0 ❌ EMPTY");
                $hasEmpty = true;
            } elseif ($count < 10) {
                $this->line(str_pad($tableName, 25) . " : " . $count . " ⚠️  low count");
            } else {
                $this->info(str_pad($tableName, 25) . " : " . $count . " ✅");
            }
        }

        $this->newLine();
        if ($hasEmpty) {
            $this->error("⚠️ Some tables are empty. Check your seeders.");
        } else {
            $this->info("✅ All tables have data!");
        }
    }
}
