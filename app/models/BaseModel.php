<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27
 * Time: 13:27
 */
use \Phalcon\Mvc\Model\Transaction\Manager;
use \Phalcon\Mvc\Model\Transaction\Failed;

class BaseModel extends \Phalcon\Mvc\Model
{

    protected $_message;

    public $conn;

    protected $manager;

    protected $transaction;

    protected $failed;

    protected function onConstruct()
    {
        $this->conn = $this->getReadConnection();
        $this->manager = new Manager();
    }

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

    public function getRecordsByCondition($conditions = [],$fields = [],$limit = 0,$offset = 0,$orderBy = []){
        try{
            $query = $this->query();
            $query->where('1 = 1');

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


            if($limit != -1){
                $query->columns(['COUNT(*) AS total']);
                $total = $query->execute();
                $total = $total->getFirst();
                $total = $total ? $total->total : 0;
            }

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

            $records = $query->execute();
            $records = $records->toArray();


            if($limit != -1){
                return [$records ,$total];
            }

            return $records;

        }catch(Exception $e){
            $sql_info = json_encode(@array_column($e->getTrace(), null,'function')['executePrepared']['args']);
            Log::writeLog("db", $e->getMessage().";errorTrace".$e->getTraceAsString().";sqlInfo:$sql_info",0);

            return [false,false];
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
        return $this->getRecordsByCondition($conditions,$fields,$limit,$offset,$orderBy);
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


    public function saveRecords($insertInfos, $insertFields = array(), $on_update = false){
        try {

            if (! isset($insertInfos) || ! is_array($insertInfos) || ! $insertInfos) {
                throw new Exception('empty insert info');
            }

            // 整理要插入的字段
            if (! isset($insertFields) || ! is_array($insertFields) || ! $insertFields) {
                $insertFields = array_keys($insertInfos[0]);
                $insertFields[] = 'created_at';
            }

            $nowTime = time();
            // 拼接SQL文字符串,编辑插入参数
            $insertStr = '';
            foreach ($insertInfos as $key => &$insertInfo) {
                $insertInfo['created_at'] = $nowTime;

                $insertField = '';
                $insertValue = '';
                foreach ($insertFields as $v) {
                    if (array_key_exists($v, $insertInfo)) {
                        $insertField = $insertField . "`$v`,";
                        $insertValue = $insertValue . ":{$v}_{$key},";
                        $insertParams["{$v}_{$key}"] = isset($insertInfo[$v]) ? $insertInfo[$v] : null;
                    }
                }
                $insertValue = substr($insertValue, 0, - 1);
                $insertStr = $insertStr . "({$insertValue}),";
            }
            $insertField = substr($insertField, 0, - 1);
            $insertStr = substr($insertStr, 0, - 1);

            // 是否重复的数据更新
            if ($on_update) {
                $duplicateStr = " ON DUPLICATE KEY UPDATE ";
                foreach ($insertFields as $v) {
                    if (array_key_exists($v, $insertInfo)) {
                        $duplicateStr = $duplicateStr . "`$v`=VALUES(`$v`),";
                    }
                }
                $duplicateStr = rtrim($duplicateStr, ',');
            } else {
                $duplicateStr = '';
            }

            // 编辑SQL文
            $sql = "INSERT INTO `" . $this->getSchema() . "`.`{$this->getSource()}`
					(	$insertField
					)
					VALUES
					$insertStr
					$duplicateStr";

            $cmd = $this->conn->prepare($sql);
            $cmd = $this->conn->executePrepared($cmd, $insertParams, []);

            if ($cmd->errorCode() == '00000') {
                $exeRtn = true;
            } else {
                Log::writeLog('db',json_encode($cmd->errorInfo()) . ";sql:$sql", 'ERROR');
                $exeRtn = false;
            }
        } catch (Exception $e) {
            Log::writeLog('db',$e->getMessage() . ';trace:' . $e->getTraceAsString() . ';sql:' . @$sql, 'ERROR');
            $exeRtn = false;
        }

        $conn = null;
        return $exeRtn;
    }

    public function translateRecords($records = [],$fields = []){

        foreach($fields as $k => $v){
            if(is_array($v)){
                $data = array_flip(array_column($v['data'],$v['source']));
                foreach($records as &$a){
                    $a[$k.'_str'] = $v['data'][$data[$a[$k]]][$v['target']];
                }
            }else if($v === 'time'){
                foreach($records as &$a)
                    $a[$k.'_str'] = date("Y-m-d H:i:s");
            }else{
                if(!isset($translate)){
                    $translateConfig = new \Phalcon\Config\Adapter\Php("../app/config/translate.php");
                    $translate = $translateConfig->toArray();
                }
                foreach($records as &$a)
                    $a[$k.'_str'] = $translate[$v][$a[$k]];
            }
            unset($a);
        }

        return $records;
    }
}