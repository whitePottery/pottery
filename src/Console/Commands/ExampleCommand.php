<?php

namespace Pottery\Console\Commands;

// use Pottery\Library\PotteryHelper;
// use Pottery\Models\Pottery;
// use Pottery\Models\PotterySetting;
// use Carbon\Carbon;
use Illuminate\Console\Command;

class ExampleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pottery:example';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Заготовка команды pottery';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {

        $this->info("pottery - Команда выполнена");
    }
}
