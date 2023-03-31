<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\File;
use Illuminate\Filesystem\Filesystem;
/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
Artisan::command('say:hi {name?}', function ($name=null) {


    $file=new Illuminate\Filesystem\Filesystem;
    if ($file->makeDirectory(app_path().'/test')){
        if ($file->put(app_path().'/test/test.txt','hi '.$name)){
            $this->info("file created");
        }
        else{
            $this->info("error in create");
        }

    }
    else{
        $this->info("error");
    }


});
