<?php

namespace Gendiff\Cli;

function doIt($doc, $config)
{
    $args = \Docopt::handle($doc);
    handle($args, $doc, $config);
}

function handle($args, $doc, $config)
{
    foreach ($args as $arg => $value) {
        if ($value == true) {
            echo $config($arg, $doc);
        }
    }
}
