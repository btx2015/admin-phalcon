<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;

class ControllerBase extends Controller
{
    protected $request;
    protected $params;
    protected $ip;
    protected $uri;
    protected $config;
    protected $accessConfig;

    protected function onConstruct(){
        $this->config = require "../app/config/config.php";
        $this->request = new request();
        $this->initParams();
        $this->checkAuth();
    }

    protected function initParams(){
        $this->uri = $this->request->get('_url');
        $access = $this->config->access->toArray();
        $accessDefault = $access['default'];
        $accessUri = $access[$this->uri] ?? [];
        $this->accessConfig = array_merge($accessDefault,$accessUri);
        if($this->request->isPost()){
            $post = json_decode($this->request->getRawBody(),true);
            if(!$post)
                $post = $this->request->getPost();
            $this->params = $post ? $post : [];
        }else{
            if($this->accessConfig['method'] != 'GET')
                exit('method error!');
            $this->params = $this->request->getQuery();
        }
    }

    protected function checkAuth(){
        if($this->accessConfig['require_login'])
            exit('need login!');
//        if(!isset($_SESSION['uid']) || !$_SESSION['uid'])
//            exit('need login!');
        if(!$this->checkAccess())
            exit('permission denied');
    }

    protected function checkAccess(){
        return true;
    }

}
