<?php
declare(strict_types=1);

use App\Domain\DiffEvaluator;
use PHPUnit\Framework\TestCase;

class DiffEvaluatorTest extends TestCase
{
    private DiffEvaluator $diffEvaluator;

    private array $before = [
        'баобаб'  => 'зелёный',
        'кенгуру' => 'прыгает',
        'заяц'    => 'зверь',
    ];

    private array $after = [
        'кенгуру' => 'подпрыгивает',
        'заяц'    => 'зверь',
        'игуана'  => 'вкусная',
    ];

    protected function setUp(): void
    {
        $this->diffEvaluator = new DiffEvaluator(new \App\Infrastructure\FileConverter(new \App\Infrastructure\FileTypeDetector()));
    }

    /** @test */
    public function returnsEmptyArray(): void
    {
        $res = $this->diffEvaluator->evaluateDiff($this->before, $this->after);
        self::assertEquals
        (
            [
                ['type' => 'minus', 'item' => 'баобаб', 'value' => 'зелёный'],
                ['type' => 'minus', 'item' => 'кенгуру', 'value' => 'прыгает'],
                ['type' => 'plus', 'item' => 'кенгуру', 'value' => 'подпрыгивает'],
                ['type' => 'same', 'item' => 'заяц', 'value' => 'зверь'],
                ['type' => 'plus', 'item' => 'игуана', 'value' => 'вкусная'],
            ],
            $res
        );
    }
}