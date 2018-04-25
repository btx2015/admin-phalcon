<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27
 * Time: 13:07
 */

namespace admin;


class MenuController extends \ControllerBase
{
    public function getMenuData(){
        $menuData = [
            ["name" => " 首  页 " ,"icon" => "icon-home"  ,"controller" => "index" ,"action" => "index" ],
            ["name" => "系统管理" ,"icon" => "icon-cogs",
                "child" => [
                    ["name" => "角色管理" ,"icon" => "icon-group" ,"controller" => "role"   ,"action" => "index" ],
                    ["name" => "管 理 员" ,"icon" => "icon-user"  ,"controller" => "user"   ,"action" => "index" ],
                    ["name" => "系统设置" ,"icon" => "icon-cogs"  ,"controller" => "config" ,"action" => "index" ],
                ]
            ],
        ];
        $menu = [];
        $access = [
            'admin' => [
                'index'=> ['index' => 1],
                'role' => ['index' => 1],
                'user' => ['index' => 1],
                'config' => ['index' => 1],
            ]
        ];
        foreach($menuData as &$v){
            if(isset($v['child'])){
                $child = [];
                foreach($v['child'] as &$a){
                    if(!isset($access['admin'][$a['controller']][$a['action']])){
                        unset($a);
                        continue;
                    }
                    $child[] = [
                        "tittle" => $a['name'],
                        "icon" => $a['icon'],
                        "href" => "/admin/".$a['controller']."/".$a['action'],
                    ];
                }
                if(empty($child)){
                    unset($v);
                    continue;
                }
                $menu[] = [
                    "tittle" => $v['name'],
                    "icon" => $v['icon'],
                    "child" => $child,
                ];
            }else{
                if(!isset($access['admin'][$v['controller']][$v['action']]))
                    continue;
                $menu[] = [
                    "tittle" => $v['name'],
                    "icon" => $v['icon'],
                    "href" => "/admin/".$v['controller']."/".$v['action'],
                ];
            }
        }

        return $menu;
    }
}