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

    protected function onConstruct(){
        $this->config = require "../app/config/config.php";
        $this->request = new request();
        $this->initParams();
        $this->checkAuth();
        $this->valid = new Validators();

        $menu = $this->getMenuData();
        $this->view->setVar('menu',$menu);

        $this->initAssets();
        $_SESSION['rid'] = 1;
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
        if(!isset($_SESSION['access'])){
            $_SESSION['access'] = $accessModel->getNode(1,'name');
        }
        //$this->dump($_SESSION['access']);
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

    public function getMenuData(){
        $menuData = [
            ["name" => " 首  页 " ,"icon" => "&#xe68e;"  ,"controller" => "index" ,"action" => "index" ],
            ["name" => "系统管理" ,"icon" => "&#xe620;",
                "child" => [
                    ["name" => "角色管理" ,"icon" => "icon-group" ,"controller" => "role"   ,"action" => "index" ],
                    ["name" => "管 理 员" ,"icon" => "icon-user"  ,"controller" => "admin"  ,"action" => "index" ],
                    ["name" => "系统设置" ,"icon" => "icon-cogs"  ,"controller" => "config" ,"action" => "index" ],
                ]
            ],
        ];
        $menu = [];
        $access = [
            'admin' => [
                'index'=> ['index' => 1],
                'role' => ['index' => 1],
                'admin' => ['index' => 1],
                'config' => ['index' => 1],
            ]
        ];
        foreach($menuData as &$v){
            $active = false;
            if(isset($v['child'])){
                $child = [];
                foreach($v['child'] as &$a){
                    if(!isset($access['admin'][$a['controller']][$a['action']])){
                        unset($a);
                        continue;
                    }
                    $children = [
                        "tittle" => $a['name'],
                        "icon" => $a['icon'],
                        "href" => "/admin/".$a['controller']."/".$a['action'],
                    ];
                    if($this->uri === $children['href']){
                        $children['active'] = 'active';
                        $active = true;
                    }

                    $child[] = $children;
                }
                if(empty($child)){
                    unset($v);
                    continue;
                }
                $menus = [
                    "tittle" => $v['name'],
                    "icon" => $v['icon'],
                    "child" => $child,
                ];
                if($active)
                    $menus['active'] = 'active';
                $menu[] = $menus;
            }else{
                if(!isset($access['admin'][$v['controller']][$v['action']]))
                    continue;

                $menus = [
                    "tittle" => $v['name'],
                    "icon" => $v['icon'],
                    "href" => "/admin/".$v['controller']."/".$v['action'],
                ];
                if($this->uri === $menus['href'])
                    $menus['active'] = 'active';
                $menu[] = $menus;
            }
        }

        return $menu;
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
