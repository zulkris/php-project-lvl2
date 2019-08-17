<?php

namespace Differ;

class Differ
{
	private $json1;
	private $json2;

	private $result;

	const SAME = 1;
	const CHANGED = 2;
	const DELETED = 3;
	const ADDED = 4;

	/**
	 * Differ constructor.
	 * @param $path1
	 * @param $path2
	 */
	public function __construct($text1, $text2)
	{
		$this->json1 = json_decode($text1, true);
		$this->json2 = json_decode($text2, true);
	}
	public function compare()
	{
	    //var_dump($this->json1, $this->json2); exit;

		$allKeys = array_keys(array_merge($this->json1, $this->json2));

		$this->result = array_reduce($allKeys, function($acc, $key) {
			$a1 = $this->json1;
			$a2 = $this->json2;
			if (isset($a1[$key]) && isset($a2[$key]) && $a1[$key] === $a2[$key]) {
				$acc[$key] = [
					"type" => 1,
					"value" => is_bool($a1[$key]) ? json_encode($a1[$key]) : $a1[$key]
				];
				return $acc;
			}
			if (isset($a1[$key]) && isset($a2[$key])) {
				$acc[$key] = [
					"type" => 2,
					"old" => is_bool($a1[$key]) ? json_encode($a1[$key]) : $a1[$key],
					"new" => is_bool($a2[$key]) ? json_encode($a2[$key]) : $a2[$key]
				];
				return $acc;
			}
			if (isset($a1[$key]) && !isset($a2[$key])) {
				$acc[$key] = [
					"type" => 3,
					"value" => is_bool($a1[$key]) ? json_encode($a1[$key]) : $a1[$key]
				];
			}
			if (!isset($a1[$key]) && isset($a2[$key])) {
				$acc[$key] = [
					"type" => 4,
					"value" => is_bool($a2[$key]) ? json_encode($a2[$key]) : $a2[$key]
				];
			}
			return $acc;
		}, []);

		return $this;
	}

	public function toArray()
	{
		return $this->result;
	}
	public function toString()
	{
		$result = $this->result;
		$strings = array_map(function ($key) use ($result) {
			switch ($result[$key]['type']) {
				case 1:
					return "    {$key}: {$result[$key]['value']}";
					break;
				case 2:
					return "  + {$key}: {$result[$key]['new']}" . PHP_EOL . "  - {$key}: {$result[$key]['old']}";
					break;
				case 3:
					return "  - {$key}: {$result[$key]['value']}";
					break;
				case 4:
					return "  + {$key}: {$result[$key]['value']}";
					break;
			}
			return false; //unexpected
		}, array_keys($this->result));

		return "{" . PHP_EOL .
			implode(PHP_EOL, $strings) .
			PHP_EOL . "}";
	}
}