<?php

namespace Differ;

class JsonDiffer extends AbstractDiffer
{
	/**
	 * Differ constructor.
	 * @param $path1
	 * @param $path2
	 */
	public function __construct($before, $after)
    {
        $this->compare(
            json_decode($before, true),
            json_decode($after, true)
        );
    }
}