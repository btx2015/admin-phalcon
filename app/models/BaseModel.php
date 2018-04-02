<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27
 * Time: 13:27
 */

class BaseModel extends \Phalcon\Mvc\Model
{

    protected $_message;

    public function create($create = NULL,$whiteList = NULL){
        try{
            $create['created_at'] = time();
            if(parent::create($create) !== true){
                $error = '';
                foreach($this->getMessages() as $message){
                    $error .=  $message->getMessage();
                }
                Log::writeLog('db',$error,0);
                return false;
            }
            return true;
        }catch(ErrorException $e){
            print_r($e->getMessage());
            exit();
        }
    }

    public function getRecords($conditions = [],$fields = [],$limit = 0,$offset = 0,$orderBy = []){
        try{
            if(!is_array($conditions) || !is_array($fields) || !is_numeric($limit) || !is_numeric($offset) || !is_array($orderBy)){
                return false;
            }

            $query = $this->query();
            $query->where('1 = 1');

            if($fields){
                $query->columns($fields);
            }

            if($limit > 0){
                $limit = ($limit < 1000) ? $limit : 1000;
                $offset = $offset >= 0 ? $offset : 0;
                $query->limit($limit,$offset);
            }

            if($orderBy){
                $orderByStr = ' ';
                foreach($orderBy as $key => $val){
                    $orderByStr = $val == 1 ? $orderByStr." {$key} DESC," : $orderByStr." {$key} ,";
                }
                $orderByStr = substr($orderByStr, 0, -1);
            }else{
                $orderByStr = " id DESC";
            }
            $query->orderBy($orderByStr);

            $whereArr = $index = [];
            foreach($conditions as $key => $val){
                if(!isset($val)){
                    unset($conditions[$key]);
                    continue;
                }
                switch(true){
                    case preg_match("/(>=|>|<|<=|!=)$/",$key,$signal):
                        unset($conditions[$key]);
                        $key = preg_filter("/(>=|>|<|<=|!=)$/", '', $key);
                        $index[$key] = isset($index[$key]) ? $index[$key] + 1 : 0;
                        $query->andWhere("$key {$signal[0]} :{$key}_{$index[$key]}:");
                        $whereArr["{$key}_{$index[$key]}"] = $val;
                        break;
                    default:
                        if(!is_array($val)){
                            $query->andWhere("$key = :$key:");
                        }elseif($val){
                            $inArr = [];
                            foreach ($val as $k=>$v){
                                $whereArr["{$key}_{$k}"] = $v;
                                $inArr[] = ":{$key}_{$k}:";
                            }
                            $inStr = implode(',', $inArr);
                            $query->andWhere("$key IN ({$inStr})");
                            unset($whereArr[$key]);

                        }
                        break;
                }
            }

            if($whereArr){
                $query->bind($whereArr);
            }

            $records = $query->execute();
            $records = $records->toArray();
            $records = isset($records[0]) ? $records[0] : [];

            return $records;

        }catch(Exception $e){
            $sql_info = json_encode(@array_column($e->getTrace(), null,'function')['executePrepared']['args']);
            Log::writeLog("db", $e->getMessage().";errorTrace".$e->getTraceAsString().";sqlInfo:$sql_info",0);

            return false;
        }
    }

    public function saveRecords(){

    }
}