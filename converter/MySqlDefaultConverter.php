<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 16.04.2016
 * Time: 03:13
 */

namespace database\MySqlQueryBuilderBundle\converter;

use database\MySqlQueryBuilderBundle\model\QueryModel;

/**
 * Class QueryValidator
 *
 * this validator check the content of the QueryModel
 * some values will be prepared for the database
 *
 * @package lib\common\classes\database\queryBuilder
 */
class MySqlDefaultConverter {
    const VALID_JOIN_TYPES           = ['INNER', 'LEFT', 'RIGHT', 'LEFT OUTER', 'RIGHT OUTER'];
    const VALID_JOIN_CONDITION_TYPES = ['WITH' => 'ON', 'ON' => 'ON', 'USING' => 'USING'];
    const VALID_EXPRESSION_TYPES     = ['AND', 'OR'];
    const VALID_ORDER_DIRECTIONS     = ['ASC', 'DESC'];

    /**
     * @param QueryModel $model
     *
     * @throws QueryConversionException
     */
    public function validate (QueryModel $model) {
        switch ($model->getType()) {
            case QueryModel::QUERY_TYPE_SELECT:
                $this->validateSelect($model);
                $this->validateFrom($model);
                $this->validateJoin($model);
                $this->validateWhere($model);
                $this->validateGroupBy($model);
                $this->validateHaving($model);
                $this->validateOrderBy($model);
                break;
            case QueryModel::QUERY_TYPE_INSERT:
                $this->validateFrom($model);
                $this->validateValues($model);
                break;
            case QueryModel::QUERY_TYPE_UPDATE:
                $this->validateFrom($model);
                $this->validateValues($model);
                $this->validateWhere($model);
                break;
            case QueryModel::QUERY_TYPE_DELETE:
                $this->validateFrom($model);
                $this->validateWhere($model);
                break;
            default:
                throw new QueryConversionException('invalid query type');
                break;
        }
    }

    /**
     * check if the getValues contains the following array type ['key1'=>'string1','key2'=>'string2']
     *
     * @param QueryModel $model
     *
     * @throws QueryConversionException
     */
    public function validateValues (QueryModel $model) {
        $values = $model->getValues();
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $this->noStringException($key, 'invalid values key. string accepted '.gettype($key).' given');
                $this->noStringException($value, 'invalid values key value. string accepted '.gettype($value).' given');
            }

            return;
        }

        throw new QueryConversionException('invalid values arguments. array accepted '.gettype($values).' given');
    }

    /**
     * check if the getSelect contains the following array type ['string1','string2']
     *
     * @param QueryModel $model
     *
     * @throws QueryConversionException
     */
    protected function validateSelect (QueryModel $model) {
        $selects = $model->getSelect();
        if (is_array($selects)) {
            foreach ($selects as $select) {
                $this->noStringException($select,
                                         'invalid select column arguments. string accepted '.gettype($select).' given');
            }

            return;
        }
        throw new QueryConversionException('invalid select arguments. array accepted '.gettype($selects).' given');
    }

    /**
     * check if the getSelect contains the following array type ['table' => 'alias']
     *
     * @param QueryModel $model
     *
     * @throws QueryConversionException
     */
    protected function validateFrom (QueryModel $model) {
        $from = $model->getFrom();
        if (is_array($from)) {
            $this->notSetOrNotStringException($from, 'table', 'invalid from table argument. string accepted');
            $this->notSetOrNotStringException($from, 'alias', 'invalid from table alias argument. string accepted');

            return;
        }
        throw new QueryConversionException('invalid from arguments or invalid array content. array accepted '.
                                           gettype($from).
                                           ' given');
    }

    /**
     * check if the getJoin contains the following array type
     * [['joinType'=>'INNER','join'=>'table','alias'=>'alias','conditionType'=>'WITH','condition'=>'string']]
     *
     * the joinType will setted to uppercase, the conditionType 'with' will be replaced with 'on'
     * the joinType and the conditionType will be checked by valid constants
     *
     * @param QueryModel $model
     *
     * @throws QueryConversionException
     */
    protected function validateJoin (QueryModel $model) {
        $joins = $model->getJoin();
        if (is_array($joins)) {
            foreach ($joins as &$join) {
                if (!is_array($join)) {
                    throw new QueryConversionException('invalid join arguments. array accepted '.gettype($join).
                                                       ' given');
                }
                $join = $this->validateJoinType($join);
                $join = $this->validateJoinConditionType($join);
                $this->notSetOrNotStringException($join, 'join', 'invalid join table argument');
                $this->notSetOrNotStringException($join, 'alias', 'invalid join table alias argument');
                $this->notSetOrNotStringException($join, 'condition', 'invalid join condition argument');
            }

            $model->setJoin($joins);

            return;
        }
        throw new QueryConversionException('invalid join arguments');
    }

    /**
     * check if the getWhere contains the following array type [['whereType'=>'AND','where'=>'string']]
     * the whereType will be setted to uppercase and checked with the valid constants
     *
     * @param QueryModel $model
     *
     * @throws QueryConversionException
     */
    protected function validateWhere (QueryModel $model) {
        $wheres = $model->getWhere();
        if (is_array($wheres)) {
            foreach ($wheres as &$where) {
                if (!is_array($where)) {
                    throw new QueryConversionException('invalid where arguments. array accepted '.gettype($where).
                                                       ' given');
                }
                $where = $this->validateWhereType($where);
                $this->notSetOrNotStringException($where, 'where', 'invalid where argument');
            }

            $model->setWhere($wheres);

            return;
        }

        throw new QueryConversionException('invalid where arguments. array accepted '.gettype($wheres).' given');
    }

    /**
     * check if the getGroupBy contains the following array type ['string','string']
     *
     * @param QueryModel $model
     *
     * @throws QueryConversionException
     */
    protected function validateGroupBy (QueryModel $model) {
        $groupBys = $model->getGroupBy();
        if (is_array($groupBys)) {
            foreach ($groupBys as $groupBy) {
                $this->noStringException($groupBy,
                                         'invalid groupBy column arguments. string accepted '.gettype($groupBy).
                                         ' given');
            }

            return;
        }
        throw new QueryConversionException('invalid groupBy arguments. array accepted '.gettype($groupBys).' given');
    }

    /**
     * check if the getHaving contains the following array type [['havingType' => 'and','having' => 'string']]
     * the havingType will be setted to upper case and checked with the valid constants
     *
     * @param QueryModel $model
     *
     * @throws QueryConversionException
     */
    protected function validateHaving (QueryModel $model) {
        $havings = $model->getHaving();
        if (is_array($havings)) {
            foreach ($havings as &$having) {
                if (!is_array($having)) {
                    throw new QueryConversionException('invalid having arguments. array accepted '.gettype($having).
                                                       ' given');
                }
                $having = $this->validateHavingType($having);
                $this->notSetOrNotStringException($having, 'having', 'invalid having argument');
            }

            $model->setHaving($havings);

            return;
        }

        throw new QueryConversionException('invalid having arguments. array accepted '.gettype($havings).' given');
    }

    /**
     * check if the getOrderBy contains the following array type [['sort'=>['string'],'order'=>'ASC']]
     * the order will be setted to upper case and checked with the valid constants
     *
     * @param QueryModel $model
     *
     * @throws QueryConversionException
     */
    protected function validateOrderBy (QueryModel $model) {
        $orderBys = $model->getOrderBy();
        if (is_array($orderBys)) {
            foreach ($orderBys as &$orderBy) {
                if (!is_array($orderBy)) {
                    throw new QueryConversionException('invalid orderBy. array accepted '.gettype($orderBy).' given');
                }

                if (!isset($orderBy['sort']) || !is_array($orderBy['sort'])) {
                    throw new QueryConversionException('invalid orderBy column arguments.');
                }

                $this->validateOrderBySorts($orderBy['sort']);
                $orderBy = $this->validateOrderByDirection($orderBy);
            }
            $model->setOrderBy($orderBys);

            return;
        }

        throw new QueryConversionException('invalid orderBy arguments. array accepted '.gettype($orderBys).' given');
    }

    private function validateJoinConditionType ($join) {
        $joinMessage = 'invalid join table condition type. accepted values are '.
                       implode(', ', array_keys(self::VALID_JOIN_CONDITION_TYPES));
        $this->notSetOrNotStringException($join, 'type', $joinMessage);

        $type = strtoupper($join['type']);
        if (!in_array($type, array_keys(self::VALID_JOIN_CONDITION_TYPES))) {
            throw new QueryConversionException($joinMessage.' \''.$type.'\' given');
        }

        $join['type'] = self::VALID_JOIN_CONDITION_TYPES[$type];

        return $join;
    }

    private function validateJoinType ($join) {
        $joinMessage = 'invalid join type valid join types are '.implode(', ', self::VALID_JOIN_TYPES);
        $this->notSetOrNotStringException($join, 'joinType', $joinMessage);

        $join['joinType'] = strtoupper($join['joinType']);
        if (!in_array($join['joinType'], self::VALID_JOIN_TYPES)) {
            throw new QueryConversionException($joinMessage.' \''.$join['joinType'].'\' given');
        }

        return $join;
    }

    private function validateWhereType ($where) {
        $whereMessage = 'invalid where type. accepted values are '.implode(', ', self::VALID_EXPRESSION_TYPES);
        $this->notSetOrNotStringException($where, 'whereType', $whereMessage);

        $where['whereType'] = strtoupper($where['whereType']);
        if (!in_array($where['whereType'], self::VALID_EXPRESSION_TYPES)) {
            throw new QueryConversionException($whereMessage.' \''.$where['whereType'].'\' given');
        }

        return $where;
    }

    private function validateHavingType ($having) {
        $havingMessage = 'invalid having type. accepted values are '.implode(', ', self::VALID_EXPRESSION_TYPES);
        $this->notSetOrNotStringException($having, 'havingType', $havingMessage);

        $having['havingType'] = strtoupper($having['havingType']);
        if (!in_array($having['havingType'], self::VALID_EXPRESSION_TYPES)) {
            throw new QueryConversionException($havingMessage.' \''.$having['havingType'].'\' given');
        }

        return $having;
    }

    private function validateOrderBySorts ($sorts) {
        foreach ($sorts as $sort) {
            $this->noStringException($sort,
                                     'invalid orderBy column arguments. string accepted '.gettype($sort).' given');
        }
    }

    private function validateOrderByDirection ($orderBy) {
        $orderByMessage = 'invalid orderBy direction. accepted values are '.implode(', ', self::VALID_ORDER_DIRECTIONS);
        $this->notSetOrNotStringException($orderBy, 'order', $orderByMessage);

        $orderBy['order'] = strtoupper($orderBy['order']);

        if (!in_array($orderBy['order'], self::VALID_ORDER_DIRECTIONS)) {
            throw new QueryConversionException($orderByMessage.' \''.$orderBy['order'].'\' given');
        }

        return $orderBy;
    }

    private function noStringException ($value, $message) {
        if (!is_string($value)) {
            throw new QueryConversionException($message);
        }
    }

    private function notSetOrNotStringException ($array, $key, $message) {
        if (!isset($array[$key]) || !is_string($array[$key])) {
            throw new QueryConversionException($message);
        }
    }
}