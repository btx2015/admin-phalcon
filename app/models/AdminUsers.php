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
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("btx_admin");
        $this->setSource("admin_users");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'admin_users';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AdminUsers[]|AdminUsers|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AdminUsers|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
