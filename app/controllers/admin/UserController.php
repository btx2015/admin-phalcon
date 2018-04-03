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
    private $model;
    protected function onConstruct()
    {
        parent::onConstruct();
        $this->model = new \AdminUsers();
    }

    public function loginAction(){

    }

    public function addAction(){
        $create = $this->valid->validateParams([
            "username"  => ['username',['PresenceOf']],
            "password"  => ['password',['PresenceOf']],
            "role_id"   => ['role_id',['PresenceOf']],
            "true_name" => ['true_name',[]],
            "phone"     => ['phone',['PresenceOf']],
            "email"     => ['email',['Email']],
        ],$this->params);
        if(isset($create['code']))
            $this->returnResult($create);
        $this->returnResult($this->model->createUserRecord($create));
    }

    public function listAction(){
        $conditions = $this->valid->validateParams([
            "username"  => ['username',[]],
            "phone"     => ['phone',[]],
            "state"     => ['state',['InclusionIn'],['InclusionIn'=>['domain'=>[1,2]]]],
            "page"      => ['page', ['Numericality']],
            "limit"     => ['limit',['Numericality']]
        ]);
        if(isset($conditions['code']))
            $this->returnResult($conditions);
        $this->returnResult($this->model->getUserRecords($conditions));
    }

    public function updateAction(){
        $update = $this->valid->validateParams([
            "id"        => ['id',['PresenceOf','Numericality']],
            "username"  => ['username',[]],
            "state"     => ['state',['InclusionIn'],['InclusionIn'=>['domain'=>[1,2]]]],
            "role_id"   => ['role_id',[]],
            "truename"  => ['truename',[]],
            "phone"     => ['phone',[]],
            "email"     => ['email',['Email']],
        ],$this->params);
        if(isset($update['code']))
            $this->returnResult($update);
        $this->returnResult($this->model->updateUserRecord($update));
    }
}