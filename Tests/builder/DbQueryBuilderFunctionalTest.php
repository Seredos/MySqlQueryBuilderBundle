<?php
use database\MySqlQueryBuilderBundle\converter\MySqlDefaultConverter;
use database\MySqlQueryBuilderBundle\builder\DbQueryBuilder;
use database\MySqlQueryBuilderBundle\model\QueryModel;
use database\MySqlQueryBuilderBundle\sql\MySqlBuilder;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 17.04.2016
 * Time: 22:56
 */
class DbQueryBuilderFunctionalTest extends PHPUnit_Framework_TestCase {
    /**
     * @var DbQueryBuilder
     */
    private $queryBuilder;

    /**
     * @var QueryModel
     */
    private $queryModel;
    /**
     * @var MySqlDefaultConverter
     */
    private $queryConverter;

    /**
     * @var MySqlBuilder
     */
    private $query;

    protected function setUp () {
        $this->query = new MySqlBuilder();
        $this->queryConverter = new MySqlDefaultConverter();

        $this->queryModel = new QueryModel();
        $this->queryBuilder = new DbQueryBuilder($this->queryModel);
    }

    /**
     * @test
     */
    public function simpleSelect () {
        $this->queryBuilder->select()
                           ->from('example1', 'alias');

        $this->queryConverter->validate($this->queryModel);
        $this->query->buildQuery($this->queryModel);

        $this->assertSame('SELECT * FROM example1 alias ', $this->query->getSql());
    }

    /**
     * @test
     */
    public function completeSelect () {
        $this->queryBuilder->select(['u.id', 'u.firstname'])
                           ->from('user', 'u')
                           ->leftJoin('group', 'g', 'WITH', 'g.id = u.group_id')
                           ->where('u.lastname LIKE :lastname')
                           ->groupBy(['u.id',
                                      'u.firstname'])
                           ->having('u.firstname LIKE :firstname')
                           ->orderBy('u.firstname');

        $this->queryConverter->validate($this->queryModel);
        $this->query->buildQuery($this->queryModel);

        $this->assertSame('SELECT u.id,u.firstname FROM user u LEFT JOIN group g ON g.id = u.group_id WHERE u.lastname LIKE :lastname GROUP BY u.id,u.firstname HAVING u.firstname LIKE :firstname ORDER BY u.firstname ASC ',
                          $this->query->getSql());
    }

    /**
     * @test
     */
    public function simpleInsert () {
        $this->queryBuilder->insert('example1')
                           ->values(['column2' => 'value2', 'column1' => 'value1']);

        $this->queryConverter->validate($this->queryModel);
        $this->query->buildQuery($this->queryModel);

        $this->assertSame('INSERT INTO example1 (column2,column1) VALUES(value2,value1) ', $this->query->getSql());
    }

    /**
     * @test
     */
    public function simpleUpdate () {
        $this->queryBuilder->update('example1')
                           ->values(['column2' => 'value2', 'column1' => 'value1']);

        $this->queryConverter->validate($this->queryModel);
        $this->query->buildQuery($this->queryModel);

        $this->assertSame('UPDATE example1 SET column2 = value2,column1 = value1 ', $this->query->getSql());
    }

    /**
     * @test
     */
    public function completeUpdate () {
        $this->queryBuilder->update('example1')
                           ->values(['column2' => 'value2', 'column1' => 'value1'])
                           ->where('column3 = :param');

        $this->queryConverter->validate($this->queryModel);
        $this->query->buildQuery($this->queryModel);

        $this->assertSame('UPDATE example1 SET column2 = value2,column1 = value1 WHERE column3 = :param ',
                          $this->query->getSql());
    }

    /**
     * @test
     */
    public function simpleDelete () {
        $this->queryBuilder->delete('example1');

        $this->queryConverter->validate($this->queryModel);
        $this->query->buildQuery($this->queryModel);

        $this->assertSame('DELETE FROM example1 ', $this->query->getSql());
    }

    /**
     * @test
     */
    public function completeDelete () {
        $this->queryBuilder->delete('example1')
                           ->where('column3 = :param');

        $this->queryConverter->validate($this->queryModel);
        $this->query->buildQuery($this->queryModel);

        $this->assertSame('DELETE FROM example1 WHERE column3 = :param ', $this->query->getSql());
    }
}