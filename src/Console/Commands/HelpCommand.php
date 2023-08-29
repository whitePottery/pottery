<?php

namespace Pottery\Console\Commands;

use Illuminate\Console\Command;

class HelpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ptr:help {lengs?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создаем пустой шаблон с базовыми настройками';

    public $LoginAndPackageName;
    public $className;
    // public $className;
    // public $ind;

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
     * @return int
     */
    public function handle()
    {
        $this->checkLengs();
    }

    private function checkLengs()
    {

        $lengs = $this->argument("lengs") ?? 'en';

        $data = $this->getData();

        if(isset($data[$lengs])){
          $this->info($data[$lengs]);
        }
        else{
          $this->error('please - php artisan ptr:help ru or en');
        }

    }

    private function getData()
    {

        return [
            'ru' => '
        php artisan ptr:create LoginGitHub/PackageName
        LoginGitHub - Ваш логин на GitHub
        PackageName - название пакета который создаете


            ',
            'en' => '
        php artisan ptr:create LoginGitHub/PackageName',
        ];
    }

}
