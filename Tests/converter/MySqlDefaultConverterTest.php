<?php
use database\MySqlQueryBuilderBundle\converter\MySqlDefaultConverter;
use database\MySqlQueryBuilderBundle\model\QueryModel;
use database\MySqlQueryBuilderBundle\converter\QueryConversionException;
use database\MySqlQueryBuilderBundle\Tests\converter\MySqlDefaultConverterMockBuilder;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 16.04.2016
 * Time: 19:05
 */
class MySqlDefaultConverterTest extends PHPUnit_Framework_TestCase {
    /**
     * @var MySqlDefaultConverter
     */
    private $queryConverter;

    /**
     * @var ReflectionClass
     */
    private $queryConverterReflection;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $emptyObject;

    /**
     * @var MySqlDefaultConverterMockBuilder
     */
    private $mockBuilder;

    protected function setUp () {
        $this->mockBuilder = new MySqlDefaultConverterMockBuilder($this);
        $this->emptyObject = $this->mockBuilder->buildEmptyMock();
        $this->queryConverter = new MySqlDefaultConverter();
        $this->queryConverterReflection = new ReflectionClass(MySqlDefaultConverter::class);
    }

    /**
     * @test
     */
    public function validate_withInvalidModel () {
        $this->setExpectedExceptionRegExp(QueryConversionException::class);
        $queryModelMock = $this->mockBuilder->buildQueryModelMock('invalid');
        $converter = new MySqlDefaultConverter();
        $converter->validate($queryModelMock);
    }

    /**
     * @test
     */
    public function validate_withSelectModel () {
        $queryModelMock = $this->mockBuilder->buildQueryModelMock();
        $mockValidator = $this->mockBuilder->buildValidateMock_withSelect($queryModelMock);
        $mockValidator->validate($queryModelMock);
    }

    /**
     * @test
     */
    public function validate_withInsertModel () {
        $queryModelMock = $this->mockBuilder->buildQueryModelMock(QueryModel::QUERY_TYPE_INSERT);
        $mockValidator = $this->mockBuilder->buildValidateMock_withInsert($queryModelMock);
        $mockValidator->validate($queryModelMock);
    }

    /**
     * @test
     */
    public function validate_withUpdateModel () {
        $queryModelMock = $this->mockBuilder->buildQueryModelMock(QueryModel::QUERY_TYPE_UPDATE);
        $mockValidator = $this->mockBuilder->buildValidateMock_withUpdate($queryModelMock);
        $mockValidator->validate($queryModelMock);
    }

    /**
     * @test
     */
    public function validate_withDeleteModel () {
        $queryModelMock = $this->mockBuilder->buildQueryModelMock(QueryModel::QUERY_TYPE_DELETE);
        $mockValidator = $this->mockBuilder->buildValidateMock_withDelete($queryModelMock);
        $mockValidator->validate($queryModelMock);
    }

    /**
     * @test
     */
    public function validateSelect_withEmptyModel () {
        $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetter('getSelect', []);
        $this->callValidateReflection('validateSelect', $queryModelMock);
    }

    /**
     * @test
     */
    public function validateSelect_withValidModel () {
        $select = ['u.id', 'COUNT(g.id) AS counter', 'DISTINCT u.name'];
        $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetter('getSelect', $select);
        $this->callValidateReflection('validateSelect', $queryModelMock);
    }

    /**
     * @test
     */
    public function validateSelect_withInvalidModels () {
        $invalidArguments = [$this->emptyObject, [$this->emptyObject], [1], [true], [[]], 'string'];

        foreach ($invalidArguments as $arguments) {
            $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetter('getSelect', $arguments);
            try {
                $this->callValidateReflection('validateSelect', $queryModelMock);
                $this->fail('invalid query model accepted');
            } catch (QueryConversionException $e) {
                $this->assertContains('invalid select', $e->getMessage());
            }
        }
    }

    /**
     * @test
     */
    public function validateValues_withEmptyModel () {
        $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetter('getValues', []);
        $this->callValidateReflection('validateValues', $queryModelMock);
    }

    /**
     * @test
     */
    public function validateValues_withValidModel () {
        $values = ['key1' => 'value1', 'key2' => 'value2'];
        $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetter('getValues', $values);
        $this->callValidateReflection('validateValues', $queryModelMock);
    }

    /**
     * @test
     */
    public function validateValues_withInvalidModels () {
        $invalidArguments = [$this->emptyObject,
                             [$this->emptyObject],
                             [1],
                             [true],
                             [[]],
                             'string',
                             ['key' => $this->emptyObject]];

        foreach ($invalidArguments as $arguments) {
            $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetter('getValues', $arguments);
            try {
                $this->callValidateReflection('validateValues', $queryModelMock);
                $this->fail('invalid query model accepted');
            } catch (QueryConversionException $e) {
                $this->assertContains('invalid values', $e->getMessage());
            }
        }
    }

    /**
     * @test
     */
    public function validateFrom_withValidModel () {
        $from = ['table' => 'test1', 'alias' => 'test2'];
        $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetter('getFrom', $from);
        $this->callValidateReflection('validateFrom', $queryModelMock);
    }

    /**
     * @test
     */
    public function validateFrom_withInvalidModels () {
        $invalidArguments = [$this->emptyObject,
                             [$this->emptyObject],
                             ['table' => $this->emptyObject],
                             ['table' => 'test1', 'alias' => $this->emptyObject],
                             [1],
                             [true],
                             [[]],
                             'string'];

        foreach ($invalidArguments as $arguments) {
            $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetter('getFrom', $arguments);
            try {
                $this->callValidateReflection('validateFrom', $queryModelMock);
                $this->fail('invalid from arguments accepted');
            } catch (QueryConversionException $e) {
                $this->assertContains('invalid from', $e->getMessage());
            }
        }
    }

    /**
     * @test
     */
    public function validateJoin_withValidModel () {
        $joins = [['joinType' => 'INNER',
                   'join' => 'table',
                   'alias' => 'alias',
                   'type' => 'WITH',
                   'condition' => 'condition'],
                  ['joinType' => 'LEFT',
                   'join' => 'table',
                   'alias' => 'alias',
                   'type' => 'ON',
                   'condition' => 'condition'],
                  ['joinType' => 'RIGHT',
                   'join' => 'table',
                   'alias' => 'alias',
                   'type' => 'USING',
                   'condition' => 'condition'],
                  ['joinType' => 'LEFT OUTER',
                   'join' => 'table',
                   'alias' => 'alias',
                   'type' => 'with',
                   'condition' => 'condition'],
                  ['joinType' => 'right outer',
                   'join' => 'table',
                   'alias' => 'alias',
                   'type' => 'on',
                   'condition' => 'condition']];

        $joinsCompare = [['joinType' => 'INNER',
                          'join' => 'table',
                          'alias' => 'alias',
                          'type' => 'ON',
                          'condition' => 'condition'],
                         ['joinType' => 'LEFT',
                          'join' => 'table',
                          'alias' => 'alias',
                          'type' => 'ON',
                          'condition' => 'condition'],
                         ['joinType' => 'RIGHT',
                          'join' => 'table',
                          'alias' => 'alias',
                          'type' => 'USING',
                          'condition' => 'condition'],
                         ['joinType' => 'LEFT OUTER',
                          'join' => 'table',
                          'alias' => 'alias',
                          'type' => 'ON',
                          'condition' => 'condition'],
                         ['joinType' => 'RIGHT OUTER',
                          'join' => 'table',
                          'alias' => 'alias',
                          'type' => 'ON',
                          'condition' => 'condition']];

        $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetterAndOneSetter('getJoin',
                                                                                           $joins,
                                                                                           'setJoin',
                                                                                           $joinsCompare);
        $this->callValidateReflection('validateJoin', $queryModelMock);
    }

    /**
     * @test
     */
    public function validateJoin_withInvalidModels () {
        $invalidArguments = [$this->emptyObject,
                             [$this->emptyObject],
                             [[]],
                             [[$this->emptyObject]],
                             [['joinType' => $this->emptyObject]],
                             [['joinType' => 'test']],
                             [['joinType' => 'INNER']],
                             [['joinType' => 'INNER', 'join' => $this->emptyObject]],
                             [['joinType' => 'INNER', 'join' => $this->emptyObject, 'type' => 'WITH']],
                             [['joinType' => 'INNER',
                               'join' => 'test',
                               'type' => 'WITH',
                               'alias' => $this->emptyObject]],
                             [['joinType' => 'INNER',
                               'join' => 'test',
                               'type' => 'WITH',
                               'alias' => 'test',
                               'condition' => $this->emptyObject]],
                             [['joinType' => 'INNER', 'join' => 'test']],
                             [['joinType' => 'INNER', 'join' => 'test', 'type' => $this->emptyObject]],
                             [['joinType' => 'INNER', 'join' => 'test', 'type' => 'test']],];

        foreach ($invalidArguments as $arguments) {
            $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetter('getJoin', $arguments);
            try {
                $this->callValidateReflection('validateJoin', $queryModelMock);
                $this->fail('invalid join arguments accepted');
            } catch (QueryConversionException $e) {
                $this->assertContains('invalid join', $e->getMessage());
            }
        }
    }

    /**
     * @test
     */
    public function validateWhere_withEmptyModel () {
        $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetter('getWhere', []);
        $this->callValidateReflection('validateWhere', $queryModelMock);
    }

    /**
     * @test
     */
    public function validateWhere_withValidModel () {
        $where = [['whereType' => 'AND', 'where' => 'test'],
                  ['whereType' => 'and', 'where' => 'test'],
                  ['whereType' => 'OR', 'where' => 'test'],
                  ['whereType' => 'or', 'where' => 'test']];
        $whereCompare = [['whereType' => 'AND', 'where' => 'test'],
                         ['whereType' => 'AND', 'where' => 'test'],
                         ['whereType' => 'OR', 'where' => 'test'],
                         ['whereType' => 'OR', 'where' => 'test']];


        $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetterAndOneSetter('getWhere',
                                                                                           $where,
                                                                                           'setWhere',
                                                                                           $whereCompare);
        $this->callValidateReflection('validateWhere', $queryModelMock);
    }

    /**
     * @test
     */
    public function validateWhere_withInvalidModels () {
        $invalidArguments = [$this->emptyObject,
                             [$this->emptyObject],
                             [[]],
                             [[$this->emptyObject]],
                             [['whereType' => $this->emptyObject]],
                             [['whereType' => 'test']],
                             [['whereType' => 'and']],
                             [['whereType' => 'and', 'where' => $this->emptyObject]],];

        foreach ($invalidArguments as $arguments) {
            $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetter('getWhere', $arguments);
            try {
                $this->callValidateReflection('validateWhere', $queryModelMock);
                $this->fail('invalid where arguments accepted');
            } catch (QueryConversionException $e) {
                $this->assertContains('invalid where', $e->getMessage());
            }
        }
    }

    /**
     * @test
     */
    public function validateGroupBy_withEmptyModel () {
        $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetter('getGroupBy', []);
        $this->callValidateReflection('validateGroupBy', $queryModelMock);
    }

    /**
     * @test
     */
    public function validateGroupBy_withValidModel () {
        $groupBy = ['u.id', 'COUNT(g.id) AS counter', 'DISTINCT u.name'];

        $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetter('getGroupBy', $groupBy);
        $this->callValidateReflection('validateGroupBy', $queryModelMock);
    }

    /**
     * @test
     */
    public function validateGroupBy_withInvalidModels () {
        $invalidArguments = [$this->emptyObject, [$this->emptyObject], [1], [true], [[]], 'string'];

        foreach ($invalidArguments as $arguments) {
            $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetter('getGroupBy', $arguments);
            try {
                $this->callValidateReflection('validateGroupBy', $queryModelMock);
                $this->fail('invalid groupBy arguments accepted');
            } catch (QueryConversionException $e) {
                $this->assertContains('invalid groupBy', $e->getMessage());
            }
        }
    }

    /**
     * @test
     */
    public function validateHaving_withEmptyModel () {
        $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetter('getHaving', []);
        $this->callValidateReflection('validateHaving', $queryModelMock);
    }

    /**
     * @test
     */
    public function validateHaving_withValidModel () {
        $having = [['havingType' => 'AND', 'having' => 'test'],
                   ['havingType' => 'and', 'having' => 'test'],
                   ['havingType' => 'OR', 'having' => 'test'],
                   ['havingType' => 'or', 'having' => 'test']];
        $havingCompare = [['havingType' => 'AND', 'having' => 'test'],
                          ['havingType' => 'AND', 'having' => 'test'],
                          ['havingType' => 'OR', 'having' => 'test'],
                          ['havingType' => 'OR', 'having' => 'test']];

        $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetterAndOneSetter('getHaving',
                                                                                           $having,
                                                                                           'setHaving',
                                                                                           $havingCompare);
        $this->callValidateReflection('validateHaving', $queryModelMock);
    }

    /**
     * @test
     */
    public function validateHaving_withInvalidModels () {
        $invalidArguments = [$this->emptyObject,
                             [$this->emptyObject],
                             [[]],
                             [[$this->emptyObject]],
                             [['havingType' => $this->emptyObject]],
                             [['havingType' => 'test']],
                             [['havingType' => 'and']],
                             [['havingType' => 'and', 'having' => $this->emptyObject]],];

        foreach ($invalidArguments as $arguments) {
            $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetter('getHaving', $arguments);
            try {
                $this->callValidateReflection('validateHaving', $queryModelMock);
                $this->fail('invalid having arguments accepted');
            } catch (QueryConversionException $e) {
                $this->assertContains('invalid having', $e->getMessage());
            }
        }
    }

    /**
     * @test
     */
    public function validateOrderBy_withEmptyModel () {
        $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetter('getOrderBy', []);
        $this->callValidateReflection('validateOrderBy', $queryModelMock);
    }

    /**
     * @test
     */
    public function validateOrderBy_withValidModel () {
        $orderBy = [['sort' => ['u.id', 'COUNT(g.id) AS counter', 'DISTINCT u.name'], 'order' => 'ASC'],
                    ['sort' => ['u.param'], 'order' => 'desc']];
        $orderByCompare = [['sort' => ['u.id', 'COUNT(g.id) AS counter', 'DISTINCT u.name'], 'order' => 'ASC'],
                           ['sort' => ['u.param'], 'order' => 'DESC']];

        $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetterAndOneSetter('getOrderBy',
                                                                                           $orderBy,
                                                                                           'setOrderBy',
                                                                                           $orderByCompare);
        $this->callValidateReflection('validateOrderBy', $queryModelMock);
    }

    /**
     * @test
     */
    public function validateOrderBy_withInvalidArguments () {
        $invalidArguments = [$this->emptyObject,
                             [$this->emptyObject],
                             [[]],
                             [[$this->emptyObject]],
                             [['sort' => $this->emptyObject]],
                             [['sort' => 'string']],
                             [['sort' => [$this->emptyObject]]],
                             [['sort' => ['string']]],
                             [['sort' => ['string'], 'order' => $this->emptyObject]],
                             [['sort' => ['string'], 'order' => 'test']]];

        foreach ($invalidArguments as $arguments) {
            $queryModelMock = $this->mockBuilder->buildQueryModelMockWithOneGetter('getOrderBy', $arguments);
            try {
                $this->callValidateReflection('validateOrderBy', $queryModelMock);
                $this->fail('invalid orderBy arguments accepted');
            } catch (QueryConversionException $e) {
                $this->assertContains('invalid orderBy', $e->getMessage());
            }
        }
    }

    private function callValidateReflection ($method, QueryModel $model) {
        $methodReflection = $this->queryConverterReflection->getMethod($method);
        $methodReflection->setAccessible(true);

        return $methodReflection->invokeArgs($this->queryConverter, [$model]);
    }
}