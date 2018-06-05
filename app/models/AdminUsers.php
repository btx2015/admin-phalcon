<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Security;

class AdminUsers extends BaseModel
{
    /**
     * Validations and business logic
     *
     * @return boolean
     */
//    public function validation()
//    {
//        $validator = new Validation();
//
//        $validator->add(
//            'email',
//            new EmailValidator(
//                [
//                    'model'   => $this,
//                    'message' => 'Please enter a correct email address',
//                ]
//            )
//        );
//
//        return $this->validate($validator);
//    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("btx_admin");
        $this->setSource("admin_users");
    }

    /**
     * @param array $create
     * @return array
     */
    public function createUserRecord($create = []){
        if(empty($create))
            return ['code'=>20000];//Empty array
        if($this->findFirst([
            'conditions' => 'username = ?1 OR phone = ?2 AND state != 3',
            'bind' => [
                1 => $create['username'],
                2 => $create['phone']
            ]
        ]))
            return ['code'=>20001];//The username is already exists
        $roleModel = new AdminRole();
        if(!$roleModel->checkChildOfRole($create['rid']))
            return ['code'=>30004];
        if(!$this->create($create)){
            return ['code'=>20000];//Create fail
        }
        return ['code'=>0];
    }

    /**
     * @param array $conditions
     * @return array
     */
    public function getUserRecords($conditions = []){
        $conditions['id!='] = 1;
        $roleModel = new AdminRole();
        if($_SESSION['rid'] != 1){
            $children = $roleModel->findChildByParentId($_SESSION['rid']);
            if(empty($children))
                return ['code'=>0,'data'=>[],'total'=>0];
            if(isset($conditions['rid'])){
                if(!in_array($conditions['role_id'],$children))
                    return ['code'=>0,'data'=>[],'total'=>0];
            }else{
                $conditions['role_id'] = $children;
            }
        }
        list($data,$total) = $this->getRecords($conditions,
            ['id','username','phone','email','state','true_name','role_id','created_at']);
        if($data){
            $ridData = array_unique(array_column($data,'role_id'));
            $roleModel = new AdminRole();
            $roles = $roleModel->getRecordsByCondition(['id'=>$ridData],['id','name'],-1);
            $data = $this->translateRecords($data,[
                'role_id' => ['data' => $roles,'source' => 'id','target' => 'name'],
                'state' => 'state',
                'created_at' => 'time']);
        }
        return [
            'code' => 0,
            'data' => $data,
            'count' => $total,
        ];
    }


    public function updateUserRecord($update = []){
        $user = $this->findFirst([
            'conditions' => 'id = ?1 AND state != 3',
            'bind' => [
                1 => $update['id']
            ]
        ]);
        if(!$user)
            return ['code'=>20002];//User is not exists.
        $roleModel = new AdminRole();
        if(!$roleModel->checkChildOfRole($user->rid))
            return ['code'=>30004];
        if(isset($update['rid']) && !$roleModel->checkChildOfRole($update['rid']))
            return ['code'=>30004];
        if($user->update($update) !== true)
            return ['code'=>20003];//Failed
        return ['code'=>0];
    }

    public function updateStateForUsers($users = [] ,$state = 1){
        $where = ['id' => $users];
        if($state == 1){
            $where['state'] = 2;
        }else if($state == 2){
            $where['state'] = 1;
        }else{
            $where['state!='] = 3;
        }
        $usersData = $this->getRecordsByCondition($where,['id','role_id'],-1);
        if(count($users) != count($usersData))
            return ['code' => 20002];
        $roleModel = new AdminRole();
        $roleChildren = $roleModel->findChildByParentId($_SESSION['rid']);
        foreach($usersData as &$v){
            if(!in_array($v['role_id'],$roleChildren))
                return ['code' => 20004];
            $v['state'] = $state;
            unset($v['role_id']);
            unset($v);
        }
        if($this->saveRecords($usersData,['id','state'],true) !== true)
            return ['code' => 20005];
        return ['code' => 0];
    }

    public function userLogin($login){
        $userModel = new AdminUsers();
        $user = $userModel->findFirst([
            "conditions" => "username = ?1 AND state = 1",
            "bind" => [
                1 => $login['username']
            ]
        ]);
        if(!$user)
            return ['code' => 20002];
        $security = new Security();
        if(!$security->checkHash($login['password'],$user->password)){
            $user->login_err_num ++;//错误计次
            if($user->login_err_num > 3){
                $user->state = 2;//禁用用户
                Log::writeLog('danger','登录密码输入错误次数过多，'.$login['username'].'管理员被禁用。登录IP:'.$login['ip'],0);
            }
            $user->update();
            return ['code' => 10000];
        }
        $_SESSION['uid'] = $user->id;
        $accessModel = new AdminAccess();
        $_SESSION['access'] = $accessModel->getNode($user->role_id,'name');
    }
}
