<?php
require_once __DIR__ . '/../src/bootstrap.php';

use Jet\Client;
use Jet\ClientFactory;
use Jet\Facade;
use Jet\Metadata;
use Jet\Transporter\CurlHttpTransporter;
use Jet\Transporter\StreamSocketTransporter;
use Jet\Util;

$configFile = is_file(__DIR__ . '/config.php') ? __DIR__ . '/config.php' : __DIR__ . '/config.php.dist';
$config = include $configFile;

$consulUri = Util::arrayGet($config, 'consul.uri', 'http://127.0.0.1:8500');
$jsonrpcHttpHost = Util::arrayGet($config, 'jsonrpc.http.host', '127.0.0.1');
$jsonrpcHttpPort = Util::arrayGet($config, 'jsonrpc.http.port', 9502);
$jsonrpcTcpHost = Util::arrayGet($config, 'jsonrpc.tcp.host', '127.0.0.1');
$jsonrpcTcpPort = Util::arrayGet($config, 'jsonrpc.tcp.port', 9503);

echo sprintf("CONSUL_URI: %s\n", $consulUri);

$service = 'CalculatorService';

echo "Create with http transporter\n";
$client = ClientFactory::create($service, new CurlHttpTransporter($jsonrpcHttpHost, $jsonrpcHttpPort));
var_dump($client->add(rand(0, 100), rand(0, 100)));

echo "Create with tcp transporter\n";
$client = ClientFactory::create($service, new StreamSocketTransporter($jsonrpcTcpHost, $jsonrpcTcpPort));
var_dump($client->add(rand(0, 100), rand(0, 100)));

echo "Create with facade\n";
/**
 * @method static int add(int $a, int $b)
 */
class Calculator extends Facade
{
    protected static function getFacadeAccessor()
    {
        global $jsonrpcHttpHost, $jsonrpcHttpPort;

        return ClientFactory::create('CalculatorService', new CurlHttpTransporter($jsonrpcHttpHost, $jsonrpcHttpPort));
        // return 'CalculatorService';
    }
}

var_dump(Calculator::add(rand(0, 100), rand(0, 100)));

echo "Create with custom client\n";
/**
 * @method int add(int$a, int$b)
 * @package
 */
class CalculatorService extends Client
{
    public function __construct($service = 'CalculatorService')
    {
        global $jsonrpcHttpHost, $jsonrpcHttpPort;

        $metadata = new Metadata($service);
        $metadata->setTransporter(new CurlHttpTransporter($jsonrpcHttpHost, $jsonrpcHttpPort));

        parent::__construct($metadata);
    }
}

$service = new CalculatorService;
var_dump($service->add(rand(0, 100), rand(0, 100)));
