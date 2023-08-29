<?php

namespace Pottery\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreatePackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pot:make {LoginAndPackageName?} {--l|light}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создаем пустой шаблон с базовыми настройками';

    public $className;
    public $email;
    public $selfPath;

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

        $this->getData();

        ! $this->options("light") ? $this->createLight() : $this->create();

        $this->gitHub();
    }

    public function gitHub()
    {

        if (File::exists($this->newPackagePath . '.git')) {

            File::deleteDirectory($this->newPackagePath . '.git');
        }

        chdir($this->newPackagePath);

        exec('ls', $output);

        exec('git init', $output);

        if (File::exists($this->newPackagePath . '_config')) {

            File::move($this->newPackagePath . '_config', $this->newPackagePath . '.git/config');
        }

        $this->ReplaceFile($this->newPackagePath . '.git/config');

    }

    public function createLight()
    {

        $path = $this->createPathToPackage();

    }

    public function create()
    {
        $path = $this->createPathToPackage();

        File::copyDirectory($this->selfPath, $path);

        File::copy($path . '/_README.md', $path . '/README.md');
        File::delete($path . '/_README.md');

        $ignore                      = [];
        $ignore['MakePackage.php']   = true;
        $ignore['CreatePackage.php'] = true;
        $ignore['HelpCommand.php']   = true;

        foreach (File::allFiles($path) as $K => $V) {

            if (isset($ignore[basename($V)])) {
                File::delete($V);
                continue;
            }

            $this->ReplaceFile($V);
        }

    }

    public function ReplaceText($f) //strtolower($this->LoginGit);

    {
        preg_match('/ExampleCommand::class,([\s\S]*)]\)/U', $f, $matches);

        if (isset($matches[1])) {
            $f = str_replace($matches[1], '\n', $f);
        }
        $f = str_replace('email@email.tmp', $this->email, $f);
        $f = str_replace('/ExampleCommand::class,([\s\S]*)]\)/U', '', $f);
        $f = str_replace('whitePottery', $this->LoginGit, $f);
        $f = str_replace('whitepottery', strtolower($this->LoginGit), $f);
        $f = str_replace('pottery', strtolower($this->packageName), $f);
        $f = str_replace('Pottery', $this->className, $f);

        return $f;
    }

    public function ReplaceFile($path)
    {
        $f = file_get_contents($path);
        $f = $this->ReplaceText($f);

        $name   = basename($path);
        $nameTo = $this->ReplaceText(basename($path));

        if ($name != $nameTo) {
            File::delete($path);
            $path = str_replace(basename($path), $nameTo, $path);
        }

        file_put_contents($path, $f);
    }

    /**
     * [checkInputData description]
     * @return [type] [description]
     */
    private function getData()
    {

        $this->LoginGit = $this->verifyData($this->ask('Введите свой логин на GitHub:'));

        $this->packageName = $this->verifyData($this->ask('Введите название нового пакета так же как он будет на GitHub (обычно это стиль: kebab-case):'));

        $this->email = $this->verifyData($this->ask('Введите свой email:'));

        $this->className = $this->dashesToCamelCase($this->packageName);

        $this->selfPath = base_path('vendor/whitepottery/pottery/');

        $this->newPackagePath = base_path('vendor/' . $this->LoginGit . '_TMP/' . $this->packageName . '/');

    }

    /**
     * [checkInputData description]
     * @return [type] [description]
     */
    private function checkInputData()
    {
        $tmpArry = explode('/', $this->argument("LoginAndPackageName"));

        $this->LoginGit    = $this->verifyData($tmpArry[0]);
        $this->packageName = $this->verifyData($tmpArry[1]);
        $this->className   = $this->dashesToCamelCase($this->packageName);
    }

    /**
     * [createPathToPackage description]
     * @return [type] [description]
     */
    private function createPathToPackage()
    {
        $path = $this->newPackagePath;

        if ( ! File::exists(base_path($path))) {

            File::makeDirectory(base_path($path), 0755, true);

            $this->info("Создана папка -" . $path);

            return $path;
        }

        $this->error('Папка: "' . $path . ' уже существует');

        die;

    }

    public function dashesToCamelCase($string, $capitalizeFirstCharacter = true)
    {
        $str = str_replace('-', '', ucwords($string, '-'));

        if ( ! $capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }

    /**
     * [verifyData description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    private function verifyData($data)
    {

        if (empty($data)) {

            $this->error('Error! Incorrect data entered. Pleace insert: "php artisan ptr:help"');

            die;
        }

        return $data;

    }

    // public function camelToUnderscore($string, $us = "-")
    // {
    //     // $output = ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $this->packageName)), '-');

    //     return strtolower(preg_replace(
    //         '/(?<=\d)(?=[A-Za-z])|(?<=[A-Za-z])(?=\d)|(?<=[a-z])(?=[A-Z])/', $us, $string));
    // }

}
