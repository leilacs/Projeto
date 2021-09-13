<?php

include "Funcionario.php";
include "Cliente.php";
include "Empresa.php";
require_once 'ApiBase.php';

class Api extends ApiBase {

	public function __construct($request, $origin) {
		parent::__construct($request);
		$this->projetoToken = "1234";
    }

    private function ehTokenValido() {
        $headers = array_change_key_case(apache_request_headers(), CASE_UPPER);
        return isset($headers['AUTHORIZATION']) ? $this->projetoToken === $headers['AUTHORIZATION'] : false;
    }

    /**
    * @api {post} /createFuncionario createFuncionario
    * @apiName createFuncionario
    * @apiGroup Funcionario
    * @apiDescription Cria um novo registro de funcionário.
    *
    * @apiParam (Request body) {String} login    Login do funcionário.
    * @apiParam (Request body) {String} nome     Nome do funcionário.
    * @apiParam (Request body) {String} cpf      CPF do funcionário.
    * @apiParam (Request body) {String} email    Email do funcionário.
    * @apiParam (Request body) {String} endereco Endereço do funcionário.
    * @apiParam (Request body) {String} senha    Senha.
    * @apiContentType application/json
    *
    * @apiExample {curl} Example usage:
    *   curl -i -k -X POST \
    *   -H "Content-Type: application/json" -H "Authorization: <token>" \
    *   -d '{"login": <login>, "nome": <nome>, "cpf": <cpf>, "email": <email>, "endereco": <endereco>, "senha": <senha>}' \
    *   https://intranet.localhost/api/v1/createFuncionario
    */
    protected function createFuncionario() {
        if ($this->ehTokenValido()) {
            $data = json_decode($this->data);

            if (empty($data->login) || empty($data->nome) || empty($data->cpf) || empty($data->email) || empty($data->endereco) || empty($data->senha)) {
                return array(
                    'Campos obrigatórios sem preenchimento.',
                    200
                );
            }

            $result = Funcionario::save(array(
                'login' => $data->login,
                'nome' => $data->nome,
                'cpf' => $data->cpf,
                'email' => $data->email,
                'endereco' => $data->endereco,
                'senha' => $data->senha
            ));

            if ($result) {
                return array(
                    array(
                        'result' => $result
                    ),
                    200
                );
            } else {
                return array(
                    array(
                        'message' => 'Funcionário não encontrado!'
                    ),
                    401
                );
            }
        } else {
            return array(
                array(
                    'message' => 'Unauthorized'
                ),
                401
            );
        }
    }

    /**
    * @api {post} /getAllFuncionarios getAllFuncionarios
    * @apiName getAllFuncionarios
    * @apiDescription Busca todos os funcionários da base de dados.
    *
    * @apiExample {curl} Example usage:
    *   curl -i -k -X POST \
    *   -H "Content-Type: application/json" -H "Authorization: <token>" \
    *   https://intranet.localhost/testeProjeto/v1/getAllFuncionarios
    */
    public function getAllFuncionarios() {
        if ($this->ehTokenValido()) {
	        $funcionarios = Funcionario::get();

	        if ($funcionarios) {
	            return array(
	                array(
	                    'result' => $funcionarios
	                ),
	                200
	            );
	        } else {
	            return array(
	                array(
	                    'message' => 'Não há funcionários para listar!'
	                ),
	                401
	            );
	        }
	    } else {
            return array(
                array(
                    'message' => 'Unauthorized'
                ),
                401
            );
        }
    }

    /**
    * @api {post} /getFuncionario getFuncionario
    * @apiName getFuncionario
    * @apiGroup Funcionario
    * @apiDescription Busca um funcionário.
    *
    * @apiParam (Request body) {int} id  Id do funcionário.
    * @apiContentType application/json
    *
    * @apiExample {curl} Example usage:
    *   curl -i -k -X POST \
    *   -H "Content-Type: application/json" -H "Authorization: <token>" \
    *   -d '{"id": <id>}' \
    *   https://intranet.localhost/api/v1/getFuncionario
    */
    public function getFuncionario() {
    	if ($this->ehTokenValido()) {
	        $data = json_decode($this->data);
	        
	        $funcionario = Funcionario::getPorId($data->id);
	        if ($funcionario) {

	            $empresa = Empresa::getEmpresaDoFuncionario($data->id);
                $funcionario['EMPRESA'] = $empresa;

	            return array(
	                array(
	                    'result' => $funcionario
	                ),
	                200
	            );
	        } else {
	            return array(
	                array(
	                    'message' => 'Funcionário não cadastrado!'
	                ),
	                401
	            );
	        }
	    } else {
            return array(
                array(
                    'message' => 'Unauthorized'
                ),
                401
            );
        }
    }

    /**
    * @api {post} /updateFuncionario updateFuncionario
    * @apiName updateFuncionario
    * @apiGroup Funcionario
    * @apiDescription Atualiza um funcionário.
    *
    * @apiParam (Request body) {int}    id       Id do funcionário.
    * @apiParam (Request body) {String} login    Login do funcionário.
    * @apiParam (Request body) {String} nome     Nome do funcionário.
    * @apiParam (Request body) {String} cpf      CPF do funcionário.
    * @apiParam (Request body) {String} email    Email do funcionário.
    * @apiParam (Request body) {String} endereco Endereço do funcionário.
    * @apiParam (Request body) {String} senha    Senha.
    * @apiContentType application/json
    *
    * @apiExample {curl} Example usage:
    *   curl -i -k -X POST \
    *   -H "Content-Type: application/json" -H "Authorization: <token>" \
    *   -d '{"id": <id>, login": <login>, "nome": <nome>, "cpf": <cpf>, "email": <email>, "endereco": <endereco>, "senha": <senha>}' \
    *   https://intranet.localhost/api/v1/updateFuncionario
    */
    public function updateFuncionario() {
    	if ($this->ehTokenValido()) {
	        $data = json_decode($this->data);

	        $funcionario = Funcionario::getPorId($data->id);
	        if ($funcionario) {

	            $result = Funcionario::updateFuncionario(array(
	                'id' => $data->id,
	                'login' => $data->login,
	                'nome' => $data->nome,
	                'cpf' => $data->cpf,
	                'email' => $data->email,
	                'endereco' => $data->endereco,
	                'senha' => $data->senha
	            ));

	            if ($result) {
	                return array(
	                    array(
	                        'result' => $result
	                    ),
	                    200
	                );
	            } else {
	                return array(
	                    array(
	                        'message' => 'Erro ao atualizar o funcionário!'
	                    ),
	                    401
	                );
	            }
	        } else {
	            return array(
	                array(
	                    'message' => 'Funcionário não encontrado!'
	                ),
	                401
	            );
	        }
	    } else {
            return array(
                array(
                    'message' => 'Unauthorized'
                ),
                401
            );
        }
    }

    /**
    * @api {post} /deleteFuncionario deleteFuncionario
    * @apiName deleteFuncionario
    * @apiGroup Funcionario
    * @apiDescription Exclui um funcionário.
    *
    * @apiParam (Request body) {int} id  Id do funcionário.
    * @apiContentType application/json
    *
    * @apiExample {curl} Example usage:
    *   curl -i -k -X POST \
    *   -H "Content-Type: application/json" -H "Authorization: <token>" \
    *   -d '{"id": <id>}' \
    *   https://intranet.localhost/api/v1/deleteFuncionario
    */
    public function deleteFuncionario () {
    	if ($this->ehTokenValido()) {
	        $data = json_decode($this->data);
	        
	        $funcionario = Funcionario::getPorId($data->id);
	        if ($funcionario) {
	            $result = Funcionario::delete($data->id);

	            return array(
	                array(
	                    'result' => $result
	                ),
	                200
	            );
	        } else {
	            return array(
	                array(
	                    'message' => 'Funcionário não encontrado!'
	                ),
	                401
	            );
	        }
	    } else {
            return array(
                array(
                    'message' => 'Unauthorized'
                ),
                401
            );
        }
    }

	/**
    * @api {post} /createCliente createCliente
    * @apiName createCliente
    * @apiGroup Cliente
    * @apiDescription Cria um novo registro de cliente.
    *
    * @apiParam (Request body) {String} login    Usuário.
    * @apiParam (Request body) {String} nome     Nome do cliente.
    * @apiParam (Request body) {String} cpf      CPF do cliente.
    * @apiParam (Request body) {String} email    Email do cliente.
    * @apiParam (Request body) {String} endereco Endereço do cliente.
    * @apiParam (Request body) {String} senha    Senha.
    * @apiContentType application/json
    *
    * @apiExample {curl} Example usage:
    *   curl -i -k -X POST \
    *   -H "Content-Type: application/json" -H "Authorization: <token>" \
    *   -d '{"login": <login>, "nome": <nome>, "cpf": <cpf>, "email": <email>, "endereco": <endereco>, "senha": <senha>}' \
    *   https://intranet.localhost/api/v1/createCliente
    */
    protected function createCliente() {
        if ($this->ehTokenValido()) {
	        $data = json_decode($this->data);

	        if (empty($data->login) || empty($data->nome) || empty($data->cpf) || empty($data->email) || empty($data->endereco) || empty($data->senha)) {
	            return array(
	                'Campos obrigatórios sem preenchimento.',
	                200
	            );
	        }

	        $result = Cliente::save(array(
	            'login' => $data->login,
	            'nome' => $data->nome,
	            'cpf' => $data->cpf,
	            'email' => $data->email,
	            'endereco' => $data->endereco,
	            'senha' => $data->senha
	        ));

	        if ($result) {
	            return array(
	                array(
	                    'result' => $result
	                ),
	                200
	            );
	        } else {
	            return array(
	                array(
	                    'message' => 'Cliente não encontrado!'
	                ),
	                401
	            );
	        }
	    } else {
            return array(
                array(
                    'message' => 'Unauthorized'
                ),
                401
            );
        }
    }

	/**
    * @api {post} /getAllClientes getAllClientes
    * @apiName getAllClientes
    * @apiDescription Busca todos os clientes da base de dados.
    *
    * @apiExample {curl} Example usage:
    *   curl -i -k -X POST \
    *   -H "Content-Type: application/json" -H "Authorization: <token>" \
    *   https://intranet.localhost/testeProjeto/v1/getAllClientes
    */
    public function getAllClientes() {
        if ($this->ehTokenValido()) {
	        $clientes = Cliente::get();

	        if ($clientes) {
	            return array(
	                array(
	                    'result' => $clientes
	                ),
	                200
	            );
	        } else {
	            return array(
	                array(
	                    'message' => 'Não há clientes para listar!'
	                ),
	                401
	            );
	        }
	    } else {
            return array(
                array(
                    'message' => 'Unauthorized'
                ),
                401
            );
        }
    }

    /**
    * @api {post} /getCliente getCliente
    * @apiName getCliente
    * @apiGroup Cliente
    * @apiDescription Busca um cliente.
    *
    * @apiParam (Request body) {int} id  Id do cliente.
    * @apiContentType application/json
    *
    * @apiExample {curl} Example usage:
    *   curl -i -k -X POST \
    *   -H "Content-Type: application/json" -H "Authorization: <token>" \
    *   -d '{"id": <id>}' \
    *   https://intranet.localhost/api/v1/getCliente
    */
    public function getCliente() {
    	if ($this->ehTokenValido()) {
	        $data = json_decode($this->data);
	        
	        $cliente = Cliente::getPorId($data->id);
	        if ($cliente) {

	            $empresa = Empresa::getEmpresaDoCliente($data->id);
                $cliente['EMPRESA'] = $empresa;

	            return array(
	                array(
	                    'result' => $cliente
	                ),
	                200
	            );
	        } else {
	            return array(
	                array(
	                    'message' => 'Cliente não cadastrado!'
	                ),
	                401
	            );
	        }
	    } else {
            return array(
                array(
                    'message' => 'Unauthorized'
                ),
                401
            );
        }
    }

    /**
    * @api {post} /updateCliente updateCliente
    * @apiName updateCliente
    * @apiGroup Cliente
    * @apiDescription Atualiza um cliente.
    *
    * @apiParam (Request body) {int}    id       Id do cliente.
    * @apiParam (Request body) {String} login    Usuário.
    * @apiParam (Request body) {String} nome     Nome do cliente.
    * @apiParam (Request body) {String} cpf      CPF do cliente.
    * @apiParam (Request body) {String} email    Email do cliente.
    * @apiParam (Request body) {String} endereco Endereço do cliente.
    * @apiParam (Request body) {String} senha    Senha.
    * @apiContentType application/json
    *
    * @apiExample {curl} Example usage:
    *   curl -i -k -X POST \
    *   -H "Content-Type: application/json" -H "Authorization: <token>" \
    *   -d '{"id": <id>, login": <login>, "nome": <nome>, "cpf": <cpf>, "email": <email>, "endereco": <endereco>, "senha": <senha>}' \
    *   https://intranet.localhost/api/v1/updateCliente
    */
    public function updateCliente() {
    	if ($this->ehTokenValido()) {
	        $data = json_decode($this->data);

	        $cliente = Cliente::getPorId($data->id);
	        if ($cliente) {

	            $result = Cliente::updateCliente(array(
	                'id' => $data->id,
	                'login' => $data->login,
	                'nome' => $data->nome,
	                'cpf' => $data->cpf,
	                'email' => $data->email,
	                'endereco' => $data->endereco,
	                'senha' => $data->senha
	            ));

	            if ($result) {
	                return array(
	                    array(
	                        'result' => $result
	                    ),
	                    200
	                );
	            } else {
	                return array(
	                    array(
	                        'message' => 'Erro ao atualizar o cliente!'
	                    ),
	                    401
	                );
	            }
	        } else {
	            return array(
	                array(
	                    'message' => 'Cliente não encontrado!'
	                ),
	                401
	            );
	        }
	    } else {
            return array(
                array(
                    'message' => 'Unauthorized'
                ),
                401
            );
        }
    }

    /**
    * @api {post} /deleteCliente deleteCliente
    * @apiName deleteCliente
    * @apiGroup Cliente
    * @apiDescription Exclui um cliente.
    *
    * @apiParam (Request body) {int} id  Id do cliente.
    * @apiContentType application/json
    *
    * @apiExample {curl} Example usage:
    *   curl -i -k -X POST \
    *   -H "Content-Type: application/json" -H "Authorization: <token>" \
    *   -d '{"id": <id>}' \
    *   https://intranet.localhost/api/v1/deleteCliente
    */
    public function deleteCliente () {
    	if ($this->ehTokenValido()) {
	        $data = json_decode($this->data);
	        
	        $cliente = Cliente::getPorId($data->id);
	        if ($cliente) {
	            $result = Cliente::delete($data->id);

	            return array(
	                array(
	                    'result' => $result
	                ),
	                200
	            );
	        } else {
	            return array(
	                array(
	                    'message' => 'Cliente não encontrado!'
	                ),
	                401
	            );
	        }
	    } else {
            return array(
                array(
                    'message' => 'Unauthorized'
                ),
                401
            );
        }
    }

    /**
    * @api {post} /createEmpresa createEmpresa
    * @apiName createEmpresa
    * @apiGroup Empresa
    * @apiDescription Cria um novo registro de empresa.
    *
    * @apiParam (Request body) {String} nome     Nome da empresa.
    * @apiParam (Request body) {String} cnpj     CNPJ da empresa.
    * @apiParam (Request body) {String} endereco Endereço da empresa.
    * @apiContentType application/json
    *
    * @apiExample {curl} Example usage:
    *   curl -i -k -X POST \
    *   -H "Content-Type: application/json" -H "Authorization: <token>" \
    *   -d '{"nome": <nome>, "cnpj": <cnpj>, "endereco": <endereco>}' \
    *   https://intranet.localhost/api/v1/createEmpresa
    */
    protected function createEmpresa() {
        if ($this->ehTokenValido()) {
	        $data = json_decode($this->data);

	        if (empty($data->nome) || empty($data->cnpj) || empty($data->endereco)) {
	            return array(
	                'Campos obrigatórios sem preenchimento.',
	                200
	            );
	        }

	        $result = Empresa::save(array(
	            'nome' => $data->nome,
	            'cnpj' => $data->cnpj,
	            'endereco' => $data->endereco
	        ));

	        if ($result) {
	            return array(
	                array(
	                    'result' => $result
	                ),
	                200
	            );
	        } else {
	            return array(
	                array(
	                    'message' => 'Empresa não encontrada!'
	                ),
	                401
	            );
	        }
	    } else {
            return array(
                array(
                    'message' => 'Unauthorized'
                ),
                401
            );
        }
    }

    /**
    * @api {post} /getAllEmpresas getAllEmpresas
    * @apiName getAllEmpresas
    * @apiDescription Busca todos as empresas da base de dados.
    *
    * @apiExample {curl} Example usage:
    *   curl -i -k -X POST \
    *   -H "Content-Type: application/json" -H "Authorization: <token>" \
    *   https://intranet.localhost/api/v1/getAllEmpresas
    */
    public function getAllEmpresas() {
        if ($this->ehTokenValido()) {
	        $empresas = Empresa::get();

	        if ($empresas) {
	            return array(
	                array(
	                    'result' => $empresas
	                ),
	                200
	            );
	        } else {
	            return array(
	                array(
	                    'message' => 'Não há empresas para listar!'
	                ),
	                401
	            );
	        }
	    } else {
            return array(
                array(
                    'message' => 'Unauthorized'
                ),
                401
            );
        }
    }

    /**
    * @api {post} /getEmpresa getEmpresa
    * @apiName getEmpresa
    * @apiGroup Empresa
    * @apiDescription Busca uma empresa.
    *
    * @apiParam (Request body) {int} id  Id da empresa.
    * @apiContentType application/json
    *
    * @apiExample {curl} Example usage:
    *   curl -i -k -X POST \
    *   -H "Content-Type: application/json" -H "Authorization: <token>" \
    *   -d '{"id": <id>}' \
    *   https://intranet.localhost/api/v1/getEmpresa
    */
    public function getEmpresa() {
    	if ($this->ehTokenValido()) {
	        $data = json_decode($this->data);
	        
	        $empresa = Empresa::getPorId($data->id);
	        if ($empresa) {

	            $cliente = Cliente::getClienteDaEmpresa($data->id);
                $empresa['CLIENTE'] = $cliente;
                $funcionario = Funcionario::getFuncionarioDaEmpresa($data->id);
                $empresa['FUNCIONARIO'] = $funcionario;

	            return array(
	                array(
	                    'result' => $empresa
	                ),
	                200
	            );
	        } else {
	            return array(
	                array(
	                    'message' => 'Empresa não cadastrada!'
	                ),
	                401
	            );
	        }
	    } else {
            return array(
                array(
                    'message' => 'Unauthorized'
                ),
                401
            );
        }
    }

    /**
    * @api {post} /updateEmpresa updateEmpresa
    * @apiName updateEmpresa
    * @apiGroup Empresa
    * @apiDescription Atualiza uma empresa.
    *
    * @apiParam (Request body) {int}    id       Id da empresa.
    * @apiParam (Request body) {String} nome     Nome da empresa.
    * @apiParam (Request body) {String} cnpj     CNPJ da empresa.
    * @apiParam (Request body) {String} endereco Endereço da empresa.
    * @apiContentType application/json
    *
    * @apiExample {curl} Example usage:
    *   curl -i -k -X POST \
    *   -H "Content-Type: application/json" -H "Authorization: <token>" \
    *   -d '{"id": <id>, "nome": <nome>, "cnpj": <cnpj>, "endereco": <endereco>}' \
    *   https://intranet.localhost/api/v1/updateEmpresa
    */
    public function updateEmpresa() {
    	if ($this->ehTokenValido()) {
	        $data = json_decode($this->data);

	        $empresa = Empresa::getPorId($data->id);
	        if ($empresa) {

	            $result = Empresa::updateEmpresa(array(
	                'id' => $data->id,
	                'nome' => $data->nome,
	                'cnpj' => $data->cnpj,
	                'endereco' => $data->endereco	                
	            ));

	            if ($result) {
	                return array(
	                    array(
	                        'result' => $result
	                    ),
	                    200
	                );
	            } else {
	                return array(
	                    array(
	                        'message' => 'Erro ao atualizar a empresa!'
	                    ),
	                    401
	                );
	            }
	        } else {
	            return array(
	                array(
	                    'message' => 'Empresa não encontrada!'
	                ),
	                401
	            );
	        }
	    } else {
            return array(
                array(
                    'message' => 'Unauthorized'
                ),
                401
            );
        }
    }

    /**
    * @api {post} /deleteEmpresa deleteEmpresa
    * @apiName deleteEmpresa
    * @apiGroup Empresa
    * @apiDescription Exclui uma empresa.
    *
    * @apiParam (Request body) {int} id  Id da empresa.
    * @apiContentType application/json
    *
    * @apiExample {curl} Example usage:
    *   curl -i -k -X POST \
    *   -H "Content-Type: application/json" -H "Authorization: <token>" \
    *   -d '{"id": <id>}' \
    *   https://intranet.localhost/api/v1/deleteEmpresa
    */
    public function deleteEmpresa () {
    	if ($this->ehTokenValido()) {
	        $data = json_decode($this->data);
	        
	        $empresa = Empresa::getPorId($data->id);
	        if ($empresa) {
	            $result = Empresa::delete($data->id);

	            return array(
	                array(
	                    'result' => $result
	                ),
	                200
	            );
	        } else {
	            return array(
	                array(
	                    'message' => 'Empresa não encontrada!'
	                ),
	                401
	            );
	        }
	    } else {
            return array(
                array(
                    'message' => 'Unauthorized'
                ),
                401
            );
        }
    }

    /**
    * @api {post} /geraDocumentoFuncionario geraDocumentoFuncionario
    * @apiName geraDocumentoFuncionario
    * @apiGroup Funcionario
    * @apiDescription Gera documento de identificação do funcionário.
    *
    * @apiParam (Request body) {int} id  Id do funcionário.
    * @apiContentType application/json
    *
    * @apiExample {curl} Example usage:
    *   curl -i -k -X POST \
    *   -H "Content-Type: application/json" -H "Authorization: <token>" \
    *   -d '{"id": <id>}' \
    *   https://intranet.localhost/api/v1/geraDocumentoFuncionario
    */
    public function geraDocumentoFuncionario() {
        if ($this->ehTokenValido()) {
            $data = json_decode($this->data);

            $funcionario = $this->getFuncionario($data->id);
            if ($funcionario) {
                
                // Define HTML que serah apresentado --------------------------------------------------
                $html = '
                <html>
                <style>
                body {
                  font-family: Helvetica, Arial, sans-serif;
                  font-size: 12pt;
                  color: #333333;
                  background-color: #fff;
                }
                .text-left { text-align: left;}
                .text-right { text-align: right;}
                .text-center { text-align: center;}
                .text-justify { text-align: justify;}
                .step-heading{
                    background-color:#0b3b64; 
                    color: #fff;
                }
                .resume-heading{
                  border-bottom: 1px solid #0b3b64;
                  border-top: 1px solid #0b3b64;
                }
                .bg-grey{background-color: #c5c5c5;}
                .border-bottom{  border-bottom: 1px solid #000;}
                .border-top{border-top: 1px solid #000;padding: 5px;}
                .td-titulo-resultado{ border-right: 1px solid #ccc;padding: 10px;font-weight:bolder;}
                .td-resultado{ padding: 10px;}
                </style>
                <body>

                  <table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td class="resume-heading" colspan="2"><strong>FUNCIONARIO</strong></td>
                    </tr>
                    <tr>
                      <td class="resume-heading"><strong>Login</strong></td>
                      <td class="resume-heading"><strong>'.$funcionario[0]['result']['LOGIN'].'</strong></td>
                    </tr>
                    <tr>
                      <td class="resume-heading"><strong>Nome</strong></td>
                      <td class="resume-heading"><strong>'.$funcionario[0]['result']['NOME'].'</strong></td>
                    </tr>
                    <tr>
                      <td class="resume-heading"><strong>CPF</strong></td>
                      <td class="resume-heading"><strong>'.$funcionario[0]['result']['CPF'].'</strong></td>
                    </tr>
                    <tr>
                      <td class="resume-heading"><strong>E-mail</strong></td>
                      <td class="resume-heading"><strong>'.$funcionario[0]['result']['EMAIL'].'</strong></td>
                    </tr>
                    <tr>
                      <td class="resume-heading"><strong>Endereço</strong></td>
                      <td class="resume-heading"><strong>'.$funcionario[0]['result']['ENDERECO'].'</strong></td>
                    </tr>
                    <tr>
                      <td class="resume-heading"><strong>Senha</strong></td>
                      <td class="resume-heading"><strong>'.$funcionario[0]['result']['SENHA'].'</strong></td>
                    </tr>
                  </table><br>
                  
                  <table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td class="resume-heading" colspan="2"><strong>EMPRESA(s)</strong></td>
                    </tr> ';

                foreach ($funcionario[0]['result']['EMPRESA'] as $key => $value) {
                    $html .= '
                    <tr>
                      <td class="resume-heading"><strong>Nome</strong></td>
                      <td class="resume-heading"><strong>'.$value['nome_empresa'].'</strong></td>
                    </tr>
                    <tr>
                      <td class="resume-heading"><strong>CNPJ</strong></td>
                      <td class="resume-heading"><strong>'.$value['cnpj'].'</strong></td>
                    </tr>
                    <tr>
                      <td class="resume-heading"><strong>Endereço</strong></td>
                      <td class="resume-heading"><strong>'.$value['endereco_empresa'].'</strong></td>
                    </tr>';    
                }

                $html .= '
                  <br>
                </body></html>
                ';

                include 'geraPdf.php';

                $pdf = new geraPDF;

                $pdf->setComando('%s --webpage -t pdf14 --compression=1 --jpeg=50 --size A4 -f %s  --left 2cm --right 1cm --header .rD. --headfootsize 7.0  --footer .r1. %s');
                $pdf->setArquivo("identificacao_funcionario");
                $pdf->criaPDF($html);

                $result = true;
                
                return array(
                    array(
                        'result' => $result
                    ),
                    200
                );
            } else {
                return array(
                    array(
                        'message' => 'Funcionário não cadastrado!'
                    ),
                    401
                );
            }

        } else {
            return array(
                array(
                    'message' => 'Unauthorized'
                ),
                401
            );
        }
    }

}
?>
