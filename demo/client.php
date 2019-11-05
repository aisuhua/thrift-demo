<?php

// turn error reporting on, so we get everything in the console
error_reporting(E_ERROR);

// load libraries (note proper path to Thrift dir)
define('THRIFT_PHP_LIB', __DIR__);
define('GEN_PHP_DIR', __DIR__.'/gen-php');

require_once THRIFT_PHP_LIB.'/Thrift/ClassLoader/ThriftClassLoader.php';

use Thrift\ClassLoader\ThriftClassLoader;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;

$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', THRIFT_PHP_LIB);
$loader->registerDefinition('adder', GEN_PHP_DIR);
$loader->register();

if (array_search('--http', $argv)) {
    $socket = new THttpClient('thrift.demo.com', 80,'');
} else {
    $host = explode(":", $argv[1]);
    $socket = new TSocket('172.17.0.2', 8000);
}

$transport = new TBufferedTransport($socket, 1024, 1024);
$protocol  = new TBinaryProtocol($transport);
$client = new \adder\AddServiceClient($protocol);

$transport->open();
echo $client->add(100, 200);
echo PHP_EOL;
$transport->close();