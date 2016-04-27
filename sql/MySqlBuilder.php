<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 15.04.2016
 * Time: 21:04
 */

namespace database\MySqlQueryBuilderBundle\sql;

use database\MySqlQueryBuilderBundle\model\QueryModel;
use database\MySqlQueryBuilderBundle\converter\QueryConversionException;

class MySqlBuilder implements SqlBuilderInterface
{
	const JOIN_EXPRESSION_ON = 'ON';
	const JOIN_EXPRESSION_USING = 'USING';

	/**
	 * @var string
	 */
	private $sql;

	/**
	 * @param QueryModel $model
	 *
	 * @throws QueryConversionException
	 */
	public function buildQuery(QueryModel $model) {
		$this->sql = '';
		switch ($model->getType()) {
			case QueryModel::QUERY_TYPE_SELECT:
				$select = $this->buildSelect($model->getSelect());
				$from = $this->buildFrom($model->getFrom());
				$join = $this->buildJoin($model->getJoin());
				$where = $this->buildWhere($model->getWhere());
				$groupBy = $this->buildGroupBy($model->getGroupBy());
				$having = $this->buildHaving($model->getHaving());
				$orderBy = $this->buildOrderBy($model->getOrderBy());
				$this->sql = $select . $from . $join . $where . $groupBy . $having . $orderBy;
				break;
			case QueryModel::QUERY_TYPE_INSERT:
				$insert = $this->buildInsert($model->getFrom());
				$values = $this->buildInsertValues($model->getValues());
				$this->sql = $insert . $values;
				break;
			case QueryModel::QUERY_TYPE_UPDATE:
				$from = $this->buildUpdate($model->getFrom());
				$values = $this->buildUpdateValues($model->getValues());
				$where = $this->buildWhere($model->getWhere());
				$this->sql = $from . $values . $where;
				break;
			case QueryModel::QUERY_TYPE_DELETE:
				$from = $this->buildDelete($model->getFrom());
				$where = $this->buildWhere($model->getWhere());
				$this->sql = $from . $where;
				break;
		}
	}

	public function getSql() {
		return $this->sql;
	}

	protected function buildUpdate($from) {
		$sql = 'UPDATE ' . $from['table'] . ' ';

		return $sql;
	}

	protected function buildInsert($from) {
		$sql = 'INSERT INTO ' . $from['table'] . ' ';

		return $sql;
	}

	protected function buildDelete($from) {
		$sql = 'DELETE FROM ' . $from['table'] . ' ';

		return $sql;
	}

	protected function buildInsertValues($values) {
		$sql = '(' . implode(',', array_keys($values)) . ') VALUES(' . implode(',', $values) . ') ';

		return $sql;
	}

	protected function buildUpdateValues($values) {
		$sets = [];
		foreach ($values as $key => $value) {
			$sets[] = $key . ' = ' . $value;
		}

		if (count($sets) > 0) {
			return 'SET ' . implode(',', $sets) . ' ';
		}

		return '';
	}

	protected function buildSelect($select) {
		$sql = 'SELECT * ';

		if (count($select) > 0) {
			$sql = 'SELECT ' . implode(',', $select) . ' ';
		}

		return $sql;
	}

	protected function buildFrom($from) {
		$sql = 'FROM ' . $from['table'] . ' ' . $from['alias'] . ' ';

		return $sql;
	}

	protected function buildJoin($joins) {
		$sql = '';
		foreach ($joins as $join) {
			$sql .= $join['joinType'] . ' JOIN ' . $join['join'] . ' ' . $join['alias'] . ' ' . $this->joinExpression($join);
		}

		return $sql;
	}

	protected function buildWhere($whereExpressions) {
		$sql = '';
		if (count($whereExpressions) > 0) {
			$sql = 'WHERE ';
		}
		foreach ($whereExpressions as $key => $where) {
			if ($key > 0) {
				$sql .= $where['whereType'] . ' ';
			}
			$sql .= $where['where'] . ' ';
		}

		return $sql;
	}

	protected function buildGroupBy($groupBy) {
		$sql = '';
		if (count($groupBy) > 0) {
			$sql = 'GROUP BY ' . implode(',', $groupBy) . ' ';
		}

		return $sql;
	}

	protected function buildHaving($havingExpressions) {
		$sql = '';
		foreach ($havingExpressions as $key => $having) {
			if ($key > 0) {
				$sql .= $having['havingType'] . ' ';
			}
			$sql .= 'HAVING ' . $having['having'] . ' ';
		}

		return $sql;
	}

	protected function buildOrderBy($orderByExpressions) {
		$sql = '';

		if (count($orderByExpressions) > 0) {
			$sql = 'ORDER BY ';
			foreach ($orderByExpressions as $orderBy) {
				$sql .= implode(',', $orderBy['sort']) . ' ' . $orderBy['order'] . ' ';
			}
		}

		return $sql;
	}

	private function joinExpression($join) {
		$sql = '';
		if ($join['type'] == self::JOIN_EXPRESSION_ON) {
			$sql = 'ON ' . $join['condition'] . ' ';
		} else if ($join['type'] == self::JOIN_EXPRESSION_USING) {
			$sql = 'USING(' . $join['condition'] . ') ';
		}

		return $sql;
	}
}