<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetFolderPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:set {folder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $folder = $this->argument('folder');
        $this->setPermissions($folder);
    }

    private function setPermissions($folder)
    {
        $command = "chmod -R 755 $folder"; // Change permissions as needed
        exec($command);

        $this->info("Permissions set recursively for folder: $folder");
    }
}
