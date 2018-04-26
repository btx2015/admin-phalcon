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

        $this->assets();
        $_SESSION['rid'] = 2;
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

    protected function returnResult($result = ['code'=>0]){
        if($result['code']){
            if(!isset($result['msg']) || !$result['msg']){
                $config = new \Phalcon\Config\Adapter\Php("../app/config/errMsg.php");
                if($config && isset($config->toArray()[$result['code']]))
                    $result['msg'] = $config->toArray()[$result['code']];
            }
        }else{
            if(!isset($result['msg']) || !$result['msg'])
                $result['msg'] = 'success';
        }
        echo json_encode($result);
        exit();
    }

    public function getMenuData(){
        $menuData = [
            ["name" => " 首  页 " ,"icon" => "icon-home"  ,"controller" => "index" ,"action" => "index" ],
            ["name" => "系统管理" ,"icon" => "icon-cogs",
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

    private function assets(){

        $this->assets->addCss("css/bootstrap.min.css");

        $this->assets->addCss("css/bootstrap-responsive.min.css");

        $this->assets->addCss("css/font-awesome.min.css");

        $this->assets->addCss("css/style-metro.css");

        $this->assets->addCss("css/style.css");

        $this->assets->addCss("css/style-responsive.css");

        $this->assets->addCss("css/default.css");

//        $this->assets->addCss("css/uniform.default.css");

//        $this->assets->addCss("css/jquery.gritter.css");

//        $this->assets->addCss("css/daterangepicker.css");

//        $this->assets->addCss("css/fullcalendar.css");

//        $this->assets->addCss("css/jqvmap.css");

//        $this->assets->addCss("css/jquery.easy-pie-chart.css");

        $this->assets->addJs("js/jquery-1.10.1.min.js");

//        $this->assets->addJs("js/jquery-migrate-1.2.1.min.js");
//
//        $this->assets->addJs("js/jquery-ui-1.10.1.custom.min.js");
//
        $this->assets->addJs("js/bootstrap.min.js");
//
//        $this->assets->addJs("js/jquery.slimscroll.min.js");
//
//        $this->assets->addJs("js/jquery.blockui.min.js");
//
//        $this->assets->addJs("js/jquery.cookie.min.js");
//
//        $this->assets->addJs("js/jquery.uniform.min.js");
//
//        $this->assets->addJs("js/jquery.vmap.js");
//
//        $this->assets->addJs("js/jquery.vmap.russia.js");
//
//        $this->assets->addJs("js/jquery.vmap.world.js");
//
//        $this->assets->addJs("js/jquery.vmap.europe.js");
//
//        $this->assets->addJs("js/jquery.vmap.germany.js");
//
//        $this->assets->addJs("js/jquery.vmap.usa.js");
//
//        $this->assets->addJs("js/jquery.vmap.sampledata.js");
//
//        $this->assets->addJs("js/jquery.flot.js");
//
//        $this->assets->addJs("js/jquery.flot.resize.js");
//
//        $this->assets->addJs("js/jquery.pulsate.min.js");
//
//        $this->assets->addJs("js/date.js");
//
//        $this->assets->addJs("js/daterangepicker.js");
//
//        $this->assets->addJs("js/jquery.gritter.js");
//
//        $this->assets->addJs("js/fullcalendar.min.js");
//
//        $this->assets->addJs("js/jquery.easy-pie-chart.js");
//
//        $this->assets->addJs("js/jquery.sparkline.min.js");
//
        $this->assets->addJs("js/app.js");
//
        $this->assets->addJs("js/index.js");
//
        $this->assets->addJs("js/btx.js");
    }
}
