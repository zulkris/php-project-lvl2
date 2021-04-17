<?php
declare(strict_types=1);

namespace App;

use Docopt;
use JsonException;

class CliHandler
{
    /**
     * @throws JsonException
     */
    public static function handle($configFile): string
    {
        $args = Docopt::handle($configFile);

        foreach ($args as $k => $v) {
            $res[] = $k . ': ' . json_encode($v, JSON_THROW_ON_ERROR);
        }

        return implode(PHP_EOL, $res ?? []);
    }
}
