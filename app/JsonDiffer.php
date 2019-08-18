<?php

namespace Differ;

class JsonDiffer extends AbstractDiffer
{
	/**
	 * Differ constructor.
	 * @param $path1
	 * @param $path2
	 */
	public function __construct($content_file1, $content_file2)
    {
        $this->content1 = json_decode($content_file1, true);
        $this->content2 = json_decode($content_file2, true);
    }
}