<?php

namespace Gendiff\Cli;

require_once('config.php');

function handle($args, $doc) use ($config)
{
    foreach ($args as $key => $value) {
        if ($value == true) {
            echo $config[$key]($doc);
        }
    }
}
