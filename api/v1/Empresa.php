<?php

class Empresa {
    var $nome;
    var $cnpj;
    var $endereco;


	function save($parametros = array()) {
		//função para salvar um registro no banco de dados
		extract($parametros);

        require_once "ConnectionMySql.php";

        $db  = new ConnectionMysql();
        $res = $db->getResource();

        $sql = "
            INSERT INTO empresa VALUES (
                NULL, '$nome', '$cnpj', '$endereco'
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
            FROM empresa
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
            FROM empresa
            WHERE cd_empresa = $id
        ";
        $result = mysql_query($sql);  
        $row = mysql_fetch_assoc($result);

        return $row;
    }

	function getEmpresaDoFuncionario($cdFuncionario) {
	    //busca as empresas do funcionario
	    require_once "ConnectionMySql.php";

        $db  = new ConnectionMysql();
        $res = $db->getResource();

        $sql = "
	    	SELECT 
                e.nome as nome_empresa,
                e.cnpj,
                e.endereco as endereco_empresa
            FROM empresa_funcionario ef
            LEFT JOIN empresa e ON (ef.cd_empresa = e.cd_empresa)
            WHERE cd_funcionario = $cdFuncionario
        ";
        $result = mysql_query($sql);  
        $results_rows = array();

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            array_push($results_rows, $row);
        }

        return $results_rows;
	}

	function getEmpresaDoCliente($cdCliente) {
	    //busca as empresas do cliente
	    require_once "ConnectionMySql.php";

        $db  = new ConnectionMysql();
        $res = $db->getResource();

        $sql = "
	    	SELECT 
                e.nome as nome_empresa,
                e.cnpj,
                e.endereco as endereco_empresa
            FROM empresa_cliente ef
            LEFT JOIN empresa e ON (ef.cd_empresa = e.cd_empresa)
            WHERE cd_cliente = $cdCliente
        ";
        $result = mysql_query($sql);  
        $results_rows = array();

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            array_push($results_rows, $row);
        }

        return $results_rows;
	}

	function updateEmpresa($parametros = array()) {
        //função para atualizar um registro no banco de dados
        extract($parametros);

        require_once "ConnectionMySql.php";

        $db  = new ConnectionMysql();
        $res = $db->getResource();

        $sql = "
            UPDATE empresa SET
                nome = '$nome', 
                cnpj = '$cnpj', 
                endereco = '$endereco'
            WHERE cd_empresa = $id   
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
            DELETE FROM empresa
            WHERE cd_empresa = $id
        ";
        $result = mysql_query($sql);  
        
        return $result;
    }
}

?>
