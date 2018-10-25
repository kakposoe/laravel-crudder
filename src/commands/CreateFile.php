<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crudder:create {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new crud file';

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
     * @return mixed
     */
    public function handle()
    {
        // get all fields of model
        // find out of the model has a protected array called $types
        // e.g. ['password' => 'password']
        // Create array of fields
        // add to a precreated view file
    }
}
