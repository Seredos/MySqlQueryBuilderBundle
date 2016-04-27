<?php
namespace database\MySqlQueryBuilderBundle\Tests\builder;

use database\MySqlQueryBuilderBundle\model\QueryModel;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 17.04.2016
 * Time: 04:39
 */
class DbQueryBuilderMockBuilder {
    /**
     * @var \PHPUnit_Framework_TestCase
     */
    private $testCase;

    /**
     * @var QueryModel|PHPUnit_Framework_MockObject_MockObject
     */
    private $queryModelMock;

    public function __construct (\PHPUnit_Framework_TestCase $testCase) {
        $this->testCase = $testCase;
        $this->queryModelMock = $this->testCase->getMockBuilder(QueryModel::class)
                                               ->getMock();
    }

    /**
     * @return QueryModel|PHPUnit_Framework_MockObject_MockObject
     */
    public function getQueryModelMock () {
        return $this->testCase->getMockBuilder(QueryModel::class)
                              ->getMock();
    }

    /**
     * @return QueryModel|PHPUnit_Framework_MockObject_MockObject
     */
    public function buildSelectMock () {
        //first select
        $this->queryModelMock->expects($this->testCase->at(0))
                             ->method('setType')
                             ->with(QueryModel::QUERY_TYPE_SELECT);
        $this->queryModelMock->expects($this->testCase->at(1))
                             ->method('setSelect')
                             ->with([]);
        $this->queryModelMock->expects($this->testCase->at(2))
                             ->method('getSelect')
                             ->will($this->testCase->returnValue([]));
        $this->queryModelMock->expects($this->testCase->at(3))
                             ->method('setSelect')
                             ->with(['u.id']);

        //second select replace the first select
        $this->queryModelMock->expects($this->testCase->at(4))
                             ->method('setType')
                             ->with(QueryModel::QUERY_TYPE_SELECT);
        $this->queryModelMock->expects($this->testCase->at(5))
                             ->method('setSelect')
                             ->with([]);
        $this->queryModelMock->expects($this->testCase->at(6))
                             ->method('getSelect')
                             ->will($this->testCase->returnValue([]));
        $this->queryModelMock->expects($this->testCase->at(7))
                             ->method('setSelect')
                             ->with(['u.name',
                                     'COUNT(g.group) AS counter']);

        //add a select column to the other columns
        $this->queryModelMock->expects($this->testCase->at(8))
                             ->method('getSelect')
                             ->will($this->testCase->returnValue(['u.name', 'COUNT(g.group) AS counter']));
        $this->queryModelMock->expects($this->testCase->at(9))
                             ->method('setSelect')
                             ->with(['u.name',
                                     'COUNT(g.group) AS counter',
                                     'u.password']);

        //add a select columns to the other columns
        $this->queryModelMock->expects($this->testCase->at(10))
                             ->method('getSelect')
                             ->will($this->testCase->returnValue(['u.name',
                                                                  'COUNT(g.group) AS counter',
                                                                  'u.password']));
        $this->queryModelMock->expects($this->testCase->at(11))
                             ->method('setSelect')
                             ->with(['u.name',
                                     'COUNT(g.group) AS counter',
                                     'u.password',
                                     'u.firstname',
                                     'u.sex']);

        //remove all select columns
        $this->queryModelMock->expects($this->testCase->at(12))
                             ->method('setType')
                             ->with(QueryModel::QUERY_TYPE_SELECT);
        $this->queryModelMock->expects($this->testCase->at(13))
                             ->method('setSelect')
                             ->with([]);

        return $this->queryModelMock;
    }

    /**
     * @return QueryModel|PHPUnit_Framework_MockObject_MockObject
     */
    public function buildInsertMock () {
        //set insert table
        $this->queryModelMock->expects($this->testCase->at(0))
                             ->method('setType')
                             ->with(QueryModel::QUERY_TYPE_INSERT);
        $this->queryModelMock->expects($this->testCase->at(1))
                             ->method('setFrom')
                             ->with(['table' => 'example1',
                                     'alias' => '']);

        //overwrite insert table
        $this->queryModelMock->expects($this->testCase->at(2))
                             ->method('setType')
                             ->with(QueryModel::QUERY_TYPE_INSERT);
        $this->queryModelMock->expects($this->testCase->at(3))
                             ->method('setFrom')
                             ->with(['table' => 'example2',
                                     'alias' => '']);

        return $this->queryModelMock;
    }

    /**
     * @return QueryModel|PHPUnit_Framework_MockObject_MockObject
     */
    public function buildUpdateMock () {
        //set update table
        $this->queryModelMock->expects($this->testCase->at(0))
                             ->method('setType')
                             ->with(QueryModel::QUERY_TYPE_UPDATE);
        $this->queryModelMock->expects($this->testCase->at(1))
                             ->method('setFrom')
                             ->with(['table' => 'example1',
                                     'alias' => '']);

        //overwrite update table
        $this->queryModelMock->expects($this->testCase->at(2))
                             ->method('setType')
                             ->with(QueryModel::QUERY_TYPE_UPDATE);
        $this->queryModelMock->expects($this->testCase->at(3))
                             ->method('setFrom')
                             ->with(['table' => 'example2',
                                     'alias' => '']);

        return $this->queryModelMock;
    }

    /**
     * @return QueryModel|PHPUnit_Framework_MockObject_MockObject
     */
    public function buildDeleteMock () {
        //set delete table
        $this->queryModelMock->expects($this->testCase->at(0))
                             ->method('setType')
                             ->with(QueryModel::QUERY_TYPE_DELETE);
        $this->queryModelMock->expects($this->testCase->at(1))
                             ->method('setFrom')
                             ->with(['table' => 'example1',
                                     'alias' => '']);

        //overwrite delete table
        $this->queryModelMock->expects($this->testCase->at(2))
                             ->method('setType')
                             ->with(QueryModel::QUERY_TYPE_DELETE);
        $this->queryModelMock->expects($this->testCase->at(3))
                             ->method('setFrom')
                             ->with(['table' => 'example2',
                                     'alias' => '']);

        return $this->queryModelMock;
    }

    /**
     * @return QueryModel|PHPUnit_Framework_MockObject_MockObject
     */
    public function buildValuesMock () {
        $this->queryModelMock->expects($this->testCase->at(0))
                             ->method('setValues')
                             ->with(['column2' => 'value2',
                                     'column1' => 'value1']);
        $this->queryModelMock->expects($this->testCase->at(1))
                             ->method('setValues')
                             ->with(['column3' => 'value3',
                                     'column4' => 'value4']);

        return $this->queryModelMock;
    }

    /**
     * @return QueryModel|PHPUnit_Framework_MockObject_MockObject
     */
    public function buildFromMock () {
        //set from
        $this->queryModelMock->expects($this->testCase->at(0))
                             ->method('setFrom')
                             ->with(['table' => 'user',
                                     'alias' => 'u']);
        //replace from
        $this->queryModelMock->expects($this->testCase->at(1))
                             ->method('setFrom')
                             ->with(['table' => 'person',
                                     'alias' => 'p']);

        return $this->queryModelMock;
    }

    /**
     * @return QueryModel|PHPUnit_Framework_MockObject_MockObject
     */
    public function buildInnerJoinMock () {
        //add a first innerJoin with the join method
        $this->queryModelMock->expects($this->testCase->at(0))
                             ->method('addJoin')
                             ->with(['joinType' => 'INNER',
                                     'join' => 'group',
                                     'alias' => 'g',
                                     'type' => 'WITH',
                                     'condition' => 'g.id = p.group_id',]);
        //add a second innerJoin
        $this->queryModelMock->expects($this->testCase->at(1))
                             ->method('addJoin')
                             ->with(['joinType' => 'INNER',
                                     'join' => 'function',
                                     'alias' => 'f',
                                     'type' => 'WITH',
                                     'condition' => 'f.id = g.function_id',]);

        return $this->queryModelMock;
    }

    /**
     * @return QueryModel|PHPUnit_Framework_MockObject_MockObject
     */
    public function buildLeftJoinMock () {
        //add a first leftJoin with the join method
        $this->queryModelMock->expects($this->testCase->at(0))
                             ->method('addJoin')
                             ->with(['joinType' => 'LEFT',
                                     'join' => 'group',
                                     'alias' => 'g',
                                     'type' => 'WITH',
                                     'condition' => 'g.id = p.group_id',]);
        //add a second leftJoin
        $this->queryModelMock->expects($this->testCase->at(1))
                             ->method('addJoin')
                             ->with(['joinType' => 'LEFT',
                                     'join' => 'function',
                                     'alias' => 'f',
                                     'type' => 'WITH',
                                     'condition' => 'f.id = g.function_id',]);

        return $this->queryModelMock;
    }

    /**
     * @return QueryModel|PHPUnit_Framework_MockObject_MockObject
     */
    public function buildWhereMock () {
        //set the first where expression
        $this->queryModelMock->expects($this->testCase->at(0))
                             ->method('setWhere')
                             ->with([]);
        $this->queryModelMock->expects($this->testCase->at(1))
                             ->method('addWhere')
                             ->with(['whereType' => 'AND',
                                     'where' => 'u.id = :user']);
        //add an and expression
        $this->queryModelMock->expects($this->testCase->at(2))
                             ->method('addWhere')
                             ->with(['whereType' => 'AND',
                                     'where' => 'u.name IN(:names)']);
        //add an or expression
        $this->queryModelMock->expects($this->testCase->at(3))
                             ->method('addWhere')
                             ->with(['whereType' => 'OR',
                                     'where' => 'u.group IN(:groups)']);
        //overwrite the expressions
        $this->queryModelMock->expects($this->testCase->at(4))
                             ->method('setWhere')
                             ->with([]);
        $this->queryModelMock->expects($this->testCase->at(5))
                             ->method('addWhere')
                             ->with(['whereType' => 'AND',
                                     'where' => 'u.password = :password']);

        return $this->queryModelMock;
    }

    /**
     * @return QueryModel|PHPUnit_Framework_MockObject_MockObject
     */
    public function buildGroupByMock () {
        //set first groupBy column
        $this->queryModelMock->expects($this->testCase->at(0))
                             ->method('setGroupBy')
                             ->with([]);
        $this->queryModelMock->expects($this->testCase->at(1))
                             ->method('getGroupBy')
                             ->will($this->testCase->returnValue([]));
        $this->queryModelMock->expects($this->testCase->at(2))
                             ->method('setGroupBy')
                             ->with(['u.id']);

        //overwrite with to columns
        $this->queryModelMock->expects($this->testCase->at(3))
                             ->method('setGroupBy')
                             ->with([]);
        $this->queryModelMock->expects($this->testCase->at(4))
                             ->method('getGroupBy')
                             ->will($this->testCase->returnValue([]));
        $this->queryModelMock->expects($this->testCase->at(5))
                             ->method('setGroupBy')
                             ->with(['u.firstname', 'u.name']);

        //add one column
        $this->queryModelMock->expects($this->testCase->at(6))
                             ->method('getGroupBy')
                             ->will($this->testCase->returnValue(['u.firstname', 'u.name']));
        $this->queryModelMock->expects($this->testCase->at(7))
                             ->method('setGroupBy')
                             ->with(['u.firstname',
                                     'u.name',
                                     'u.group']);

        //add two columns
        $this->queryModelMock->expects($this->testCase->at(8))
                             ->method('getGroupBy')
                             ->will($this->testCase->returnValue(['u.firstname', 'u.name']));
        $this->queryModelMock->expects($this->testCase->at(9))
                             ->method('setGroupBy')
                             ->with(['u.firstname',
                                     'u.name',
                                     'u.password',
                                     'u.lastname']);

        return $this->queryModelMock;
    }

    /**
     * @return QueryModel|PHPUnit_Framework_MockObject_MockObject
     */
    public function buildHavingMock () {
        //set the first having expression
        $this->queryModelMock->expects($this->testCase->at(0))
                             ->method('setHaving')
                             ->with([]);
        $this->queryModelMock->expects($this->testCase->at(1))
                             ->method('addHaving')
                             ->with(['havingType' => 'AND',
                                     'having' => 'name = :name']);

        //add an and having expression
        $this->queryModelMock->expects($this->testCase->at(2))
                             ->method('addHaving')
                             ->with(['havingType' => 'AND',
                                     'having' => 'password = :password']);

        //add an or having expression
        $this->queryModelMock->expects($this->testCase->at(3))
                             ->method('addHaving')
                             ->with(['havingType' => 'OR',
                                     'having' => 'id IN(:ids)']);

        return $this->queryModelMock;
    }

    /**
     * @return QueryModel|PHPUnit_Framework_MockObject_MockObject
     */
    public function buildOrderByMock () {
        //set the first ASC order
        $this->queryModelMock->expects($this->testCase->at(0))
                             ->method('addOrderBy')
                             ->with(['sort' => ['u.id'],
                                     'order' => 'ASC']);

        //add an order array
        $this->queryModelMock->expects($this->testCase->at(1))
                             ->method('addOrderBy')
                             ->with(['sort' => ['u.group',
                                                'u.name'],
                                     'order' => 'ASC']);

        //add an DESC order
        $this->queryModelMock->expects($this->testCase->at(2))
                             ->method('addOrderBy')
                             ->with(['sort' => ['u.password'],
                                     'order' => 'DESC']);

        return $this->queryModelMock;
    }
}