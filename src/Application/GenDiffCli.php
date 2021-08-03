<?php
declare(strict_types=1);

namespace App\Application;

use App\Domain\DiffEvaluator;
use Docopt\Handler;
use Docopt\LanguageError;
use JsonException;

class GenDiffCli
{
    private Handler       $handler;
    private DiffEvaluator $diffEvaluator;

    private string $configFile =
        <<<'STR'
        Generate diff
        
        Usage:
          gendiff (-h|--help)
          gendiff (-v|--version)
          gendiff [--format <fmt>] <firstFile> <secondFile>
        
        Options:
          -h --help                     Show this screen
          -v --version                  Show version
          --format <fmt>                Report format [default: plain]
        STR;

    private array         $defaultParams = [
        'version' => '0.1.1',
        'format'  => 'plain',
    ];

    public function __construct(Handler $handler, DiffEvaluator $diffEvaluator)
    {
        $this->handler       = $handler;
        $this->handler->__construct($this->defaultParams);

        $this->diffEvaluator = $diffEvaluator;
    }

    /**
     * @throws JsonException
     * @throws LanguageError
     */
    public function handle(): string
    {
        $args = $this->handler->handle($this->configFile);

        foreach ($args as $k => $v) {
            $res[] = $k . ': ' . json_encode($v, JSON_THROW_ON_ERROR);
        }

        return implode(PHP_EOL, $res ?? []);
    }
}
