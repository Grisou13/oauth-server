<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
class KeyGenerate extends Command{
    protected $signature = 'key:generate';
    protected $description = 'Generate a key';

    public function handle(){
        $file = base_path(".env");
        $content = file_get_contents($file);
        $key= base64_encode(random_bytes(32));
        $re = '/(APP_KEY=)(.*)/';
        $newContent = preg_replace($re,"APP_KEY=".$key, $content);
        file_put_contents($file, $newContent);
        $this->info("App key set to [{$key}] in {$file}");


    }
}