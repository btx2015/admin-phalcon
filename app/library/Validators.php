<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/28
 * Time: 22:25
 */

use Phalcon\Validation\Validator\Callback;

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
                if (isset($v[2][$a])) {
                    if(!is_array($v[2][$a]) || empty($v[2][$a]))
                        return ['code'=>10006,'msg'=>"The filters is invalid."];
                    $object = $this->$a($v[2][$a]);
                } else {
                    $object = $this->$a();
                }
                if (!$object)
                    return ['code'=>10006,'msg'=>"The filters is invalid."];
                $this->add([$k], $object);
            }
        }
        $checkResult = $this->validate($params);
        if(count($checkResult)){
            foreach($checkResult as $msg)
                return ['code'=>10006,'msg'=>$msg->getMessage()];
        }
        return $data;
    }

    function __call($func_name,$args=[]){
        $func_name = 'Phalcon\\Validation\\Validator\\'.$func_name;
        if(!class_exists($func_name))
            return false;
        return new $func_name($args[0] ?? []);
    }

    private function Access(){
        return new Callback([
            "message" => "The access is error",
            "callback" => function($data) {
                $access = $data['access'];
                if(!is_array($access) || empty($access))
                    return false;
                foreach($access as $v)
                    if(!is_numeric($v))
                        return false;
                return true;
            }
        ]);
    }

    private function Roles(){
        return new Callback([
            "message" => "The roles is error",
            "callback" => function($data) {
                if(!isset($data['roles']))
                    return false;
                $access = $data['roles'];
                if(!is_array($access) || empty($access))
                    return false;
                $access = array_unique($access);
                foreach($access as $v)
                    if(!is_numeric($v))
                        return false;
                return true;
            }
        ]);
    }

    private function Users(){
        return new Callback([
            "message" => "The users is error",
            "callback" => function($data) {
                if(!isset($data['users']))
                    return false;
                $access = $data['users'];
                if(!is_array($access) || empty($access))
                    return false;
                $access = array_unique($access);
                foreach($access as $v)
                    if(!is_numeric($v))
                        return false;
                return true;
            }
        ]);
    }
}