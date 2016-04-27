<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 18.04.2016
 * Time: 20:53
 */

namespace database\MySqlQueryBuilderBundle\sql;


use database\MySqlQueryBuilderBundle\model\QueryModel;

interface SqlBuilderInterface {
    public function buildQuery (QueryModel $model);

    public function getSql ();
}