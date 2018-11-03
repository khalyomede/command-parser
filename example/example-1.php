<?php
    require( __DIR__ . '/../vendor/autoload.php' );

    use Khalyomede\CommandParser;

    $arguments = CommandParser::parse([
        'arguments' => [
            'path'
        ],
        'options' => [
            'max-length, l',
            'offset: integer'
        ],
        'flags' => [
            'help, h',
            'version, v',
            'quiet'
        ]
    ]);

    print_r($arguments);
?>