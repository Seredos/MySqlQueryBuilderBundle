<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 15.04.2016
 * Time: 21:05
 */

namespace database\MySqlQueryBuilderBundle\builder;


use database\MySqlQueryBuilderBundle\model\QueryModel;

class DbQueryBuilder {
    /**
     * @var QueryModel
     */
    private $queryModel;

    public function __construct (QueryModel $queryModel) {
        $this->queryModel = $queryModel;
    }

    /**
     * @return QueryModel
     */
    public function getModel () {
        return $this->queryModel;
    }

    /**
     * @param null|string|string[] $select
     *
     * @return DbQueryBuilder
     */
    public function select ($select = null) {
        $this->queryModel->setType(QueryModel::QUERY_TYPE_SELECT);
        $this->queryModel->setSelect([]);
        $this->addSelect($select);

        return $this;
    }

    /**
     * @param null|string|string[] $select
     *
     * @return DbQueryBuilder
     */
    public function addSelect ($select = null) {
        if ($select != null) {
            $this->queryModel->setSelect(array_merge($this->queryModel->getSelect(), $this->valueToArray($select)));
        }

        return $this;
    }

    /**
     * @param string $from
     *
     * @return DbQueryBuilder
     */
    public function insert ($from) {
        $this->queryModel->setType(QueryModel::QUERY_TYPE_INSERT);
        $this->queryModel->setFrom(['table' => $from, 'alias' => '']);

        return $this;
    }

    /**
     * @param string $from
     *
     * @return DbQueryBuilder
     */
    public function update ($from) {
        $this->queryModel->setType(QueryModel::QUERY_TYPE_UPDATE);
        $this->queryModel->setFrom(['table' => $from, 'alias' => '']);

        return $this;
    }

    /**
     * @param string $from
     *
     * @return DbQueryBuilder
     */
    public function delete ($from) {
        $this->queryModel->setType(QueryModel::QUERY_TYPE_DELETE);
        $this->queryModel->setFrom(['table' => $from, 'alias' => '']);

        return $this;
    }

    /**
     * @param array $values
     *
     * @return $this
     */
    public function values ($values = []) {
        $this->queryModel->setValues($values);

        return $this;
    }

    /**
     * @param string $from
     * @param string $alias
     *
     * @return DbQueryBuilder
     */
    public function from ($from, $alias) {
        $this->queryModel->setFrom(['table' => $from, 'alias' => $alias]);

        return $this;
    }

    /**
     * @param string $join
     * @param string $alias
     * @param string $conditionType
     * @param string $condition
     *
     * @return DbQueryBuilder
     */
    public function leftJoin ($join, $alias, $conditionType, $condition) {
        return $this->join($join, $alias, $conditionType, $condition, 'LEFT');
    }

    /**
     * @param string $join
     * @param string $alias
     * @param string $conditionType
     * @param string $condition
     *
     * @return DbQueryBuilder
     */
    public function innerJoin ($join, $alias, $conditionType, $condition) {
        return $this->join($join, $alias, $conditionType, $condition);
    }

    /**
     * @param string $join
     * @param string $alias
     * @param string $conditionType
     * @param string $condition
     * @param string $joinType
     *
     * @return DbQueryBuilder
     */
    public function join ($join, $alias, $conditionType, $condition, $joinType = 'INNER') {
        $this->queryModel->addJoin(['joinType' => $joinType,
                                    'join' => $join,
                                    'alias' => $alias,
                                    'type' => $conditionType,
                                    'condition' => $condition,]);

        return $this;
    }

    /**
     * @param string $where
     *
     * @return DbQueryBuilder
     */
    public function where ($where) {
        $this->queryModel->setWhere([]);

        return $this->whereExpression($where);
    }

    /**
     * @param string $where
     *
     * @return DbQueryBuilder
     */
    public function andWhere ($where) {
        return $this->whereExpression($where);
    }

    /**
     * @param string $where
     *
     * @return DbQueryBuilder
     */
    public function orWhere ($where) {
        return $this->whereExpression($where, 'OR');
    }

    /**
     * @param string|string[] $groupBy
     *
     * @return DbQueryBuilder
     */
    public function groupBy ($groupBy) {
        $this->queryModel->setGroupBy([]);

        return $this->addGroupBy($groupBy);
    }

    /**
     * @param string|string[] $groupBy
     *
     * @return DbQueryBuilder
     */
    public function addGroupBy ($groupBy) {
        if ($groupBy != null) {
            $this->queryModel->setGroupBy(array_merge($this->queryModel->getGroupBy(), $this->valueToArray($groupBy)));
        }

        return $this;
    }

    /**
     * @param string $having
     *
     * @return DbQueryBuilder
     */
    public function having ($having) {
        $this->queryModel->setHaving([]);

        return $this->havingExpression($having);
    }

    /**
     * @param string $having
     *
     * @return DbQueryBuilder
     */
    public function andHaving ($having) {
        return $this->havingExpression($having);
    }

    /**
     * @param string $having
     *
     * @return DbQueryBuilder
     */
    public function orHaving ($having) {
        return $this->havingExpression($having, 'OR');
    }

    /**
     * @param string|string[] $sort
     * @param string          $order
     *
     * @return DbQueryBuilder
     */
    public function orderBy ($sort, $order = 'ASC') {
        $this->queryModel->addOrderBy(['sort' => $this->valueToArray($sort), 'order' => $order]);

        return $this;
    }

    private function whereExpression ($where, $whereType = 'AND') {
        $this->queryModel->addWhere(['whereType' => $whereType, 'where' => $where]);

        return $this;
    }

    private function havingExpression ($having, $havingType = 'AND') {
        $this->queryModel->addHaving(['havingType' => $havingType, 'having' => $having]);

        return $this;
    }

    private function valueToArray ($value) {
        if (!is_array($value)) {
            return [$value];
        }

        return $value;
    }
}