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
    protected $valid;

    /**
     * 获取配置
     * 权限验证
     * 参数处理
     * 加载资源
     */
    protected function onConstruct(){
        $_SESSION['rid'] = 1;
        $this->config = require "../app/config/config.php";
        $this->request = new request();
        $this->uri = $this->request->get('_url');
        $this->checkAuth();
        $this->initParams();
        $this->initAssets();
        $this->valid = new Validators();
    }

    protected function initParams(){
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
        $this->ip = $_SERVER['REMOTE_ADDR'];
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
        $accessModel = new \AdminAccess();
        if(!isset($_SESSION['rid']) || !isset($_SESSION['access'])){
            //TODO 跳转登录 同时验证uid rid
            $_SESSION['access'] = $accessModel->getNode($_SESSION['rid'],'name');
        }
        if($this->uri !== '/admin/index/index'){
            $route = explode('/',$this->uri);
            if(!isset($_SESSION['access'][$route[1]][$route[2]][$route[3]])){
                return false;
            }
        }

        return true;
    }

    protected function returnResult($result = []){
        if(isset($result['code']) && $result['code'] != 0){
            if(!isset($result['msg']) || !$result['msg']){
                $config = new \Phalcon\Config\Adapter\Php("../app/config/errMsg.php");
                if($config && isset($config->toArray()[$result['code']]))
                    $result['msg'] = $config->toArray()[$result['code']];
            }
        }else{
            $result = array_merge(['code'=>0,'msg'=>'success'],$result);
        }
        echo json_encode($result);
        exit();
    }

    private function initAssets(){

        $this->assets->addCss("layui/css/layui.css");
        $this->assets->addJs("layui/layui.js");
        $this->assets->addJs("btx.js");
    }

    public function dump($var, $echo=true, $label=null, $strict=true) {
        $label = ($label === null) ? '' : rtrim($label) . ' ';
        if (!$strict) {
            if (ini_get('html_errors')) {
                $output = print_r($var, true);
                $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            } else {
                $output = $label . print_r($var, true);
            }
        } else {
            ob_start();
            var_dump($var);
            $output = ob_get_clean();
            if (!extension_loaded('xdebug')) {
                $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
                $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            }
        }
        if ($echo) {
            echo($output);
            return null;
        }else
            return $output;
    }
}
