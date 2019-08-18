<?php

use Differ\YamlDiffer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class YamlDifferTest extends TestCase
{

    public function dataProvider()
    {
        $data1 = file_get_contents(__DIR__ . '/example-files/yaml1.yml');
        $data2 = file_get_contents(__DIR__ . '/example-files/yaml2.yml');

        $expected =  [
            "id" => [
                "type" => 1,
                "value" => 1
            ],
            "name" => [
                "type" => 2,
                "old" => "John Smith",
                "new" => "John Smither"
            ],
            "position" => [
                "type" => 3,
                "value" => "developer",
            ],
            "age" => [
                "type" => 4,
                "value" => 33,
            ]
        ];

        return [
            [$data1, $data2, $expected]
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testToArray($data1, $data2, $expected)
    {
        $differ = new YamlDiffer($data1, $data2);
        $this->assertEquals($expected, $differ->compare()->toArray());
    }
}
