<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {

    }

    public function testAction(){
        $this->returnResult(['code'=>0,'data'=>123]);
    }

}

