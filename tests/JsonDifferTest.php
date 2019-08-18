<?php

use PHPUnit\Framework\TestCase;
use Differ\JsonDiffer;

class JsonDifferTest extends TestCase
{
	public function dataProvider()
	{
		$json1 =  file_get_contents(__DIR__ . '/example-files/json1.json' );
		$json2 =  file_get_contents(__DIR__ . '/example-files/json2.json' );
		$json3 =  file_get_contents(__DIR__ . '/example-files/json3.json');

		$jsonhex1 = file_get_contents(__DIR__ . '/example-files/json_hex1.json');
		$jsonhex2 = file_get_contents(__DIR__ . '/example-files/json_hex2.json');

		$expectedChanged = [
			"uno" => [
				"type" => 1,
				"value" => "one"
			],
			"due" => [
				"type" => 2,
				"old" => "two",
				"new" => "dva"
			]
		];
		$expectedSame = [
			"uno" => [
				"type" => 1,
				"value" => "one"
			],
			"due" => [
				"type" => 1,
				"value" => "two"
			]
		];
		$expectedDeleted = [
			"uno" => [
				"type" => 3,
				"value" => "one"
			],
			"due" => [
				"type" => 1,
				"value" => "two"
			]
		];
		$expectedAdded = [
			"uno" => [
				"type" => 4,
				"value" => "one"
			],
			"due" => [
				"type" => 1,
				"value" => "two"
			]
		];
		$expectedMixed = [
			"host" => [
				"type" => 1,
				"value" => "hexlet.io"
			],
			"timeout" => [
				"type" => 2,
				"old" => 50,
				"new" => 20
			],
			"proxy" => [
				"type" => 3,
				"value" => "123.234.53.22",
			],
			"verbose" => [
				"type" => 4,
				"value" => 'true',
			]
		];

		return [
			[$json1, $json2, $expectedChanged],
			[$json1, $json1, $expectedSame],
			[$json1, $json3, $expectedDeleted],
			[$json3, $json1, $expectedAdded],
			[$jsonhex1, $jsonhex2, $expectedMixed]
		];
	}

	/**
	 * @dataProvider dataProvider
	 */
	public function testCompare($json1, $json2, $expected)
	{
		$differ = new JsonDiffer($json1, $json2);

		$this->assertEquals($expected, $differ->compare()->toArray());
	}

	public function testToString()
	{
		$jsonhex1 = file_get_contents(__DIR__ . '/example-files/json_hex1.json');
		$jsonhex2 = file_get_contents(__DIR__ . '/example-files/json_hex2.json');
		$differ = new JsonDiffer($jsonhex1, $jsonhex2);

		$output = file_get_contents(__DIR__ . '/example-files/string_hex.txt');

		$this->assertEquals($differ->compare()->toString(), $output);
	}
}
