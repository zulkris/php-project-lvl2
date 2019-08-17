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

        $file1 = file_get_contents(getcwd() . "/" . $args["<firstFile>"]);
        $file2 = file_get_contents(getcwd() . "/" . $args["<secondFile>"]);
        $diff = genDiff($file1, $file2);
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
