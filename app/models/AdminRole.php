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

    public function getRoleRecords($conditions = []){
        $_SESSION['rid'] = 2;
        if($_SESSION['rid'] != 1){
            $children = $this->findChildByParentId($_SESSION['rid']);
            if(empty($children))
                return ['code'=>0,'data'=>[],'total'=>0];
            $conditions['id'] = $children;
            if(isset($conditions['pid']) && !in_array($conditions['pid'],$children))
                return ['code'=>0,'data'=>[],'total'=>0];
        }
        list($data,$total) = $this->getRecords($conditions,
            ['id','name','pid','state','created_at']);
        return [
            'code' => 0,
            'data' => $data,
            'total' => $total,
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

    /**
     * Check a role whether belong to current user
     * @param int $childId
     * @return bool
     */
    public function checkChildOfRole($childId){
        if(!$childId || !is_numeric($childId))
            return false;
        if($_SESSION['rid'] == 1 || $_SESSION['rid'] == $childId)
            return true;
        $children = $this->findChildByParentId($_SESSION['rid']);
        if(!in_array($childId,$children))
            return false;
        return true;
    }

    public function findChildByParentId($parentId = 0){
        $result = [];
        if(!is_numeric($parentId))
            return $result;
        $roles = $this->find([
            "conditions" => "state != 3",
            "columns" => "id,pid"
        ])->toArray();
        if(empty($roles))
            return $result;
        foreach($roles as $k => $v){
            if($v['pid'] == $parentId){
                unset($roles[$k]);
                $children = $this->findChildByParentId($roles,$v['id']);
                $result = array_merge($result,[$v['id']],$children);
            }
        }
        return $result;
    }
}
