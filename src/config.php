<?php

namespace Gendiff\Cli;

const DESCRIPTION = <<<DOC

Generate diff

Usage:
  gendiff (-h|--help)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  --format <fmt>                Report format [default: pretty]

DOC;


$config = function ($arg, $doc) {
    switch ($arg) {
        case '-h':
        case '--help':
            return $doc;
            break;
        default:
            break;
    }
};
