<?php

class Funcionario {
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
            INSERT INTO funcionario VALUES (
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
            FROM funcionario
        ";
        $result = mysql_query($sql);  
        $results_rows = array();

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            array_push($results_rows, $row);
        }

        return $results_rows;
    }

    function getPorId($id) {
        //função para buscar o registro de um funcionário
        require_once "ConnectionMySql.php";

        $db  = new ConnectionMysql();
        $res = $db->getResource();

        $sql = "
            SELECT *
            FROM funcionario
            WHERE cd_funcionario = $id
        ";
        $result = mysql_query($sql);  
        $row = mysql_fetch_assoc($result);

        return $row;
    }

    function getFuncionarioDaEmpresa($cdEmpresa) {
        //busca clientes vinculados a empresa
        require_once "ConnectionMySql.php";

        $db  = new ConnectionMysql();
        $res = $db->getResource();

        $sql = "
            SELECT 
                f.login, f.nome, f.cpf, f.email, f.endereco, f.senha
            FROM empresa_funcionario ef
            LEFT JOIN funcionario f ON (ef.cd_funcionario = f.cd_funcionario)
            WHERE ef.cd_empresa = $cdEmpresa
        ";
        $result = mysql_query($sql);  
        $results_rows = array();

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            array_push($results_rows, $row);
        }

        return $results_rows;
    }

    function updateFuncionario($parametros = array()) {
        //função para atualizar um registro no banco de dados
        extract($parametros);

        require_once "ConnectionMySql.php";

        $db  = new ConnectionMysql();
        $res = $db->getResource();

        $sql = "
            UPDATE funcionario SET
                login ='$login', 
                nome = '$nome', 
                cpf = '$cpf', 
                email = '$email', 
                endereco = '$endereco', 
                senha = '$senha'
            WHERE cd_funcionario = $id   
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
            DELETE FROM funcionario
            WHERE cd_funcionario = $id
        ";
        $result = mysql_query($sql);  
        
        return $result;
    }
}

?>
