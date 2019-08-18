<?php

namespace Differ;

if (! function_exists('genDiff')) {
    function genDiff($path_file1, $path_file2)
    {
        $pathinfo1 = pathinfo($path_file1);
        $pathinfo2 = pathinfo($path_file2);

        if ($pathinfo1['extension'] !== $pathinfo2['extension']) {
            throw new \Exception('files have different extensions');
        }

        switch ($pathinfo1['extension']) {
            case 'json':
                $differ = new JsonDiffer(file_get_contents($path_file1), file_get_contents($path_file2));
                break;
            case 'yml':
            case 'yaml':
                $differ = new YamlDiffer(file_get_contents($path_file1), file_get_contents($path_file2));
                break;
        }

        return $differ->compare()->toString();
    }
}
