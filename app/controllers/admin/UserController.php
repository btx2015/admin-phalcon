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

    public function listAction(){
        $conditions = $this->valid->validate([
            "username"  => [[]],
            "phone"     => [[]],
            "state"     => [['InclusionIn'],['InclusionIn'=>['domain'=>[1,2,3]]]],
            "page"      => [['Numericality']],
            "limit"     => [['Numericality']]
        ]);
        if(!$conditions) exit($conditions);
        $model = new \AdminUsers();
        $data = $model->getUserRecords($conditions);
    }
}