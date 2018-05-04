<?php

class AdminNode extends BaseModel
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
     * @var string
     * @Column(column="tittle", type="string", length=30, nullable=false)
     */
    public $tittle;

    /**
     *
     * @var integer
     * @Column(column="pid", type="integer", length=11, nullable=false)
     */
    public $pid;

    /**
     *
     * @var integer
     * @Column(column="level", type="integer", length=1, nullable=false)
     */
    public $level;

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

    public function getNode(){
        return $this->find()->toArray();
    }

    public function getFormatNode(){
        $nodes = $this->find([
            "columns" => 'id,tittle,pid'
        ])->toArray();
        return $this->findChildNode($nodes,0);
    }

    private function findChildNode($nodes,$pid = 0){
        $nodesData = [];
        foreach($nodes as $k => $v){
            if($v['pid'] == $pid){
                unset($nodes[$k]);
                $child = $this->findChildNode($nodes,$v['id']);
                if(!empty($child))
                    $v['child'] = $child;
                unset($v['pid']);
                $nodesData[] = $v;
            }
        }
        return $nodesData;
    }
}
