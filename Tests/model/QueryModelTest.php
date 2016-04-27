<?php
use database\MySqlQueryBuilderBundle\model\QueryModel;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 24.04.2016
 * Time: 17:42
 */
class QueryModelTest extends PHPUnit_Framework_TestCase {
    /**
     * @var QueryModel
     */
    private $queryModel;

    public function setUp () {
        $this->queryModel = new QueryModel();
    }

    /**
     * @test
     */
    public function select () {
        $this->assertSame($this->queryModel, $this->queryModel->setSelect(['select']));
        $this->assertSame(['select'], $this->queryModel->getSelect());
    }

    /**
     * @test
     */
    public function from () {
        $this->assertSame($this->queryModel, $this->queryModel->setFrom(['from']));
        $this->assertSame(['from'], $this->queryModel->getFrom());
    }

    /**
     * @test
     */
    public function join () {
        $this->assertSame($this->queryModel, $this->queryModel->setJoin(['join']));
        $this->assertSame(['join'], $this->queryModel->getJoin());
        $this->assertSame($this->queryModel, $this->queryModel->addJoin('join2'));
        $this->assertSame(['join', 'join2'], $this->queryModel->getJoin());
    }

    /**
     * @test
     */
    public function where () {
        $this->assertSame($this->queryModel, $this->queryModel->setWhere(['where']));
        $this->assertSame(['where'], $this->queryModel->getWhere());
        $this->assertSame($this->queryModel, $this->queryModel->addWhere('where2'));
        $this->assertSame(['where', 'where2'], $this->queryModel->getWhere());
    }

    /**
     * @test
     */
    public function groupBy () {
        $this->assertSame($this->queryModel, $this->queryModel->setGroupBy(['groupBy']));
        $this->assertSame(['groupBy'], $this->queryModel->getGroupBy());
    }

    /**
     * @test
     */
    public function having () {
        $this->assertSame($this->queryModel, $this->queryModel->setHaving(['having']));
        $this->assertSame(['having'], $this->queryModel->getHaving());
        $this->assertSame($this->queryModel, $this->queryModel->addHaving('having2'));
        $this->assertSame(['having', 'having2'], $this->queryModel->getHaving());
    }

    /**
     * @test
     */
    public function orderBy () {
        $this->assertSame($this->queryModel, $this->queryModel->setOrderBy(['orderBy']));
        $this->assertSame(['orderBy'], $this->queryModel->getOrderBy());
        $this->assertSame($this->queryModel, $this->queryModel->addOrderBy('orderBy2'));
        $this->assertSame(['orderBy', 'orderBy2'], $this->queryModel->getOrderBy());
    }

    /**
     * @test
     */
    public function values () {
        $this->assertSame($this->queryModel, $this->queryModel->setValues(['values']));
        $this->assertSame(['values'], $this->queryModel->getValues());
    }

    /**
     * @test
     */
    public function type () {
        $this->assertSame($this->queryModel, $this->queryModel->setType('type'));
        $this->assertSame('type', $this->queryModel->getType());
    }
}