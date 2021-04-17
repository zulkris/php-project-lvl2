<?php
declare(strict_types=1);

use App\DiffEvaluator;
use PHPUnit\Framework\TestCase;

class DiffEvaluatorTest extends TestCase
{
    private DiffEvaluator $diffEvaluator;

    protected function setUp(): void
    {
        $this->diffEvaluator = new DiffEvaluator();
    }

    /** @test */
    public function returnsEmptyArray()
    {
        $res = $this->diffEvaluator->evaluateDiff([], []);
        self::assertEmpty($res);
    }
}