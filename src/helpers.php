<?php

namespace Differ;

if (! function_exists('genDiff')) {
    function genDiff($path1, $path2)
    {
        $differ = new Differ($path1, $path2);
        return $differ->compare()->toString();
    }
}
