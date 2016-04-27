<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 17.04.2016
 * Time: 03:17
 */
namespace database\MySqlQueryBuilderBundle\Tests\converter;

use database\MySqlQueryBuilderBundle\converter\MySqlDefaultConverter;
use database\MySqlQueryBuilderBundle\model\QueryModel;
use PHPUnit_Framework_MockObject_MockObject;

class MySqlDefaultConverterMockBuilder {
    /**
     * @var \PHPUnit_Framework_TestCase
     */
    private $testCase;

    public function __construct (\PHPUnit_Framework_TestCase $testCase) {
        $this->testCase = $testCase;
    }

    /**
     * @param string $type
     *
     * @return QueryModel|PHPUnit_Framework_MockObject_MockObject
     */
    public function buildQueryModelMock ($type = QueryModel::QUERY_TYPE_SELECT) {
        $queryModelMock = $this->testCase->getMockBuilder(QueryModel::class)
                                         ->getMock();
        $queryModelMock->expects($this->testCase->any())
                       ->method('getType')
                       ->will($this->testCase->returnValue($type));

        return $queryModelMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function buildEmptyMock () {
        return $this->testCase->getMockBuilder('emptyObject')
                              ->getMock();
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $queryModelMock
     *
     * @return MySqlDefaultConverter|\PHPUnit_Framework_MockObject_MockObject
     */
    public function buildValidateMock_withSelect (PHPUnit_Framework_MockObject_MockObject $queryModelMock) {
        /* @var $mockValidator MySqlDefaultConverter|PHPUnit_Framework_MockObject_MockObject */
        $mockValidator = $this->testCase->getMockBuilder(MySqlDefaultConverter::class)
                                        ->setMethods(['validateSelect',
                                                      'validateFrom',
                                                      'validateJoin',
                                                      'validateWhere',
                                                      'validateGroupBy',
                                                      'validateHaving',
                                                      'validateOrderBy'])
                                        ->getMock();

        $mockValidator->expects($this->testCase->once())
                      ->method('validateSelect')
                      ->with($queryModelMock)
                      ->will($this->testCase->returnValue(null));
        $mockValidator->expects($this->testCase->once())
                      ->method('validateFrom')
                      ->with($queryModelMock)
                      ->will($this->testCase->returnValue(null));
        $mockValidator->expects($this->testCase->once())
                      ->method('validateJoin')
                      ->with($queryModelMock)
                      ->will($this->testCase->returnValue(null));
        $mockValidator->expects($this->testCase->once())
                      ->method('validateWhere')
                      ->with($queryModelMock)
                      ->will($this->testCase->returnValue(null));
        $mockValidator->expects($this->testCase->once())
                      ->method('validateGroupBy')
                      ->with($queryModelMock)
                      ->will($this->testCase->returnValue(null));
        $mockValidator->expects($this->testCase->once())
                      ->method('validateHaving')
                      ->with($queryModelMock)
                      ->will($this->testCase->returnValue(null));
        $mockValidator->expects($this->testCase->once())
                      ->method('validateOrderBy')
                      ->with($queryModelMock)
                      ->will($this->testCase->returnValue(null));

        return $mockValidator;
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $queryModelMock
     *
     * @return MySqlDefaultConverter|\PHPUnit_Framework_MockObject_MockObject
     */
    public function buildValidateMock_withInsert (PHPUnit_Framework_MockObject_MockObject $queryModelMock) {
        /* @var $mockValidator MySqlDefaultConverter|PHPUnit_Framework_MockObject_MockObject */
        $mockValidator = $this->testCase->getMockBuilder(MySqlDefaultConverter::class)
                                        ->setMethods(['validateFrom',
                                                      'validateValues'])
                                        ->getMock();

        $mockValidator->expects($this->testCase->once())
                      ->method('validateFrom')
                      ->with($queryModelMock)
                      ->will($this->testCase->returnValue(null));
        $mockValidator->expects($this->testCase->once())
                      ->method('validateValues')
                      ->with($queryModelMock)
                      ->will($this->testCase->returnValue(null));

        return $mockValidator;
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $queryModelMock
     *
     * @return MySqlDefaultConverter|\PHPUnit_Framework_MockObject_MockObject
     */
    public function buildValidateMock_withUpdate (PHPUnit_Framework_MockObject_MockObject $queryModelMock) {
        /* @var $mockValidator MySqlDefaultConverter|PHPUnit_Framework_MockObject_MockObject */
        $mockValidator = $this->testCase->getMockBuilder(MySqlDefaultConverter::class)
                                        ->setMethods(['validateFrom',
                                                      'validateValues',
                                                      'validateWhere'])
                                        ->getMock();

        $mockValidator->expects($this->testCase->once())
                      ->method('validateFrom')
                      ->with($queryModelMock)
                      ->will($this->testCase->returnValue(null));
        $mockValidator->expects($this->testCase->once())
                      ->method('validateValues')
                      ->with($queryModelMock)
                      ->will($this->testCase->returnValue(null));
        $mockValidator->expects($this->testCase->once())
                      ->method('validateWhere')
                      ->with($queryModelMock)
                      ->will($this->testCase->returnValue(null));

        return $mockValidator;
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $queryModelMock
     *
     * @return MySqlDefaultConverter|\PHPUnit_Framework_MockObject_MockObject
     */
    public function buildValidateMock_withDelete (PHPUnit_Framework_MockObject_MockObject $queryModelMock) {
        /* @var $mockValidator MySqlDefaultConverter|PHPUnit_Framework_MockObject_MockObject */
        $mockValidator = $this->testCase->getMockBuilder(MySqlDefaultConverter::class)
                                        ->setMethods(['validateFrom',
                                                      'validateWhere'])
                                        ->getMock();

        $mockValidator->expects($this->testCase->once())
                      ->method('validateFrom')
                      ->with($queryModelMock)
                      ->will($this->testCase->returnValue(null));
        $mockValidator->expects($this->testCase->once())
                      ->method('validateWhere')
                      ->with($queryModelMock)
                      ->will($this->testCase->returnValue(null));

        return $mockValidator;
    }

    /**
     * @param string $method
     * @param array  $result
     *
     * @return QueryModel|\PHPUnit_Framework_MockObject_MockObject
     */
    public function buildQueryModelMockWithOneGetter ($method, $result) {
        $queryModelMock = $this->buildQueryModelMock();
        $queryModelMock->expects($this->testCase->once())
                       ->method($method)
                       ->willReturn($result);

        return $queryModelMock;
    }

    /**
     * @param string $getMethod
     * @param array  $getResult
     * @param string $setMethod
     * @param array  $setResult
     *
     * @return QueryModel|\PHPUnit_Framework_MockObject_MockObject
     */
    public function buildQueryModelMockWithOneGetterAndOneSetter ($getMethod, $getResult, $setMethod, $setResult) {
        $queryModelMock = $this->buildQueryModelMockWithOneGetter($getMethod, $getResult);
        $queryModelMock->expects($this->testCase->once())
                       ->method($setMethod)
                       ->with($setResult);

        return $queryModelMock;
    }
}