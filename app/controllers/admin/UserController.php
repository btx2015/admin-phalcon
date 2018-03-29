<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27
 * Time: 13:07
 */

namespace admin;

class UserController extends \ControllerBase
{
    protected function onConstruct()
    {
        parent::onConstruct();
    }

    public function loginAction(){
        $this->valid->add([
            ["username","password","captcha"],
            new PresenceOf([
                "message" => [
                    "username" => "The username is required!",
                    "password" => "The password is required!",
                    "captcha"  => "The captcha is required!",
                ]
            ])
        ]);
        $check_result = $this->valid->validate($this->params);
        if (count($check_result))
            foreach ($check_result as $m)
                exit($this->validMessageToStr($check_result)); // 参数错误
    }

    public function verifyAction(){
        $valid = new \Validators();
        $res = $valid->validateParams([
            "username"  => [['PresenceOf']],
            "phone"     => [['PresenceOf']],
        ]);
    }

    public function addAction(){
        $valid = new \Validators();
        $create = $valid->validateParams([
            "username"  => [['PresenceOf']],
            "password"  => [['PresenceOf']],
            "role_id"   => [['PresenceOf']],
            "true_name" => [[]],
            "phone"     => [['PresenceOf']],
            "email"     => [['Email']],
        ],$this->params);
        if(!is_array($create)) exit($create);
        $model = new \AdminUsers();
        $this->returnResult($model->createUserRecords($create));
    }

    public function listAction(){
        $valid = new \Validators();
        $conditions = $valid->validateParams([
            "username"  => [[]],
            "phone"     => [[]],
            "state"     => [['InclusionIn'],['InclusionIn'=>['domain'=>[1,2,3]]]],
            "page"      => [['Numericality']],
            "limit"     => [['Numericality']]
        ]);
        if(!$conditions) exit($conditions);
        $model = new \AdminUsers();
        $data = $model->getUserRecords($this->params);
        var_dump($data);die;
    }
}