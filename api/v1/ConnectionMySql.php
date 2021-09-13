<?php

class ConnectionMysql {
    var $resource;

    function ConnectionMysql() {

        $dbconfig['db_server'] = '10.0.0.49';
        $dbconfig['db_port'] = '3306';
        $dbconfig['db_username'] = 'root';
        $dbconfig['db_password'] = 'leila';
        $dbconfig['db_name'] = 'root';

  
        if ( isset($dbconfig)
            && isset($dbconfig['db_server']) && !is_null($dbconfig['db_server'])
            && isset($dbconfig['db_username']) && !is_null($dbconfig['db_username'])
            && isset($dbconfig['db_password']) && !is_null($dbconfig['db_password'])
        ) {
            $this->resource = mysql_connect(
                $dbconfig['db_server'],
                $dbconfig['db_username'],
                $dbconfig['db_password']
            );
        } 

        $selected = mysql_select_db($dbconfig['db_name'], $this->resource);


    }

    /**
     * Retorna a conexão com o banco MySQL
     *
     * @return Resource
     */
    function getResource() {
        $this->resource;
    }
}