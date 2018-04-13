<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27
 * Time: 13:06
 */

namespace admin;


class NodeController extends \ControllerBase
{
    private $model;
    protected function onConstruct()
    {
        parent::onConstruct();
        $this->model = new \AdminNode();
    }

    public function addAction(){
        $create = $this->valid->validateParams([
            "name"      => ['name',  ['PresenceOf']],
            "tittle"    => ['tittle',['PresenceOf']],
            "pid"       => ['pid',   ['PresenceOf','Numericality']],
            "level"     => ['level', ['PresenceOf','InclusionIn'],['InclusionIn'=>['domain'=>[1,2,3]]]]
        ],$this->params);
        if(isset($create['code']))
            $this->returnResult($create);
        $this->returnResult($this->model->createNodeRecords($create));
    }
}