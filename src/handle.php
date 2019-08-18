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

    if (isset($args["<firstFile>"]) && isset($args["<firstFile>"])) {
        $path_file1 = realpath($args["<firstFile>"]);
        $path_file2 = realpath($args["<secondFile>"]);

        $diff = genDiff($path_file1, $path_file2);
        print($diff);
        return;
    }
}

function doIt()
{
    $args = \Docopt::handle(DESCRIPTION);
    if ((isset($args['--help']) && $args['--help'] == true) || (isset($args['-h']) && $args['-h'] == true)) {
        echo DESCRIPTION;
        return;
    }

    handle($args);
}
