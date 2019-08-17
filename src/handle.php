<?php

namespace Gendiff\Cli;

use function Differ\genDiff;

const DESCRIPTION = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  --format <fmt>                Report format [default: pretty]
DOC;

function handle($args)
{
    $format = isset($args['--format']) ? isset($args['--format']) : null;

    if (isset($args['<firstFile>']) && isset($args['<secondFile>'])) {

        $diff = genDiff($args['<firstFile>'], $args['<secondFile>']);
        print($diff);
        return;
    }
}

function doIt()
{
    $args = \Docopt::handle(DESCRIPTION);
    if ($args['--help'] == true || $args['-h'] == true) {
        echo DESCRIPTION;
        return;
    }

    handle($args);
}
