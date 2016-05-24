<?php

require_once(__DIR__ . '/vendor/autoload.php');

use GetOptionKit\OptionCollection;
use GetOptionKit\OptionParser;
use GetOptionKit\OptionPrinter\ConsoleOptionPrinter;

$specs = new OptionCollection;
$specs->add('w|wsdl:', 'Url or path to wsdl')
    ->isa('url');
$specs->add('n|namespace?', 'Namespace to use')
    ->isa('string');
$specs->add('o|operation+', 'Filter on operations to generate')
    ->isa('string');
$specs->add('auth?', 'Which authentication method to use')
    ->isa('string')
    ->validValues(['none', 'basic'])
    ->defaultValue('none');
$specs->add('auth-login?', 'User for authentication')
    ->isa('string');
$specs->add('auth-password?', 'Password for authentication')
    ->isa('string');
$specs->add('connection-timeout?', 'Connection timeout in seconds')
    ->isa('number')
    ->defaultValue(15);
$specs->add('h|help', 'Display this help');

$parser = new OptionParser($specs);

try {
    $result = $parser->parse( $argv );
    
    if(!empty($result['help']) || empty($result)) {
        $printer = new ConsoleOptionPrinter();
        echo $printer->render($specs);
    } else {
        $params = array(
            'inputFile' => $result['wsdl']->getValue(),
            'outputDir' => '/output',
            'soapClientOptions' => array()
        );

        if($result['auth']->getValue() !== 'none') {
            $params['soapClientOptions'] = array(
                'authentication' => SOAP_AUTHENTICATION_BASIC,
                'login' => $result['auth-login']->getValue(),
                'password' => $result['auth-password']->getValue()
 
            );
        }

        $params['soapClientOptions']['connection_timeout'] = $result['connection-timeout']->getValue();
       
        if($result['namespace']) {
            $params['namespaceName'] = $result['namespace']->getValue();
        }

        if($result['operation']) {
            $params['operationNames'] = implode(',', $result['operation']->getValue());
        }
 
        $generator = new \Wsdl2PhpGenerator\Generator();
        $generator->generate(    
            new \Wsdl2PhpGenerator\Config($params)
        );
    }
} 
catch( Exception $e ) {
    echo 'Error: ' . $e->getMessage() . "\n";
}

