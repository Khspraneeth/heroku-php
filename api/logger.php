<?php

function logError($log,$username){

    $path = './logs/error_log.txt';
    if(!file_exists($path)){
        file_put_contents($path,'');
    }

    $ip = $_SERVER['REMOTE_ADDR'];
    date_default_timezone_set('Asia/Kolkata');
    $time = date('m/d/y h:iA',time());

    $prevContents = file_get_contents($path);
    $contents = $prevContents."$ip\t$username\t$time\t$log\r";
    file_put_contents('./logs/error_log.txt',$contents);

}

?>