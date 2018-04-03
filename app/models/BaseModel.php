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


    /**
     * Overriding create
     * @param null $create
     * @param null $whiteList
     * @return bool
     */
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
            $res = parent::getWriteConnection();
            return $res->lastInsertId(parent::getSource());
        }catch(ErrorException $e){
            print_r($e->getMessage());
            exit();
        }
    }

    /**
     * Overriding create
     * @param null $create
     * @param null $whiteList
     * @return bool
     */
    public function update($create = NULL,$whiteList = NULL){
        try{
            if(parent::update($create) !== true){
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

    /**
     * Get records by conditions
     * @param array $conditions
     * @param array $fields
     * @return array
     */
    public function getRecords($conditions = [],$fields = []){
        if(!is_array($conditions) || !is_array($fields))  return [false,false];
        list($conditions,$limit,$offset,$orderBy) = $this->beforeGetRecords($conditions);
        try{
            $query = $this->query();
            $query->where('1 = 1');

            if($fields)
                $query->columns($fields);

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
                            $whereArr[$key] = $val;
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
            if($whereArr)
                $query->bind($whereArr);

            $records = $query->execute();
            $records = $records->toArray();

            $query->columns(['COUNT(*) AS total']);
            $total = $query->execute();
            $total = $total->getFirst();

            $total = $total ? $total->total : 0;

            return [$records,$total];

        }catch(Exception $e){
            $sql_info = json_encode(@array_column($e->getTrace(), null,'function')['executePrepared']['args']);
            Log::writeLog("db", $e->getMessage().";errorTrace".$e->getTraceAsString().";sqlInfo:$sql_info",0);

            return [false,false];
        }
    }

    /**
     * Explode condition limit page and order from params
     * @param array $condition
     * @return array
     */
    public function beforeGetRecords($condition = []){
        $limit = 20;
        $page = 1;
        $order = ['id'=>1];
        if(isset($condition['limit']) && is_numeric($limit)){
            $limit = $condition['limit'];
            unset($condition['limit']);
        }
        if(isset($condition['page']) && is_numeric($page)){
            $page = $condition['page'];
            unset($condition['page']);
        }
        if(isset($condition['order']) && is_array($condition['order'])){
            $order = $condition['order'];
            unset($condition['order']);
        }
        $limit = $limit <= 50 ? $limit : 50;
        $offset = ($page - 1) * $limit;
        return [$condition,$limit,$offset,$order];
    }


    public function saveRecords(){

    }
}