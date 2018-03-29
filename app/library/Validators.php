<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/28
 * Time: 11:15
 */

class Validators extends Phalcon\Validation
{

    public function validateParams($filters = [],$params = []){
        if(!is_array($filters) || empty($filters))
            return $params;
        if(empty($params))
            return "The params is empty.";
        $data = [];
        foreach($filters as $k => $v) {
            if (!is_array($v) || !isset($v[0]) || !is_array($v[0]))
                return "The filters is invalid.";
            if ((!isset($params[$k]) || $params[$k] === '') && !in_array('PresenceOf', $v[0]))
                continue;
            if(isset($params[$k]))
                $data[$k] = $params[$k];
            foreach ($v[0] as $a) {
                if (isset($v[1]) && $v[1][$a]) {
                    $object = $this->$a($v[1][$a]);
                } else {
                    $object = $this->$a();
                }
                if (!$object)
                    return 'The filters is invalid.';
                $this->add([$k], $object);
            }
        }
        $checkResult = $this->validate($data);
        if(count($checkResult)){
            $error = '';
            foreach($checkResult as $msg)
                $error .= $msg.',';
            return $error;
        }
        return $data;
    }

    function __call($func_name,$args=[]){
        $func_name = 'Phalcon\\Validation\\Validator\\'.$func_name;
        if(!class_exists($func_name))
            return false;
        return new $func_name($args[0] ?? []);
    }
}