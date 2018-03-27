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

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AdminAccess[]|AdminAccess|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AdminAccess|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
