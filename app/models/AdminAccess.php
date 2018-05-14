<?php

class AdminAccess extends BaseModel
{

    /**
     *
     * @var integer
     * @Primary
     * @Column(column="role_id", type="integer", length=11, nullable=false)
     */
    public $role_id;

    /**
     *
     * @var integer
     * @Primary
     * @Column(column="node_id", type="integer", length=11, nullable=false)
     */
    public $node_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("btx_admin");
        $this->setSource("admin_access");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'admin_access';
    }

    public function getAccess($rid){
        $result = ['code'=>0,'data'=>[]];
        $access = $this->find([
            "conditions" => "role_id = ?1",
            "bind"       => [
                1 => $rid
            ],
            "columns"    => "node_id"
        ])->toArray();
        if(empty($access))
            return $result;
        $result['data'] = array_column($access,'node_id');
        return $result;
    }

    public function addAccess($create = [],$pid = 1){
        if(empty($create['access']))
            return ['code' => 40000];
        if($pid != 1){
            $access = $this->find([
                "conditions" => "role_id = ?1",
                "bind" => [
                    1 => $pid
                ],
                "columns" => "node_id"
            ])->toArray();
            if(empty($access))
                return ['code' => 40002];
            $access = array_column($access,'node_id');
        }else{
            $nodeModel = new AdminNode();
            $access = $nodeModel->find([
                "columns" => "id"
            ])->toArray();
            $access = array_column($access,'id');
        }
        $data = [];
        foreach($create['access'] as $v){
            if(!in_array($v,$access))
                continue;
            $data[] = ['role_id' => $create['role_id'],'node_id' => $v];
        }
        if(empty($data))
            return ['code' => 40002];
        if($this->saveRecords($data) !== true)
            return ['code' => 40000];
        return ['code' => 0];
    }

    public function delAccess($update = [],$children = []){
        $access = $this->findFirst([
            "conditions" => "id = ?1",
            "bind" => [
                1 => $update['id'],
            ]
        ]);
        if(!$access)
            return ['code' => 40003];
        $node = $this->find([
            "conditions" => "node_id = ?1",
            "bind" => [
                1 => $access->node_id
            ]
        ]);
        try{
            $transaction = $this->manager->get();
            $this->setTransaction($transaction);
            foreach($node as $v){
                if($v->role_id == $update['role_id'] || in_array($v->role_id,$children)){
                    if($v->delete() !== true){
                        $transaction->rollback();
                        return ['code' => 40001];
                    }
                }
            }
            $transaction->commit();
            return ['code' => 0];
        }catch(Phalcon\Mvc\Model\Transaction\Failed $e){
            Log::writeLog('db',$e->getMessage(),0);
        }
    }

    public function saveRoleAccess($create = []){
        $accesses = $this->find([
            "conditions" => "role_id = ?1",
            "bind" => [
                1 => $create['role_id']
            ]
        ]);
        $accessCreate = [];
        foreach($create['access'] as $v){
            $accessCreate[] = [
                'role_id' => $create['role_id'],
                'node_id' => $v
            ];
        }
        try{
            $transaction = $this->manager->get();
            $this->setTransaction($transaction);
            foreach($accesses as $access){
                if($access->delete() === false){
                    $transaction->rollback();
                    return ['code' => 40001];
                }
            }
            if($this->saveRecords($accessCreate) !== true){
                $transaction->rollback();
                return ['code' => 40000];
            }
            $transaction->commit();
            return ['code' => 0];
        }catch(Phalcon\Mvc\Model\Transaction\Failed $e){
            Log::writeLog('db',$e->getMessage(),0);
        }
    }
}
