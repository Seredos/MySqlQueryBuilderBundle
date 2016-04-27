<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 17.04.2016
 * Time: 00:34
 */

namespace database\MySqlQueryBuilderBundle\model;

/**
 * Class QueryModel
 * @package            lib\common\classes\database\queryBuilder\query
 */
class QueryModel {
    const QUERY_TYPE_SELECT = 'SELECT';
    const QUERY_TYPE_INSERT = 'INSERT';
    const QUERY_TYPE_UPDATE = 'UPDATE';
    const QUERY_TYPE_DELETE = 'DELETE';
    /**
     * @var string[]
     */
    private $select;

    /**
     * @var string[]
     */
    private $from;

    /**
     * @var array
     */
    private $join;

    /**
     * @var array
     */
    private $where;

    /**
     * @var string[]
     */
    private $groupBy;

    /**
     * @var array
     */
    private $having;

    /**
     * @var array
     */
    private $orderBy;

    /**
     * @var array
     */
    private $values;

    /**
     * @var string
     */
    private $type;

    public function __construct ($select = [],
                                 $from = [],
                                 $join = [],
                                 $where = [],
                                 $groupBy = [],
                                 $having = [],
                                 $orderBy = [],
                                 $values = [],
                                 $type = QueryModel::QUERY_TYPE_SELECT) {
        $this->select = $select;
        $this->from = $from;
        $this->join = $join;
        $this->where = $where;
        $this->groupBy = $groupBy;
        $this->having = $having;
        $this->orderBy = $orderBy;
        $this->values = $values;
        $this->type = $type;
    }

    /**
     * @param \string[] $select
     *
     * @return QueryModel
     */
    public function setSelect ($select) {
        $this->select = $select;

        return $this;
    }

    /**
     * @param \string[] $from
     *
     * @return QueryModel
     */
    public function setFrom ($from) {
        $this->from = $from;

        return $this;
    }

    /**
     * @param array $join
     *
     * @return QueryModel
     */
    public function setJoin ($join) {
        $this->join = $join;

        return $this;
    }

    /**
     * @param array $join
     *
     * @return QueryModel
     */
    public function addJoin ($join) {
        $this->join[] = $join;

        return $this;
    }

    /**
     * @param array $where
     *
     * @return QueryModel
     */
    public function setWhere ($where) {
        $this->where = $where;

        return $this;
    }

    /**
     * @param array $where
     *
     * @return QueryModel
     */
    public function addWhere ($where) {
        $this->where[] = $where;

        return $this;
    }

    /**
     * @param \string[] $groupBy
     *
     * @return QueryModel
     */
    public function setGroupBy ($groupBy) {
        $this->groupBy = $groupBy;

        return $this;
    }

    /**
     * @param array $having
     *
     * @return QueryModel
     */
    public function setHaving ($having) {
        $this->having = $having;

        return $this;
    }

    /**
     * @param array $having
     *
     * @return QueryModel
     */
    public function addHaving ($having) {
        $this->having[] = $having;

        return $this;
    }

    /**
     * @param array $orderBy
     *
     * @return QueryModel
     */
    public function setOrderBy ($orderBy) {
        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * @param array $orderBy
     *
     * @return QueryModel
     */
    public function addOrderBy ($orderBy) {
        $this->orderBy[] = $orderBy;

        return $this;
    }

    /**
     * @return \string[]
     */
    public function getSelect () {
        return $this->select;
    }

    /**
     * @return \string[]
     */
    public function getFrom () {
        return $this->from;
    }

    /**
     * @return array
     */
    public function getJoin () {
        return $this->join;
    }

    /**
     * @return array
     */
    public function getWhere () {
        return $this->where;
    }

    /**
     * @return \string[]
     */
    public function getGroupBy () {
        return $this->groupBy;
    }

    /**
     * @return array
     */
    public function getHaving () {
        return $this->having;
    }

    /**
     * @return array
     */
    public function getOrderBy () {
        return $this->orderBy;
    }

    /**
     * @return array
     */
    public function getValues () {
        return $this->values;
    }

    /**
     * @param array $values
     *
     * @return QueryModel
     */
    public function setValues ($values) {
        $this->values = $values;

        return $this;
    }

    /**
     * @return string
     */
    public function getType () {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return QueryModel
     */
    public function setType ($type) {
        $this->type = $type;

        return $this;
    }
}