<?php

namespace Pottery\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
// use Pottery\Models\Pottery;

class MakePackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ptr:make {packagename} {ClassName?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Заготовка команды';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public $author;
    public $name;
    public $className;
    public $ind;

    public function ReplaceText($f)
    {
        $f = str_replace('MakePackage::class,', '', $f);
        $f = str_replace('whitePottery', $this->name, $f);
        $f = str_replace('whitepottery', $this->author, $f);
        $f = str_replace('pottery', $this->ind, $f);
        $f = str_replace('Pottery', $this->className, $f);
        $f = str_replace('email@example.com', "orasvr@mail.ru", $f);

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

    public function handle()
    {
        $packagename = $this->argument("packagename");
        $className  = $this->argument("ClassName");

        $this->name  = explode('/', $packagename)[0];
        $packagename = strtolower($packagename);

        if (substr_count($packagename, '/') != 1) {
            $this->error("Нужно так логингитхаба/названиепакета  и всё слитно с маленьких букв");
            return;
        }

        $this->author = explode('/', $packagename)[0];
        $this->ind    = explode('/', $packagename)[1];

        if (empty($className)) {
            $className = strtoupper(substr($this->ind, 0, 1)) . substr($this->ind, 1);
        }

        $this->className = $className;

        $vendorPath = __DIR__ . '/../../../../../';

        $vendorPathAuthor  = $vendorPath . $this->author;
        $vendorPathPackage = $vendorPath . $this->author . '/' . $this->ind;

        if ( ! file_exists($vendorPathAuthor)) {
            mkdir($vendorPathAuthor);
        }

        \File::copyDirectory($vendorPath . 'whitepottery/pottery', $vendorPathPackage);

        File::copy($vendorPathPackage . '/_README.md', $vendorPathPackage . '/README.md');
        File::delete($vendorPathPackage . '/_README.md');

        $ignore                    = [];
        $ignore['MakePackage.php'] = true;

        foreach (File::allFiles($vendorPathPackage) as $K => $V) {

            if (isset($ignore[basename($V)])) {
                File::delete($V);
                continue;
            }

            $this->ReplaceFile($V);
        }

        $this->info("Пакет создан. Ищите в вендоре");
    }

}
