<?php
use database\MySqlQueryBuilderBundle\converter\MySqlDefaultConverter;
use database\MySqlQueryBuilderBundle\builder\DbQueryBuilder;
use database\MySqlQueryBuilderBundle\model\QueryModel;
use database\MySqlQueryBuilderBundle\converter\QueryConversionException;
use database\MySqlQueryBuilderBundle\sql\MySqlBuilder;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 24.04.2016
 * Time: 10:29
 */
class DbQueryBuilderFunctionalDatabaseTest extends PHPUnit_Framework_TestCase {
    /**
     * @var MySqlDefaultConverter
     */
    private $converter;

    /**
     * @var MySqlBuilder
     */
    private $sqlBuilder;

    /**
     * @var PDO
     */
    private $pdoConnection;

    private $config = ['server' => '127.0.0.1', 'user' => 'root', 'password' => '', 'name' => 'test2'];

    protected function setUp () {
        $this->markTestSkipped('please configure your database on this place to run this tests');
        $this->pdoConnection = new \PDO('mysql:host='.$this->config['server'].';',
                                        $this->config['user'],
                                        $this->config['password']);

        $this->pdoConnection->exec('DROP SCHEMA IF EXISTS '.$this->config['name']);
        $this->pdoConnection->exec('CREATE SCHEMA '.$this->config['name']);
        $this->pdoConnection->exec('USE '.$this->config['name']);
        $this->pdoConnection->exec(file_get_contents(__DIR__.'/../ConnectionTestSchema.sql'));

        $this->converter = new MySqlDefaultConverter();
        $this->sqlBuilder = new MySqlBuilder();
    }

    /**
     * @test
     */
    public function insertAndSelect () {
        $this->createParameters();
        $this->createPersons();

        $queryBuilder = $this->createQueryBuilder()
                             ->select(['p.id',
                                       'MAX(CASE WHEN pa.name = :param1 THEN pp.value ELSE :empty END) AS firstname',
                                       'MAX(CASE WHEN pa.name = :param2 THEN pp.value ELSE :empty END) AS lastname'])
                             ->from('person', 'p')
                             ->leftJoin('person_parameter', 'pp', 'WITH', 'pp.person_id = p.id')
                             ->leftJoin('parameter', 'pa', 'WITH', 'pa.id = pp.parameter_id')
                             ->groupBy('p.id');

        $queryStatement = $this->buildStatement($queryBuilder);
        $queryStatement->bindValue('param1', 'firstname');
        $queryStatement->bindValue('param2', 'lastname');
        $queryStatement->bindValue('empty', '');
        $queryStatement->execute();
        //print_r($queryStatement->fetchAll(PDO::FETCH_ASSOC));
    }

    private function createPersons () {
        $this->createPerson(['firstname' => 'Obiwan',
                             'lastname' => 'Kenobi',
                             'email' => 'obi@tatooine.de',
                             'password' => '123',
                             'city' => 'Naboo']);
        $this->createPerson(['firstname' => 'Anakin',
                             'lastname' => 'Skywalker',
                             'email' => 'vader@deathstar.de',
                             'password' => '234',
                             'city' => 'Tatooine']);
        $this->createPerson(['firstname' => 'Han',
                             'lastname' => 'Solo',
                             'email' => 'itsatrap@narshadaar.de',
                             'password' => '456',
                             'city' => 'Tatooine']);
    }

    private function createParameters () {
        $insertParameterBuilder = $this->createQueryBuilder();
        $insertParameterBuilder->insert('parameter')
                               ->values(['name' => ':name', '`order`' => ':order']);

        $parameterBuilderStatement = $this->buildStatement($insertParameterBuilder);

        $parameterBuilderStatement->execute(['name' => 'firstname', 'order' => 1]);
        $parameterBuilderStatement->execute(['name' => 'lastname', 'order' => 2]);
        $parameterBuilderStatement->execute(['name' => 'email', 'order' => 3]);
        $parameterBuilderStatement->execute(['name' => 'password', 'order' => 4]);
        $parameterBuilderStatement->execute(['name' => 'city', 'order' => 5]);
    }

    private function createPerson ($params) {
        $insertUserBuilder = $this->createQueryBuilder();
        $insertUserParamBuilder = $this->createQueryBuilder();

        $insertUserBuilder->insert('person')
                          ->values([]);

        $insertUserParamBuilder->insert('person_parameter')
                               ->values(['parameter_id' => ':parameter',
                                         'person_id' => ':person',
                                         '`value`' => ':value']);

        $userBuilderStatement = $this->buildStatement($insertUserBuilder);
        $userBuilderStatement->execute();
        $id = $this->pdoConnection->lastInsertId();

        $userParamBuilderStatement = $this->buildStatement($insertUserParamBuilder);

        foreach ($params as $key => $value) {
            $paramid = $this->findParameterByName($key);
            $userParamBuilderStatement->execute(['parameter' => $paramid, 'person' => $id, 'value' => $value]);
        }

        return $id;
    }

    private function findParameterByName ($name) {
        $selectParameterBuilder = $this->createQueryBuilder();
        $selectParameterBuilder->select('id')
                               ->from('parameter', 'p')
                               ->where('name = :name');
        $selectStatement = $this->buildStatement($selectParameterBuilder);
        $selectStatement->execute(['name' => $name]);

        return $selectStatement->fetch(PDO::FETCH_ASSOC)['id'];
    }

    private function createQueryBuilder () {
        $queryModel = new QueryModel();

        return new DbQueryBuilder($queryModel);
    }

    /**
     * @param DbQueryBuilder $builder
     *
     * @return PDOStatement
     * @throws QueryConversionException
     */
    private function buildStatement (DbQueryBuilder $builder) {
        $this->converter->validate($builder->getModel());
        $this->sqlBuilder->buildQuery($builder->getModel());

        return $this->pdoConnection->prepare($this->sqlBuilder->getSql());
    }
}