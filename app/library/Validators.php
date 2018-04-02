<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/28
 * Time: 22:25
 */

class Validators extends Phalcon\Validation
{

    public function validateParams($filters = [],$params = []){
        if(!is_array($filters) || empty($filters))
            return $params;
        $data = [];
        foreach($filters as $k => $v) {
            if (!is_array($v) || !isset($v[1]) || !is_array($v[1]))
                return ['code'=>10006,'msg'=>"The filters is invalid."];
            if ((!isset($params[$k]) || $params[$k] === '') && !in_array('PresenceOf', $v[1]))
                continue;
            if(isset($params[$k])){
                if(!is_string($v[0]))
                    return ['code'=>10006,'msg'=>"The filters is invalid."];
                $data[$v[0]] = $params[$k];
            }
            foreach ($v[1] as $a) {
                if (isset($v[2]) && $v[2][$a]) {
                    $object = $this->$a($v[2][$a]);
                } else {
                    $object = $this->$a();
                }
                if (!$object)
                    return ['code'=>10006,'msg'=>"The filters is invalid."];
                $this->add([$k], $object);
            }
        }
        $checkResult = $this->validate($data);
        if(count($checkResult)){
            foreach($checkResult as $msg)
                return ['code'=>10006,'msg'=>$msg];
        }
        return $data;
    }

    function __call($func_name,$args=[]){
        $func_name = 'Phalcon\\Validation\\Validator\\'.$func_name;
        if(!class_exists($func_name))
            return false;
        return new $func_name($args[1] ?? []);
    }
}