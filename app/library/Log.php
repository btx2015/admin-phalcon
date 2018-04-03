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
            $folder_path = BASE_PATH."/log/".date('Ymd');
            if(!file_exists($folder_path)){
                mkdir($folder_path, 0777, true);
                chmod($folder_path, 0777);
            }
            $logger = new FileAdapter($folder_path."/".$fileName.".log");
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