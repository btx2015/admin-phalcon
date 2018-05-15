<?php

class AdminNode extends BaseModel
{
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("btx_admin");
        $this->setSource("admin_node");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'admin_node';
    }


    public function getFormatNode($nodeId,$field = 'name'){
        if($nodeId === 'all'){
            $nodes = $this->find([
                "columns" => "id,pid,level,".$field,
                "order" => "id"
            ])->toArray();
        }else{
            $nodes = $this->getRecordsByCondition(['id'=>$nodeId],['id','pid','level',$field],-1,0,['id'=>0]);
        }
        return $this->$field($nodes);
    }

    private function title($nodes,$pid = 0){
        $nodesData = [];
        foreach($nodes as $k => $v){
            if($v['pid'] == $pid){
                unset($nodes[$k]);
                $child = $this->title($nodes,$v['id']);
                if(!empty($child))
                    $v['child'] = $child;
                unset($v['pid']);
                unset($v['level']);
                $nodesData[] = $v;
            }
        }
        return $nodesData;
    }

    private function name($nodes,$pid = 0){
        $nodesData = [];
        foreach($nodes as $k => $v){
            if($v['pid'] == $pid){
                unset($nodes[$k]);
                if($v['level'] == 4){
                    $actions = explode(',',$v['name']);
                    foreach($actions as $action){
                        $nodesData[$action] = 1;
                    }
                }else{
                    $nodesData[$v['name']] = 1;
                }
                $child = $this->name($nodes,$v['id']);
                if(!empty($child) && $v['level'] < 3)
                    $nodesData[$v['name']] = $child;
            }
        }
        return $nodesData;
    }
}
