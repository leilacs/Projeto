<?php

abstract class ApiBase {

    protected $method = '';
    protected $endpoint = '';
    protected $verb = '';
    protected $args = Array();
    protected $file = null;
    protected $data = null;

    public function __construct($request) {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        $this->args = explode('/', rtrim($request, '/'));
        $this->endpoint = array_shift($this->args);
        if (array_key_exists(0, $this->args) && !is_numeric($this->args[0])) {
            $this->verb = array_shift($this->args);
        }

        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if (in_array($_SERVER['HTTP_X_HTTP_METHOD'], array('DELETE', 'PUT'))) {
                $this->method = $_SERVER['HTTP_X_HTTP_METHOD'];
            } else {
                throw new Exception("Unexpected Header");
            }
        }

        switch($this->method) {
        case 'DELETE':
        case 'POST':
            $this->request = $this->_cleanInputs($_POST);
            $this->data = file_get_contents("php://input");
            break;
        case 'GET':
            $this->request = $this->_cleanInputs($_GET);
            break;
        case 'PUT':
            $this->request = $this->_cleanInputs($_GET);
            $this->file = file_get_contents("php://input");
            break;
        default:
            $this->_response('Invalid Method', 405);
            break;
        }
    }

    public function processAPI() {
        if (method_exists($this, $this->endpoint)) {
            list($output, $statusCode) = $this->{$this->endpoint}($this->args);
            return $this->_response($output, $statusCode);
        }
        return $this->_response("No Endpoint: $this->endpoint", 404);
    }

    private function _response($data, $status = 200) {
        header($_SERVER["SERVER_PROTOCOL"] . " $status " . $this->_requestStatus($status));
        return is_string($data) ? $data: Utils::json_encode($data);
    }

    private function _cleanInputs($data) {
        $clean_input = Array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->_cleanInputs($v);
            }
        } else {
            $clean_input = trim(strip_tags($data));
        }
        return $clean_input;
    }

    private function _requestStatus($code) {
        $status = array(
            200 => 'OK',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code])?$status[$code]:$status[500]; 
    }

}

?>