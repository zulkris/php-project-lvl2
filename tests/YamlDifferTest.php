<?php

use Differ\YamlDiffer;
use PHPUnit\Framework\TestCase;

class YamlDifferTest extends TestCase
{

    public function dataProvider()
    {
        $data1 = file_get_contents(__DIR__ . '/example-files/noscale/yaml1.yml');
        $data2 = file_get_contents(__DIR__ . '/example-files/noscale/yaml2.yml');

        $expected =  [
            0 => [
                "key" => "id",
                "type" => 1,
                "value" => 1
            ],
            1 => [
                "key" => "name",
                "type" => 2,
                "old" => "John Smith",
                "new" => "John Smither"
            ],
            2 => [
                "key" => "position",
                "type" => \Differ\AbstractDiffer::DELETED,
                "value" => "developer",
            ],
            3 => [
                "key" => "age",
                "type" => \Differ\AbstractDiffer::ADDED,
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
        $this->assertEquals($expected, $differ->toArray());
    }
}
