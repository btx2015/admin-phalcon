<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {

    }

    public function testAction(){
        $valid = new Validators();
        $result = $valid->validateParams([
            "username"  => [[]],
            "phone"     => [[]],
            "state"     => [['InclusionIn'],['InclusionIn'=>['domain'=>[1,2]]]],
            "page"      => [['Numericality']],
            "limit"     => [['Numericality']]
        ],$this->params);
        if($result !== true) echo $result;
        die('end');
    }

}

