<?php

class AdminRole extends BaseModel
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(column="name", type="string", length=30, nullable=false)
     */
    public $name;

    /**
     *
     * @var integer
     * @Column(column="pid", type="integer", length=11, nullable=false)
     */
    public $pid;

    /**
     *
     * @var integer
     * @Column(column="state", type="integer", length=1, nullable=false)
     */
    public $state;

    /**
     *
     * @var integer
     * @Column(column="created_at", type="integer", length=11, nullable=false)
     */
    public $created_at;

    /**
     *
     * @var string
     * @Column(column="updated_at", type="string", nullable=false)
     */
    public $updated_at;

    /**
     *
     * @var integer
     * @Column(column="updated_admin_id", type="integer", length=11, nullable=false)
     */
    public $updated_admin_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("btx_admin");
        $this->setSource("admin_role");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'admin_role';
    }

    public function getAllRoles(){
        $conditions['state'] = 1;
        if($_SESSION['rid'] != 1){
            $children = $this->findChildByParentId($_SESSION['rid']);
            if(empty($children))
                return ['code'=>0,'data'=>[],'total'=>0];
            $conditions['id'] = array_unshift($children,$_SESSION['rid']);
        }
        return [
            'code' => 0,
            'data' =>  $this->getRecordsByCondition($conditions,['id','name'],-1)
        ];
    }

    public function getRoleRecords($conditions = []){
        if($_SESSION['rid'] != 1){
            $children = $this->findChildByParentId($_SESSION['rid']);
            if(empty($children))
                return ['code'=>0,'data'=>[],'total'=>0];
            $conditions['id'] = $children;
            if(isset($conditions['pid']) && !in_array($conditions['pid'],$children))
                return ['code'=>0,'data'=>[],'total'=>0];
        }else{
            $conditions['id!='] = 1;
        }
        list($data,$total) = $this->getRecords($conditions,
            ['id','name','pid','state','created_at']);
        if($data){
            $pidData = array_unique(array_column($data,'pid'));
            $parents = $this->getRecordsByCondition(['id'=>$pidData],['id','name'],-1);
            $data = $this->translateRecords($data,[
                'pid' => ['data' => $parents,'source' => 'id','target' => 'name'],
                'state' => 'state',
                'created_at' => 'time']);
        }
        return [
            'code' => 0,
            'data' => $data,
            'count' => $total,
        ];
    }

    public function createRoleRecords($create = []){
        if(empty($create))
            return ['code'=>30000];//Empty array
        if($this->findFirst([
            'conditions' => 'name = ?1 AND state != 3',
            'bind' => [
                1 => $create['name']
            ]
        ]))
            return ['code'=>30001];//The role is already exists
        if(!$this->checkChildOfRole($create['pid']))
            return ['code'=>30004];
        if(!$this->create($create)){
            return ['code'=>30000];//Create fail
        }
        return ['code'=>0];
    }

    public function updateRoleRecords($update = []){
        $user = $this->findFirst([
            'conditions' => 'id = ?1 AND state != 3',
            'bind' => [
                1 => $update['id']
            ]
        ]);
        if(!$user)
            return ['code'=>20002];//User is not exists.

        if(!$this->checkChildOfRole($update['id']))
            return ['code'=>30004];

        if(isset($update['pid']) && !$this->checkChildOfRole($update['pid']))
            return ['code'=>30004];

        if($user->update($update) !== true)
            return ['code'=>20003];//Failed
        return ['code'=>0];
    }

    public function updateStateForRoles($roles = [] ,$state = 1){
        $where = ['id' => $roles];
        if($state == 1){
            $where['state'] = 2;
        }else if($state == 2){
            $where['state'] = 1;
        }else{
            $where['state!='] = 3;
        }
        $rolesData = $this->getRecordsByCondition($where,['id'],-1);
        if(count($roles) != count($rolesData))
            return ['code' => 30002];
        $roleChildren = $this->findChildByParentId($_SESSION['rid']);
        foreach($rolesData as &$v){
            if(!in_array($v['id'],$roleChildren))
                return ['code' => 30004];
            $v['state'] = $state;
            unset($v);
        }
        if($this->saveRecords($rolesData,['id','state'],true) !== true)
            return ['code' => 30005];
        return ['code' => 0];
    }

    /**
     * Check a role whether belong to current user
     * @param int $childId
     * @return bool
     */
    public function checkChildOfRole($childId){
        if($_SESSION['rid'] == 1)
            return true;
        if(!$childId || !is_numeric($childId) || $_SESSION['rid'] == $childId)
            return false;
        $children = $this->findChildByParentId($_SESSION['rid']);
        if(!in_array($childId,$children))
            return false;
        return true;
    }

    public function findChildByParentId($parentId = 0,$roles = false){
        $result = [];
        if(!is_numeric($parentId))
            return $result;
        if($roles === false){
            $roles = $this->find([
                "conditions" => "state != 3",
                "columns" => "id,pid"
            ])->toArray();
        }
        if(empty($roles))
            return false;
        foreach($roles as $k => $v){
            if($v['pid'] == $parentId){
                unset($roles[$k]);
                $children = $this->findChildByParentId($v['id'],$roles);
                $result = array_merge($result,[$v['id']],$children);
            }
        }
        return $result;
    }

    public function getRoleAccess($rid){
        if($rid == 1)
            return ['code' => 0];
        if(!$this->findFirst([
            "conditions" => "id = ?1 AND state = 1",
            "bind" => [
                1 => $rid
            ]
        ]))
            return ['code' => 20002];
        $accessModel = new AdminAccess();
        return $accessModel->getAccess($rid);
    }

    public function saveRoleAccess($create = []){
        if($create['role_id'] == 1)
            return ['code' => 30004];
        $role = $this->findFirst([
            "conditions" => "id = ?1 AND state = 1",
            "bind" => [
                1 => $create['role_id']
            ]
        ]);
        if(!$role)
            return ['code' => 20002];
        if(!$this->checkChildOfRole($create['role_id']))
            return ['code' => 30004];
        $accessModel = new AdminAccess();
        return $accessModel->saveRoleAccess($create,$role->pid);
    }
}
