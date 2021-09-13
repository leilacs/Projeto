<?php
require_once 'api/utils/vendor/autoload.php';

class ApiTest extends PHPUnit_Framework_TestCase {

    protected $api_base = 'https://localhost';
    protected $headers = array('content-type' => 'application/json');
    protected $options = array('debug' => false, 'verify' => false, 'exceptions' => false);
    protected $client = null;
    protected $username_valido = 'ze@empresa.com.br';
    protected $username_invalido = 'username@invalido.com.br';
    protected $password_valido = '02e56c6c9c70119a2b34fb142307e1df1830db7aed8edcbcfe05857817051e7c82cc8077168cde36a86c15461e06262a7f176e5659d66aadd60397f7f3310b8b';

    protected function echoObj($obj) {
        fwrite(STDERR, print_r($obj, TRUE));
    }

    protected function autentica($data) {
        return $this->client->post(
            '/api/v1/autentica',
            $this->headers,
            json_encode($data),
            $this->options
        )->send();
    }

    protected function setUp() {
        $this->client = new Guzzle\Http\Client($this->api_base);
    }

    public function testAutenticaCredenciaisIncorretas() {

        $response = $this->autentica(
            array(
                'username' => $this->username_valido,
                'password' => '1234567890'
            )
        );

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testAutenticaCredenciaisCorretas() {

        $response = $this->autentica(
            array(
                'username' => $this->username_valido,
                'password' => $this->password_valido
            )
        );

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testVerificaTokenInvalido() {

        $headers = $this->headers;
        $headers['Authorization'] = '1234567890';

        $response = $this->client->post(
            '/api/v1/verificaToken',
            $headers,
            null,
            $this->options
        )->send();

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testVerificaTokenValido() {

        $data = array(
            'username' => $this->username_valido,
            'password' => $this->password_valido
        );

        $response = $this->autentica($data);
        $obj = json_decode($response->getBody(true), true);

        $headers = $this->headers;
        $headers['Authorization'] = $obj['token'];

        $response = $this->client->post(
            '/api/v1/verificaToken',
            $headers,
            null,
            $this->options
        )->send();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testVerificaUsuarioInvalido() {

        $data = array(
            'username' => $this->username_invalido
        );

        $response = $this->client->post(
            '/api/v1/verificaUsuario',
            $this->headers,
            json_encode($data),
            $this->options
        )->send();

        $obj = json_decode($response->getBody(true), true);

        $this->assertEquals(false, $obj['result']);
    }

    public function testVerificaUsuarioValido() {

        $data = array(
            'username' => $this->username_valido
        );

        $response = $this->client->post(
            '/api/v1/verificaUsuario',
            $this->headers,
            json_encode($data),
            $this->options
        )->send();

        $obj = json_decode($response->getBody(true), true);

        $this->assertEquals(true, $obj['result']);
    }

    public function testBuscaNatureza() {

        $data = array(
            'username' => $this->username_valido,
            'password' => $this->password_valido
        );

        $response = $this->autentica($data);
        $obj = json_decode($response->getBody(true), true);

        $headers = $this->headers;
        $headers['Authorization'] = $obj['token'];

        $response = $this->client->post(
            '/api/v1/buscaNatureza',
            $headers,
            null,
            $this->options
        )->send();

        $retornoEsperado = array('CD_NATUREZA', 'NM_NATUREZA', 'CD_TIPO_PROBLEMA');

        $this->assertTrue(self::comparaValores($retornoEsperado, $response));
    }

    public function testBuscaPais() {

        $data = array(
            'username' => $this->username_valido,
            'password' => $this->password_valido
        );

        $response = $this->autentica($data);
        $obj = json_decode($response->getBody(true), true);

        $headers = $this->headers;
        $headers['Authorization'] = $obj['token'];

        $response = $this->client->post(
            '/api/v1/buscaPais',
            $headers,
            null,
            $this->options
        )->send();

        $retornoEsperado = array('CD_PAIS', 'NM_PAIS');

        $this->assertTrue(self::comparaValores($retornoEsperado, $response));
    }

    public function testBuscaRelacionamento() {

        $data = array(
            'username' => $this->username_valido,
            'password' => $this->password_valido
        );

        $response = $this->autentica($data);
        $obj = json_decode($response->getBody(true), true);

        $headers = $this->headers;
        $headers['Authorization'] = $obj['token'];

        $response = $this->client->post(
            '/api/v1/buscaRelacionamento',
            $headers,
            null,
            $this->options
        )->send();

        $retornoEsperado = array('CD_TIPO_RELACIONAMENTO', 'NM_TIPO_RELACIONAMENTO', 'ID_EXIBE_PORTAL_CLIENTE');

        $this->assertTrue(self::comparaValores($retornoEsperado, $response));
    }

    public function testBuscaTiposComunicacao() {

        $data = array(
            'username' => $this->username_valido,
            'password' => $this->password_valido
        );

        $response = $this->autentica($data);
        $obj = json_decode($response->getBody(true), true);

        $headers = $this->headers;
        $headers['Authorization'] = $obj['token'];

        $response = $this->client->post(
            '/api/v1/buscaTiposComunicacao',
            $headers,
            null,
            $this->options
        )->send();

        $retornoEsperado = array('CD_TIPO_COMUNICACAO', 'NM_TIPO_COMUNICACAO', 'ID_TIPO_COMUNICACAO');

        $this->assertTrue(self::comparaValores($retornoEsperado, $response));
    }

    public function testBuscaTiposDocumento() {

        $data = array(
            'username' => $this->username_valido,
            'password' => $this->password_valido
        );

        $response = $this->autentica($data);
        $obj = json_decode($response->getBody(true), true);

        $headers = $this->headers;
        $headers['Authorization'] = $obj['token'];

        $response = $this->client->post(
            '/api/v1/buscaTiposDocumento',
            $headers,
            null,
            $this->options
        )->send();

        $retornoEsperado = array('CD_TIPO_DOC', 'NM_TIPO_DOC');

        $this->assertTrue(self::comparaValores($retornoEsperado, $response));
    }

    public function testBuscaAreasCargos() {

        $data = array(
            'username' => $this->username_valido,
            'password' => $this->password_valido
        );

        $response = $this->autentica($data);
        $obj = json_decode($response->getBody(true), true);

        $headers = $this->headers;
        $headers['Authorization'] = $obj['token'];

        $response = $this->client->post(
            '/api/v1/buscaAreasCargos',
            $headers,
            null,
            $this->options
        )->send();

        $retornoEsperado = array('CD_CARGO_AREA', 'NM_CARGO_AREA');

        $this->assertTrue(self::comparaValores($retornoEsperado, $response));
    }

    public function testBuscaNiveisCargos() {

        $data = array(
            'username' => $this->username_valido,
            'password' => $this->password_valido
        );

        $response = $this->autentica($data);
        $obj = json_decode($response->getBody(true), true);

        $headers = $this->headers;
        $headers['Authorization'] = $obj['token'];

        $response = $this->client->post(
            '/api/v1/buscaNiveisCargos',
            $headers,
            null,
            $this->options
        )->send();

        $retornoEsperado = array('CD_CARGO_NIVEL', 'NM_CARGO_NIVEL');

        $this->assertTrue(self::comparaValores($retornoEsperado, $response));
    }

    public function testBuscaGrupoConta() {

        $data = array(
            'username' => $this->username_valido,
            'password' => $this->password_valido
        );

        $response = $this->autentica($data);
        $obj = json_decode($response->getBody(true), true);

        $headers = $this->headers;
        $headers['Authorization'] = $obj['token'];

        $data = array(
            'cdConta' => 6
        );

        $response = $this->client->post(
            '/api/v1/buscaGrupoConta',
            $headers,
            json_encode($data),
            $this->options
        )->send();

        $retornoEsperado = array('CD_CONTA', 'CD_MATRIZ', 'CD_EMITENTE', 'CD_PESSOA', 'RESTRITO');

        $this->assertTrue(self::comparaValores($retornoEsperado, $response));
    }

    public function testListaContatos() {

        $data = array(
            'username' => $this->username_valido,
            'password' => $this->password_valido
        );

        $response = $this->autentica($data);
        $obj = json_decode($response->getBody(true), true);

        $headers = $this->headers;
        $headers['Authorization'] = $obj['token'];

        $data = array(
            'cdConta' => 6
        );

        $response = $this->client->post(
            '/api/v1/listaContatos',
            $headers,
            json_encode($data),
            $this->options
        )->send();

        $retornoEsperado = array('CD_CONTATO', 'NM_CONTATO', 'NM_CARGO', 'NM_CARGO_AREA', 'NR_NIT', 'CD_TIPO_DOC', 'NR_FONE', 'NR_INTERNACIONAL', 'DE_EMAIL', 'CD_USUARIO', 'NR_RAMAL');

        $this->assertTrue(self::comparaValores($retornoEsperado, $response));
    }

    public function testClassificacaoRac() {

        $data = array(
            'username' => $this->username_valido,
            'password' => $this->password_valido
        );

        $response = $this->autentica($data);
        $obj = json_decode($response->getBody(true), true);

        $headers = $this->headers;
        $headers['Authorization'] = $obj['token'];

        $response = $this->client->post(
            '/api/v1/buscaClassificacaoRac',
            $headers,
            null,
            $this->options
        )->send();

        $retornoEsperado = array('CD_TIPO_PRIORIDADE', 'DE_ABREV_TIPO_PRIORIDADE');

        $this->assertTrue(self::comparaValores($retornoEsperado, $response));
    }

    public function testBuscaSituacaoRac() {

        $data = array(
            'username' => $this->username_valido,
            'password' => $this->password_valido
        );

        $response = $this->autentica($data);
        $obj = json_decode($response->getBody(true), true);

        $headers = $this->headers;
        $headers['Authorization'] = $obj['token'];

        $response = $this->client->post(
            '/api/v1/buscaSituacaoRac',
            $headers,
            null,
            $this->options
        )->send();

        $retornoEsperado = array('CD_SITUACAO', 'ID_SITUACAO', 'DE_ABREV_SITUACAO');
        
        $this->assertTrue(self::comparaValores($retornoEsperado, $response));
    }

    function comparaValores($retornoEsperado, $response) {
        $obj = json_decode($response->getBody(true), true);

        if (is_array($obj['result'][0])) {
            $retornoObtido = array_keys($obj['result'][0]);
            return $retornoEsperado == $retornoObtido;
        } else {
            return false;
        }
    }

}
?>