<?php

use PHPUnit\Framework\TestCase;
use Differ\JsonDiffer;

class JsonDifferTest extends TestCase
{
    public function dataProvider()
    {
        $json1 = file_get_contents(__DIR__ . '/example-files/noscale/json1.json');
        $json2 = file_get_contents(__DIR__ . '/example-files/noscale/json2.json');
        $json3 = file_get_contents(__DIR__ . '/example-files/noscale/json3.json');

        $jsonhex1 = file_get_contents(__DIR__ . '/example-files/noscale/json_hex1.json');
        $jsonhex2 = file_get_contents(__DIR__ . '/example-files/noscale/json_hex2.json');

        $jsonScale1 = file_get_contents(__DIR__ . '/example-files/scale/json1.json');
        $jsonScale2 = file_get_contents(__DIR__ . '/example-files/scale/json2.json');

        $expectedChanged = [
            0 => [
                "key" => "uno",
                "type" => 1,
                "value" => "one"
            ],
            1 => [
                "key" => "due",
                "type" => 2,
                "old" => "two",
                "new" => "dva"
            ]
        ];
        $expectedSame = [
            0 => [
                "key" => "uno",
                "type" => 1,
                "value" => "one"
            ],
            1 => [
                "key" => "due",
                "type" => 1,
                "value" => "two"
            ]
        ];
        $expectedDeleted = [
            0 => [
                "key" => "uno",
                "type" => 4,
                "value" => "one"
            ],
            1 => [
                "key" => "due",
                "type" => 1,
                "value" => "two"
            ]
        ];
        $expectedAdded = [
            0 => [
                "key" => "uno",
                "type" => 3,
                "value" => "one"
            ],
            1 => [
                "key" => "due",
                "type" => 1,
                "value" => "two"
            ]
        ];
        $expectedMixed = [
            0 => [
                "key" => "host",
                "type" => 1,
                "value" => "hexlet.io"
            ],
            1 => [
                "key" => "timeout",
                "type" => 2,
                "old" => 50,
                "new" => 20
            ],
            2 => [
                "key" => "proxy",
                "type" => 4,
                "value" => "123.234.53.22",
            ],
            3 => [
                "key" => "verbose",
                "type" => 3,
                "value" => true,
            ]
        ];

        $expectedScale = [
            0 => [
                "key" => "common",
                "type" => 5,
                "value" => [
                    0 => [
                        "key" => "setting1",
                        "type" => 1,
                        "value" => "Value 1",
                    ],
                    1 => [
                        "key" => "setting2",
                        "type" => 4,
                        "value" => "200"
                    ],
                    2 => [
                        "key" => "setting3",
                        "type" => 1,
                        "value" => true,
                    ],
                    3 => [
                        "key" => "setting6",
                        "type" => 4,
                        "value" => [
                            "key" => "value"
                        ]
                    ],
                    4 => [
                        "key" => "setting4",
                        "type" => 3,
                        "value" => "blah blah"
                    ],
                    5 => [
                        "key" => "setting5",
                        "type" => 3,
                        "value" => [
                            "key5" => "value5"
                        ]
                    ],
                ]
            ],
            1 => [
                "key" => "group1",
                "type" => 5,
                "value" => [
                    0 => [
                        "key" => "baz",
                        "type" => 2,
                        "old" => "bas",
                        "new" => "bars"
                    ],
                    1 => [
                        "key" => "foo",
                        "type" => 1,
                        "value" => "bar",
                    ]
                ]

            ],
            2 => [
                "key" => "group2",
                "type" => 4,
                "value" => [
                    "abc" => "12345"
                ]
            ],
            3 => [
                "key" => "group3",
                "type" => 3,
                "value" => [
                    "fee" => "100500"
                ]
            ]
        ];

        return [
            [$json1, $json2, $expectedChanged],
            [$json1, $json1, $expectedSame],
            [$json1, $json3, $expectedDeleted],
            [$json3, $json1, $expectedAdded],
            [$jsonhex1, $jsonhex2, $expectedMixed],
            [$jsonScale1, $jsonScale2, $expectedScale]
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCompare($json1, $json2, $expected)
    {
        $differ = new JsonDiffer($json1, $json2);

        $this->assertEqualsCanonicalizing($expected, $differ->toArray());
    }

    public function testToString()
    {
        $jsonhex1 = file_get_contents(__DIR__ . '/example-files/noscale/json_hex1.json');
        $jsonhex2 = file_get_contents(__DIR__ . '/example-files/noscale/json_hex2.json');
        $differ = new JsonDiffer($jsonhex1, $jsonhex2);

        $output = file_get_contents(__DIR__ . '/example-files/noscale/string_hex.txt');

        $this->assertEquals($output, $differ->toString());
    }
}
