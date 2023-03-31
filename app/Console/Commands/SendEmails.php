<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'say:hi {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'intro massage';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle($name=null)
    {
        return $this->info('The command was successful!');
    }
}
