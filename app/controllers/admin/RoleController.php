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
        if(isset($conditions['code']))
            $this->returnResult($create);
        $this->returnResult($this->model->createRoleRecords($create));
    }

    public function updateAction(){
        $create = $this->valid->validateParams([
            "id"        => ['id',['PresenceOf']],
            "name"      => ['name',[]],
            "pid"       => ['pid',['Numericality']],
            "state"     => ['state',['InclusionIn'],['InclusionIn'=>['domain'=>[1,2,3]]]]
        ],$this->params);
        if(isset($conditions['code']))
            $this->returnResult($create);
        $this->returnResult($this->model->createRoleRecords($create));
    }
}