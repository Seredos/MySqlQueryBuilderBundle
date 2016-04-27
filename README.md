MySqlQueryBuilderBundle
=======================
This bundle represents a abstraction layer between buisness logic and sql

Installation
============
add the builder bundle in your composer.json as below:
```js
"require": {
    ...
    "database/MySqlQueryBuilderBundle" : "dev-master",
},
"repositories" : [
                    ...
                    {
                  			"type" : "vcs",
                  			"url" : "https://github.com/Seredos/MySqlQueryBuilderBundle"
                  		}],
```

Usage
=====
in first step, you need an converter for your queries. this converter normalize the query informations.
a custom converter can be created to parse table names from entities for example.
```php
$queryConverter = new MySqlDefaultConverter();
```

create a query model. this model contain all query informations eg (joins, columns, expressions e.t.c.)
```php
$queryModel = new QueryModel();
```

create a query builder for your model. this builder get you helpfully functions to fill the model
```php
$queryBuilder = new DbQueryBuilder($queryModel);
```

build a statement:

insert/update:
```php
if($id == 0){
    $queryBuilder->insert('tablename');
}else{
    $queryBuilder->update('tablename')
                ->where('id = '.$id);
}

$queryBuilder->values(['column1' => "'value1'"
                    , 'column2' => "'value2'"]);
```

select:
```php
$queryBuilder->select(['column1','column2','COUNT(column3) AS counter'])
            ->from('tablename','t')
            ->leftJoin('tablename2','t2','WITH','t2.id = t.id2')
            ->rightJoin('tablename3','t3','ON','t3.id = t.id3')
            ->join('tablename4','t4','WITH','t4.id = t.id4','LEFT OUTER')
            ->where('column1 = column2')
            ->andWhere('COUNT(column3) > 0')
            ->groupBy(['column1','column2'])
            ->having('column1 LIKE \'a\'')
            ->orderBy('counter','ASC');
```

build the sql:
```php
$sqlBuilder = new MySqlBuilder();
$queryConverter->validate($queryModel);
$sqlBuilder->buildQuery($queryModel);

print_r($sqlBuilder->getSql());
```

Tests
=====
to run the unit tests call
```js
phpunit --configuration [path/to/MysqliQueryBuilderBundle]/phpunit.xml --verbose --bootstrap=[path/to/your/autoload.php]
```
to run the functional tests, you must setup your database-configuration in the Tests/builder/DbQueryBuilderFunctionalDatabaseTest.php and remove the $this->markTestSkipped() row.