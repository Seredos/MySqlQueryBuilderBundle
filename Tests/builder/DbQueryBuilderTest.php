<?php
use database\MySqlQueryBuilderBundle\builder\DbQueryBuilder;
use database\MySqlQueryBuilderBundle\Tests\builder\DbQueryBuilderMockBuilder;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 15.04.2016
 * Time: 22:06
 */
class DbQueryBuilderTest extends PHPUnit_Framework_TestCase {
    /**
     * @var DbQueryBuilderMockBuilder
     */
    private $mockBuilder;

    public function setUp () {
        $this->mockBuilder = new DbQueryBuilderMockBuilder($this);
    }

    /**
     * @test
     */
    public function select_and_addSelect () {
        $queryBuilder = new DbQueryBuilder($this->mockBuilder->buildSelectMock());

        //add first select column
        $this->assertSame($queryBuilder, $queryBuilder->select('u.id'));

        //replace first select column with an array of columns
        $this->assertSame($queryBuilder, $queryBuilder->select(['u.name', 'COUNT(g.group) AS counter']));

        //the select without parameters not reset the select columns
        $this->assertSame($queryBuilder, $queryBuilder->addSelect());

        //the addSelect add one column to the other select columns
        $this->assertSame($queryBuilder, $queryBuilder->addSelect('u.password'));

        //the addSelect add an array of columns to the other select columns
        $this->assertSame($queryBuilder, $queryBuilder->addSelect(['u.firstname', 'u.sex']));

        //the select without params remove all select columns
        $this->assertSame($queryBuilder, $queryBuilder->select());
    }

    /**
     * @test
     */
    public function insert () {
        $queryBuilder = new DbQueryBuilder($this->mockBuilder->buildInsertMock());

        //set insert table
        $this->assertSame($queryBuilder, $queryBuilder->insert('example1'));

        //overwrite insert table
        $this->assertSame($queryBuilder, $queryBuilder->insert('example2'));
    }

    /**
     * @test
     */
    public function update () {
        $queryBuilder = new DbQueryBuilder($this->mockBuilder->buildUpdateMock());

        //set update table
        $this->assertSame($queryBuilder, $queryBuilder->update('example1'));

        //overwrite update table
        $this->assertSame($queryBuilder, $queryBuilder->update('example2'));
    }

    /**
     * @test
     */
    public function delete () {
        $queryBuilder = new DbQueryBuilder($this->mockBuilder->buildDeleteMock());

        //set delete table
        $this->assertSame($queryBuilder, $queryBuilder->delete('example1'));

        //overwrite delete table
        $this->assertSame($queryBuilder, $queryBuilder->delete('example2'));
    }

    /**
     * @test
     */
    public function values () {
        $queryBuilder = new DbQueryBuilder($this->mockBuilder->buildValuesMock());

        //set values
        $this->assertSame($queryBuilder, $queryBuilder->values(['column2' => 'value2', 'column1' => 'value1']));

        //overwrite values
        $this->assertSame($queryBuilder, $queryBuilder->values(['column3' => 'value3', 'column4' => 'value4']));
    }

    /**
     * @test
     */
    public function from () {
        $queryBuilder = new DbQueryBuilder($this->mockBuilder->buildFromMock());

        //set from
        $this->assertSame($queryBuilder, $queryBuilder->from('user', 'u'));

        //replace from
        $this->assertSame($queryBuilder, $queryBuilder->from('person', 'p'));
    }

    /**
     * @test
     */
    public function innerJoin () {
        $queryBuilder = new DbQueryBuilder($this->mockBuilder->buildInnerJoinMock());

        //add a first innerJoin with the join method
        $this->assertSame($queryBuilder, $queryBuilder->join('group', 'g', 'WITH', 'g.id = p.group_id'));

        //add a second innerJoin
        $this->assertSame($queryBuilder, $queryBuilder->innerJoin('function', 'f', 'WITH', 'f.id = g.function_id'));
    }

    /**
     * @test
     */
    public function leftJoin () {
        $queryBuilder = new DbQueryBuilder($this->mockBuilder->buildLeftJoinMock());

        //add a first leftJoin with the join method
        $this->assertSame($queryBuilder, $queryBuilder->join('group', 'g', 'WITH', 'g.id = p.group_id', 'LEFT'));

        //add a second leftJoin
        $this->assertSame($queryBuilder, $queryBuilder->leftJoin('function', 'f', 'WITH', 'f.id = g.function_id'));
    }

    /**
     * @test
     */
    public function where_andWhere_orWhere () {
        $queryBuilder = new DbQueryBuilder($this->mockBuilder->buildWhereMock());

        //set the first where expression
        $this->assertSame($queryBuilder, $queryBuilder->where('u.id = :user'));

        //add an and expression
        $this->assertSame($queryBuilder, $queryBuilder->andWhere('u.name IN(:names)'));

        //add an or expression
        $this->assertSame($queryBuilder, $queryBuilder->orWhere('u.group IN(:groups)'));

        //overwrite the expressions
        $this->assertSame($queryBuilder, $queryBuilder->where('u.password = :password'));
    }

    /**
     * @test
     */
    public function groupBy_addGroupBy () {
        $queryBuilder = new DbQueryBuilder($this->mockBuilder->buildGroupByMock());

        //set first groupBy column
        $this->assertSame($queryBuilder, $queryBuilder->groupBy('u.id'));

        //overwrite with to columns
        $this->assertSame($queryBuilder, $queryBuilder->groupBy(['u.firstname', 'u.name']));

        //add one column
        $this->assertSame($queryBuilder, $queryBuilder->addGroupBy('u.group'));

        //add two columns
        $this->assertSame($queryBuilder, $queryBuilder->addGroupBy(['u.password', 'u.lastname']));
    }

    /**
     * @test
     */
    public function having_andHaving_orHaving () {
        $queryBuilder = new DbQueryBuilder($this->mockBuilder->buildHavingMock());

        //set the first having expression
        $this->assertSame($queryBuilder, $queryBuilder->having('name = :name'));

        //add an and having expression
        $this->assertSame($queryBuilder, $queryBuilder->andHaving('password = :password'));

        //add an or having expression
        $this->assertSame($queryBuilder, $queryBuilder->orHaving('id IN(:ids)'));
    }

    /**
     * @test
     */
    public function orderBy () {
        $queryBuilder = new DbQueryBuilder($this->mockBuilder->buildOrderByMock());

        //set the first ASC order
        $this->assertSame($queryBuilder, $queryBuilder->orderBy('u.id'));

        //add an order array
        $this->assertSame($queryBuilder, $queryBuilder->orderBy(['u.group', 'u.name']));

        //add an DESC order
        $this->assertSame($queryBuilder, $queryBuilder->orderBy('u.password', 'DESC'));
    }
}