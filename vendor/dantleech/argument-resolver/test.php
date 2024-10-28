<?php

use DTL\ArgumentResolver\ArgumentResolver;


require __DIR__  . '/vendor/autoload.php';

class Foobar
{
    public function execute(string $foobar, $barfoo = 'foobar')
    {
    }
}

$argumentResolver = new ArgumentResolver();
$arguments = $argumentResolver->resolveArguments('Foobar', 'execute', [
    'foobar' => 'hello'
]);

var_dump($arguments);
