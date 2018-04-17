<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27
 * Time: 13:05
 */

namespace admin;


class RoleController extends \ControllerBase
{
    private $model;
    protected function onConstruct()
    {
        parent::onConstruct();
        $this->model = new \AdminRole();
    }

    public function listAction(){
        $conditions = $this->valid->validateParams([
            "name"      => ['name',[]],
            "pid"       => ['pid',['Numericality']],
            "state"     => ['state',['InclusionIn'],['InclusionIn'=>['domain'=>[1,2]]]],
            "page"      => ['page', ['Numericality']],
            "limit"     => ['limit',['Numericality']]
        ],$this->params);
        if(isset($conditions['code']))
            $this->returnResult($conditions);
        $this->returnResult($this->model->getRoleRecords($conditions));
    }

    public function addAction(){
        $create = $this->valid->validateParams([
            "name"      => ['name',['PresenceOf']],
            "pid"       => ['pid',['PresenceOf','Numericality']],
        ],$this->params);
        if(isset($create['code']))
            $this->returnResult($create);
        $this->returnResult($this->model->createRoleRecords($create));
    }

    public function updateAction(){
        $update = $this->valid->validateParams([
            "id"        => ['id',['PresenceOf']],
            "name"      => ['name',[]],
            "pid"       => ['pid',['Numericality']],
            "state"     => ['state',['InclusionIn'],['InclusionIn'=>['domain'=>[1,2,3]]]]
        ],$this->params);
        if(isset($update['code']))
            $this->returnResult($update);
        $this->returnResult($this->model->createRoleRecords($update));
    }

    public function assignAction(){
        $create = $this->valid->validateParams([
            "rid"       => ['rid',['PresenceOf','Numericality']]
        ],$this->params);
        if(isset($create['code']))
            $this->returnResult($create);
        $accessModel = new \AdminRole();
        $this->returnResult($accessModel->getRoleAccess($create['rid']));
    }

    public function addAccessAction(){
        $create = $this->valid->validateParams([
            "rid"       => ['role_id',['PresenceOf','Numericality']],
            "access"    => ['access',['PresenceOf','Access']]
        ],$this->params);
        if(isset($create['code']))
            $this->returnResult($create);
        $this->returnResult($this->model->addRoleAccess($create));
    }

    public function delAccessAction(){
        $update = $this->valid->validateParams([
            "rid" => ['role_id',['PresenceOf','Numericality']],
            "aid" => ['id',['PresenceOf','Numericality']]
        ],$this->params);
        if(isset($update['code']))
            $this->returnResult($update);
        $accessModel = new \AdminRole();
        $this->returnResult($accessModel->delRoleAccess($update));
    }
}