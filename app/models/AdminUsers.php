<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class AdminUsers extends BaseModel
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
     * @Column(column="username", type="string", length=30, nullable=false)
     */
    public $username;

    /**
     *
     * @var string
     * @Column(column="password", type="string", length=32, nullable=false)
     */
    public $password;

    /**
     *
     * @var integer
     * @Column(column="role_id", type="integer", length=11, nullable=false)
     */
    public $role_id;

    /**
     *
     * @var string
     * @Column(column="truename", type="string", length=30, nullable=false)
     */
    public $truename;

    /**
     *
     * @var string
     * @Column(column="phone", type="string", length=11, nullable=false)
     */
    public $phone;

    /**
     *
     * @var string
     * @Column(column="email", type="string", length=255, nullable=false)
     */
    public $email;

    /**
     *
     * @var integer
     * @Column(column="state", type="integer", length=1, nullable=false)
     */
    public $state;

    /**
     *
     * @var integer
     * @Column(column="login_at", type="integer", length=11, nullable=false)
     */
    public $login_at;

    /**
     *
     * @var string
     * @Column(column="login_ip", type="string", length=20, nullable=false)
     */
    public $login_ip;

    /**
     *
     * @var integer
     * @Column(column="login_num", type="integer", length=11, nullable=false)
     */
    public $login_num;

    /**
     *
     * @var integer
     * @Column(column="login_error", type="integer", length=1, nullable=false)
     */
    public $login_error;

    /**
     *
     * @var integer
     * @Column(column="modify_pwd", type="integer", length=1, nullable=false)
     */
    public $modify_pwd;

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
        $_SESSION['rid'] = 2;
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
        return [
            'code' => 0,
            'data' => $data,
            'total' => $total,
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


}
