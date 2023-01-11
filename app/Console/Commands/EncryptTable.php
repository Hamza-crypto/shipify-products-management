<?php

namespace App\Console\Commands;

use App\Models\UserMeta;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;

class EncryptTable extends Command
{
    protected $signature = 'encrypt:table';

    protected $description = 'Encrypt table values';

    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $user_metas = UserMeta::all();
        foreach ($user_metas as $meta){

            $meta->meta_value = Crypt::encrypt($meta->meta_value);
            $meta->save();
        }

        echo "successfully done";

        return Command::SUCCESS;
    }
}
