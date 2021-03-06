<?php

class AdminMenu extends BaseModel
{

    /**
     *
     * @var integer
     * @Primary
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
     * @Column(column="node_id", type="integer", length=11, nullable=false)
     */
    public $node_id;

    /**
     *
     * @var integer
     * @Column(column="state", type="integer", length=1, nullable=false)
     */
    public $state;

    /**
     *
     * @var string
     * @Column(column="icon", type="string", length=100, nullable=false)
     */
    public $icon;

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
        $this->setSource("admin_menu");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'admin_menu';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AdminMenu[]|AdminMenu|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AdminMenu|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
