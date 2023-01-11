<?php

use Illuminate\Support\Facades\Artisan;

function order_activity($title , $order) {
    $order->activities()->create([
        "order_id" => $order->id,
        "title" => $title,

    ]);
}

function update_env($key , $value) {
    file_put_contents(app()->environmentFilePath(),
        str_replace($key . '='. env($key),
            $key . '=' . $value,file_get_contents(app()->environmentFilePath())));

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
}
