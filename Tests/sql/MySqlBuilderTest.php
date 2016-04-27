<?php
use database\MySqlQueryBuilderBundle\model\QueryModel;
use database\MySqlQueryBuilderBundle\sql\MySqlBuilder;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 16.04.2016
 * Time: 02:06
 */
class MySqlBuilderTest extends PHPUnit_Framework_TestCase {
    /**
     * @var ReflectionClass
     */
    private $baseQueryReflection;

    /**
     * @var MySqlBuilder
     */
    private $query;

    protected function setUp () {
        $this->baseQueryReflection = new ReflectionClass(MySqlBuilder::class);
        $this->query = new MySqlBuilder();
    }

    /**
     * @test that the buildQuery method call the build methods in the correct order and set the values from the
     *       argumented model
     */
    public function buildQuery_withSelectQuery () {
        /* @var $mockQuery MySqlBuilder|PHPUnit_Framework_MockObject_MockObject */
        $mockQuery = $this->getMockBuilder(MySqlBuilder::class)
                          ->setMethods(['buildSelect',
                                        'buildFrom',
                                        'buildJoin',
                                        'buildWhere',
                                        'buildGroupBy',
                                        'buildHaving',
                                        'buildOrderBy'])
                          ->getMock();

        /* @var $queryModelMock QueryModel|PHPUnit_Framework_MockObject_MockObject */
        $queryModelMock = $this->getMockBuilder(QueryModel::class)
                               ->disableOriginalConstructor()
                               ->getMock();
        $queryModelMock->expects($this->once())
                       ->method('getType')
                       ->will($this->returnValue(QueryModel::QUERY_TYPE_SELECT));
        $queryModelMock->expects($this->once())
                       ->method('getSelect')
                       ->will($this->returnValue('example1'));
        $queryModelMock->expects($this->once())
                       ->method('getFrom')
                       ->will($this->returnValue('example2'));
        $queryModelMock->expects($this->once())
                       ->method('getJoin')
                       ->will($this->returnValue('example3'));
        $queryModelMock->expects($this->once())
                       ->method('getWhere')
                       ->will($this->returnValue('example4'));
        $queryModelMock->expects($this->once())
                       ->method('getGroupBy')
                       ->will($this->returnValue('example5'));
        $queryModelMock->expects($this->once())
                       ->method('getHaving')
                       ->will($this->returnValue('example6'));
        $queryModelMock->expects($this->once())
                       ->method('getOrderBy')
                       ->will($this->returnValue('example7'));

        $mockQuery->expects($this->at(0))
                  ->method('buildSelect')
                  ->with('example1')
                  ->will($this->returnValue('test1 '));
        $mockQuery->expects($this->at(1))
                  ->method('buildFrom')
                  ->with('example2')
                  ->will($this->returnValue('test2 '));
        $mockQuery->expects($this->at(2))
                  ->method('buildJoin')
                  ->with('example3')
                  ->will($this->returnValue('test3 '));
        $mockQuery->expects($this->at(3))
                  ->method('buildWhere')
                  ->with('example4')
                  ->will($this->returnValue('test4 '));
        $mockQuery->expects($this->at(4))
                  ->method('buildGroupBy')
                  ->with('example5')
                  ->will($this->returnValue('test5 '));
        $mockQuery->expects($this->at(5))
                  ->method('buildHaving')
                  ->with('example6')
                  ->will($this->returnValue('test6 '));
        $mockQuery->expects($this->at(6))
                  ->method('buildOrderBy')
                  ->with('example7')
                  ->will($this->returnValue('test7'));

        $mockQuery->buildQuery($queryModelMock);
        $this->assertSame('test1 test2 test3 test4 test5 test6 test7', $mockQuery->getSql());
    }

    /**
     * @test
     */
    public function buildQuery_withInsertQuery () {
        /* @var $mockQuery MySqlBuilder|PHPUnit_Framework_MockObject_MockObject */
        $mockQuery = $this->getMockBuilder(MySqlBuilder::class)
                          ->setMethods(['buildInsert', 'buildInsertValues'])
                          ->getMock();

        /* @var $queryModelMock QueryModel|PHPUnit_Framework_MockObject_MockObject */
        $queryModelMock = $this->getMockBuilder(QueryModel::class)
                               ->disableOriginalConstructor()
                               ->getMock();
        $queryModelMock->expects($this->once())
                       ->method('getType')
                       ->will($this->returnValue(QueryModel::QUERY_TYPE_INSERT));

        $queryModelMock->expects($this->once())
                       ->method('getFrom')
                       ->will($this->returnValue('example1'));
        $queryModelMock->expects($this->once())
                       ->method('getValues')
                       ->will($this->returnValue('example2'));

        $mockQuery->expects($this->at(0))
                  ->method('buildInsert')
                  ->with('example1')
                  ->will($this->returnValue('test1 '));
        $mockQuery->expects($this->at(1))
                  ->method('buildInsertValues')
                  ->with('example2')
                  ->will($this->returnValue('test2'));

        $mockQuery->buildQuery($queryModelMock);
        $this->assertSame('test1 test2', $mockQuery->getSql());
    }

    /**
     * @test
     */
    public function buildQuery_withUpdateQuery () {
        /* @var $mockQuery MySqlBuilder|PHPUnit_Framework_MockObject_MockObject */
        $mockQuery = $this->getMockBuilder(MySqlBuilder::class)
                          ->setMethods(['buildUpdate',
                                        'buildUpdateValues',
                                        'buildWhere'])
                          ->getMock();

        /* @var $queryModelMock QueryModel|PHPUnit_Framework_MockObject_MockObject */
        $queryModelMock = $this->getMockBuilder(QueryModel::class)
                               ->disableOriginalConstructor()
                               ->getMock();
        $queryModelMock->expects($this->once())
                       ->method('getType')
                       ->will($this->returnValue(QueryModel::QUERY_TYPE_UPDATE));

        $queryModelMock->expects($this->once())
                       ->method('getFrom')
                       ->will($this->returnValue('example1'));
        $queryModelMock->expects($this->once())
                       ->method('getValues')
                       ->will($this->returnValue('example2'));
        $queryModelMock->expects($this->once())
                       ->method('getWhere')
                       ->will($this->returnValue('example3'));

        $mockQuery->expects($this->at(0))
                  ->method('buildUpdate')
                  ->with('example1')
                  ->will($this->returnValue('test1 '));
        $mockQuery->expects($this->at(1))
                  ->method('buildUpdateValues')
                  ->with('example2')
                  ->will($this->returnValue('test2 '));
        $mockQuery->expects($this->at(2))
                  ->method('buildWhere')
                  ->with('example3')
                  ->will($this->returnValue('test3'));

        $mockQuery->buildQuery($queryModelMock);
        $this->assertSame('test1 test2 test3', $mockQuery->getSql());
    }

    /**
     * @test
     */
    public function buildQuery_withDeleteQuery () {
        /* @var $mockQuery MySqlBuilder|PHPUnit_Framework_MockObject_MockObject */
        $mockQuery = $this->getMockBuilder(MySqlBuilder::class)
                          ->setMethods(['buildDelete', 'buildWhere'])
                          ->getMock();

        /* @var $queryModelMock QueryModel|PHPUnit_Framework_MockObject_MockObject */
        $queryModelMock = $this->getMockBuilder(QueryModel::class)
                               ->disableOriginalConstructor()
                               ->getMock();
        $queryModelMock->expects($this->once())
                       ->method('getType')
                       ->will($this->returnValue(QueryModel::QUERY_TYPE_DELETE));

        $queryModelMock->expects($this->once())
                       ->method('getFrom')
                       ->will($this->returnValue('example1'));
        $queryModelMock->expects($this->once())
                       ->method('getWhere')
                       ->will($this->returnValue('example2'));

        $mockQuery->expects($this->at(0))
                  ->method('buildDelete')
                  ->with('example1')
                  ->will($this->returnValue('test1 '));
        $mockQuery->expects($this->at(1))
                  ->method('buildWhere')
                  ->with('example2')
                  ->will($this->returnValue('test2'));

        $mockQuery->buildQuery($queryModelMock);
        $this->assertSame('test1 test2', $mockQuery->getSql());
    }

    /**
     * @test
     */
    public function buildUpdate () {
        $this->assertSame('UPDATE example ', $this->callBuildReflection('buildUpdate', ['table' => 'example']));
    }

    /**
     * @test
     */
    public function buildInsert () {
        $this->assertSame('INSERT INTO example ', $this->callBuildReflection('buildInsert', ['table' => 'example']));
    }

    /**
     * @test
     */
    public function buildDelete () {
        $this->assertSame('DELETE FROM example ', $this->callBuildReflection('buildDelete', ['table' => 'example']));
    }

    /**
     * @test
     */
    public function buildInsertValues () {
        $this->assertSame('(example2,example1) VALUES(test2,test1) ',
                          $this->callBuildReflection('buildInsertValues',
                                                     ['example2' => 'test2',
                                                      'example1' => 'test1']));
    }

    /**
     * @test
     */
    public function buildUpdateValues () {
        $this->assertSame('', $this->callBuildReflection('buildUpdateValues', []));
        $this->assertSame('SET example2 = test2,example1 = test1 ',
                          $this->callBuildReflection('buildUpdateValues',
                                                     ['example2' => 'test2',
                                                      'example1' => 'test1']));
        $this->assertSame('SET example2 = test2 ',
                          $this->callBuildReflection('buildUpdateValues',
                                                     ['example2' => 'test2']));
    }

    /**
     * @test
     */
    public function buildSelect () {
        $this->assertSame('SELECT * ', $this->callBuildReflection('buildSelect', []));
        $this->assertSame('SELECT u.id ', $this->callBuildReflection('buildSelect', ['u.id']));
        $this->assertSame('SELECT u.id,u.name ', $this->callBuildReflection('buildSelect', ['u.id', 'u.name']));
    }

    /**
     * @test
     */
    public function buildFrom () {
        $this->assertSame('FROM user u ', $this->callBuildReflection('buildFrom', ['table' => 'user', 'alias' => 'u']));
    }

    /**
     * @test
     */
    public function buildJoin () {
        $sqlPart = [['joinType' => 'INNER',
                     'join' => 'authentication',
                     'alias' => 'a',
                     'type' => 'ON',
                     'condition' => 'u.authentication = a.id',],
                    ['joinType' => 'LEFT',
                     'join' => 'user_group',
                     'alias' => 'ug',
                     'type' => 'ON',
                     'condition' => 'ug.user_id = u.id',],
                    ['joinType' => 'INNER',
                     'join' => 'group',
                     'alias' => 'g',
                     'type' => 'USING',
                     'condition' => 'ug.group',],];
        $this->assertSame('INNER JOIN authentication a ON u.authentication = a.id LEFT JOIN user_group ug ON ug.user_id = u.id INNER JOIN group g USING(ug.group) ',
                          $this->callBuildReflection('buildJoin', $sqlPart));
    }

    /**
     * @test
     */
    public function buildWhere () {
        $sqlPart = [['whereType' => 'AND', 'where' => 'u.name = :name'],
                    ['whereType' => 'AND', 'where' => 'u.password = :password'],
                    ['whereType' => 'OR', 'where' => 'g.name = :admin'],];
        $this->assertSame('WHERE u.name = :name AND u.password = :password OR g.name = :admin ',
                          $this->callBuildReflection('buildWhere', $sqlPart));
    }

    /**
     * @test
     */
    public function buildGroupBy () {
        $this->assertSame('GROUP BY u.id ', $this->callBuildReflection('buildGroupBy', ['u.id']));
        $this->assertSame('GROUP BY u.id,u.name ', $this->callBuildReflection('buildGroupBy', ['u.id', 'u.name']));
    }

    /**
     * @test
     */
    public function buildHaving () {
        $sqlPart = [['havingType' => 'AND', 'having' => 'u.name LIKE :name'],
                    ['havingType' => 'AND', 'having' => 'u.password LIKE :password'],
                    ['havingType' => 'OR', 'having' => 'g.name LIKE :admin'],];
        $this->assertSame('HAVING u.name LIKE :name AND HAVING u.password LIKE :password OR HAVING g.name LIKE :admin ',
                          $this->callBuildReflection('buildHaving', $sqlPart));
    }

    /**
     * @test
     */
    public function buildOrderBy () {
        $sqlPart = [['sort' => ['u.name'], 'order' => 'ASC'], ['sort' => ['u.password', 'u.id'], 'order' => 'DESC'],];
        $this->assertSame('ORDER BY u.name ASC u.password,u.id DESC ',
                          $this->callBuildReflection('buildOrderBy', $sqlPart));
    }

    private function callBuildReflection ($method, $sqlParts) {
        $methodReflection = $this->baseQueryReflection->getMethod($method);
        $methodReflection->setAccessible(true);

        return $methodReflection->invokeArgs($this->query, [$sqlParts]);
    }
}