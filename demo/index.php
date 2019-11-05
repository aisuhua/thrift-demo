<?php
/**
 * http://www.thrift.pl/
 * https://segmentfault.com/a/1190000013329497
 * https://thrift-tutorial.readthedocs.io/en/latest/index.html
 */

// turn error reporting on, so we get everything in the console
error_reporting(E_ERROR);

// load libraries (note proper path to Thrift dir)
define('THRIFT_PHP_LIB', __DIR__);
define('GEN_PHP_DIR', __DIR__.'/gen-php');

require_once THRIFT_PHP_LIB.'/Thrift/ClassLoader/ThriftClassLoader.php';

use Thrift\ClassLoader\ThriftClassLoader;

$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', THRIFT_PHP_LIB);
$loader->registerDefinition('adder', GEN_PHP_DIR);
$loader->register();

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TPhpStream;
use Thrift\Transport\TBufferedTransport;

// this is actual code that you need to write – the service handler
class AdderHandler implements \adder\AddServiceIf {

    public function add($number1, $number2) {
        file_put_contents('/tmp/info.log', "$number1 + $number2" . PHP_EOL);
        return $number1 + $number2;
    }

}

// handling the calls
// header('Content-Type', 'application/x-thrift');
//echo "\r\n";


$handler = new AdderHandler();
$processor = new \adder\AddServiceProcessor($handler);

$transport = new TBufferedTransport(new TPhpStream(TPhpStream::MODE_R | TPhpStream::MODE_W));
$protocol = new TBinaryProtocol($transport, true, true);

$transport->open();
$processor->process($protocol, $protocol);
$transport->close();