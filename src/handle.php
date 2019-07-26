<?php

namespace Gendiff\Cli;

function doIt()
{
    $args = \Docopt::handle(DESCRIPTION);
    handle($args, DESCRIPTION, $config);
}

function handle($args, $doc, $config)
{
    foreach ($args as $arg => $value) {
        if ($value == true) {
            echo $config($arg, $doc);
        }
    }
}
