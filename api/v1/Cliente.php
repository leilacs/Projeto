<?php

class Cliente {
    var $login;
    var $nome;
    var $cpf;
    var $email;
    var $endereco;
    var $senha;


    function save($parametros = array()) {
    	//função para salvar um registro no banco de dados
        extract($parametros);

        require_once "ConnectionMySql.php";

        $db  = new ConnectionMysql();
        $res = $db->getResource();

        $sql = "
            INSERT INTO cliente VALUES (
                NULL, '$login', '$nome', '$cpf', '$email', '$endereco', '$senha'
            )
        ";
        $result = mysql_query($sql);

        return $result;
    }

    function get() {
    	//função para buscar registros
        require_once "ConnectionMySql.php";

        $db  = new ConnectionMysql();
        $res = $db->getResource();

        $sql = "
            SELECT *
            FROM cliente
        ";
        $result = mysql_query($sql);  
        $results_rows = array();

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            array_push($results_rows, $row);
        }

        return $results_rows;
    }

    function getPorId($id) {
        //função para buscar o registro de um cliente
        require_once "ConnectionMySql.php";

        $db  = new ConnectionMysql();
        $res = $db->getResource();

        $sql = "
            SELECT *
            FROM cliente
            WHERE cd_cliente = $id
        ";
        $result = mysql_query($sql);  
        $row = mysql_fetch_assoc($result);

        return $row;
    }

    function getClienteDaEmpresa($cdEmpresa) {
        //busca clientes vinculados a empresa
        require_once "ConnectionMySql.php";

        $db  = new ConnectionMysql();
        $res = $db->getResource();

        $sql = "
            SELECT 
                c.login, c.nome, c.cpf, c.email, c.endereco, c.senha
            FROM empresa_cliente ef
            LEFT JOIN cliente c ON (ef.cd_cliente = c.cd_cliente)
            WHERE ef.cd_empresa = $cdEmpresa
        ";
        $result = mysql_query($sql);  
        $results_rows = array();

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            array_push($results_rows, $row);
        }

        return $results_rows;
    }

    function updateCliente($parametros = array()) {
        //função para atualizar um registro no banco de dados
        extract($parametros);

        require_once "ConnectionMySql.php";

        $db  = new ConnectionMysql();
        $res = $db->getResource();

        $sql = "
            UPDATE cliente SET
                login ='$login', 
                nome = '$nome', 
                cpf = '$cpf', 
                email = '$email', 
                endereco = '$endereco', 
                senha = '$senha'
            WHERE cd_cliente = $id   
        ";
        $result = mysql_query($sql);

        return $result;
    }

    function delete($id) {
    	//função para excluir um registro do banco de dados
        require_once "ConnectionMySql.php";

        $db  = new ConnectionMysql();
        $res = $db->getResource();

        $sql = "
            DELETE FROM cliente
            WHERE cd_cliente = $id
        ";
        $result = mysql_query($sql);  
        
        return $result;
    }
}

?>
