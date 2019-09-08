<?php

namespace Differ;

use Symfony\Component\Yaml\Yaml;

class YamlDiffer extends AbstractDiffer
{
    public function __construct($content_file1, $content_file2)
    {
        $this->compare(
            Yaml::parse($content_file1),
            Yaml::parse($content_file2)
        );
    }
}