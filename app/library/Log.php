<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/2
 * Time: 13:35
 */

use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileAdapter;

class Log
{

    public static function writeLog($fileName = 'error',$message = '',$level = 1){
        try{
            $logger = new FileAdapter(BASE_PATH."/log/".date('Ymd')."/".$fileName.".log");
            if($level){
                $logger->log($message, Logger::DEBUG);
            }else{
                $logger->log($message, Logger::ERROR);
            }
        }catch(ErrorException $e){
            print_r($e->getMessage());die;
        }

    }
}