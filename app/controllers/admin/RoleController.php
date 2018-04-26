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

    public function indexAction(){
        $this->assets->addCss("css/select2_metro.css");
        $this->assets->addCss("css/DT_bootstrap.css");
        $this->assets->addJs("js/jquery-migrate-1.2.1.min.js");
        $this->assets->addJs("js/jquery-ui-1.10.1.custom.min.js");
        $this->assets->addJs("js/jquery.slimscroll.min.js");
        $this->assets->addJs("js/jquery.blockui.min.js");
        $this->assets->addJs("js/select2.min.js");
        $this->assets->addJs("js/jquery.dataTables.js");
        $this->assets->addJs("js/DT_bootstrap.js");
        $this->assets->addJs("js/table-managed.js");
        $this->assets->addJs("js/btx-table.js");
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

    public function editAction(){
        $update = $this->valid->validateParams([
            "id"        => ['id',['PresenceOf']],
            "name"      => ['name',[]],
            "pid"       => ['pid',['Numericality']],
            "state"     => ['state',['InclusionIn'],['InclusionIn'=>['domain'=>[1,2,3]]]]
        ],$this->params);
        if(isset($update['code']))
            $this->returnResult($update);
        $this->returnResult($this->model->updateRoleRecords($update));
    }

    public function enableAction(){
        $update = $this->valid->validateParams([
            "roles" => ['roles',['PresenceOf','Roles']]
        ],$this->params);
        $this->returnResult($this->model->updateStateForRoles($update['roles'],1));
    }

    public function disableAction(){
        $update = $this->valid->validateParams([
            "roles" => ['roles',['PresenceOf','Roles']]
        ],$this->params);
        $this->returnResult($this->model->updateStateForRoles($update['roles'],2));
    }

    public function deleteAction(){
        $update = $this->valid->validateParams([
            "roles" => ['roles',['PresenceOf','Roles']]
        ],$this->params);
        $this->returnResult($this->model->updateStateForRoles($update['roles'],3));
    }

    public function assignAction(){
        $create = $this->valid->validateParams([
            "rid" => ['rid',['PresenceOf','Numericality']]
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