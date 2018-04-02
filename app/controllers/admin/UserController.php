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

    public function addAction(){
        $valid = new \Validators();
        $create = $valid->validateParams([
            "username"  => ['username',['PresenceOf']],
            "password"  => ['password',['PresenceOf']],
            "role_id"   => ['role_id',['PresenceOf']],
            "true_name" => ['true_name',[]],
            "phone"     => ['phone',['PresenceOf']],
            "email"     => ['email',['Email']],
        ],$this->params);
        if(isset($create['code']))
            $this->returnResult($create);
        $model = new \AdminUsers();
        $this->returnResult($model->createUserRecords($create));
    }

    public function listAction(){
        $valid = new \Validators();
        $conditions = $valid->validateParams([
            "username"  => ['username',[]],
            "phone"     => ['phone',[]],
            "state"     => ['state',['InclusionIn'],['InclusionIn'=>['domain'=>[1,2]]]],
            "page"      => ['page',['Numericality']],
            "limit"     => ['limit',['Numericality']]
        ]);
        if(isset($conditions['code']))
            $this->returnResult($conditions);
        $model = new \AdminUsers();
        $this->returnResult($model->getUserRecords($this->params));
    }
}