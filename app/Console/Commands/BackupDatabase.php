<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command{

    protected $signature = 'db:backup';

    protected $description = 'Backup the SQLite database';

    public function __construct(){
        parent::__construct();
    }

    public function handle(){
        $databasePath = database_path('database.sqlite');

        // Destination path for the backup
        $backupPath = storage_path('backups/database_' . date('Y_m_d_H_i_s') . '.sqlite');

        // Ensure the backups directory exists
        if (!file_exists(dirname($backupPath))) {
            mkdir(dirname($backupPath), 0755, true);
        }

        // Copy the database file to the backup location
        if (copy($databasePath, $backupPath)) {
            $this->info('Database backup was successful!');
            return Command::SUCCESS;
        } else {
            $this->error('Database backup failed!');
            return Command::FAILURE;
        }
    }
}