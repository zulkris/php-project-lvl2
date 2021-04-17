<?php
declare(strict_types=1);

namespace App;

use function in_array;

class DiffEvaluator
{
    public function evaluateDiff(array $one, array $two): array
    {
        $allKeys = array_keys(array_merge($one, $two));

        $removedAndChanged = array_keys(array_diff($one, $two));
        $addedAndChanged   = array_keys(array_diff($two, $one));
        $plusMinusKeys     = array_intersect($removedAndChanged, $addedAndChanged);
        $minusKeys         = array_diff($removedAndChanged, $plusMinusKeys);
        $plusKeys          = array_diff($addedAndChanged, $plusMinusKeys);

        return array_reduce(
            $allKeys,
            static function ($res, $key) use ($plusKeys, $minusKeys, $plusMinusKeys, $one, $two) {
                switch ($key) {
                    case in_array($key, $minusKeys, true):
                        $res[] = ['type' => 'minus', 'item' => $key, 'value' => $one[$key]];
                        break;
                    case in_array($key, $plusKeys, true):
                        $res[] = ['type' => 'plus', 'item' => $key, 'value' => $two[$key]];
                        break;
                    case in_array($key, $plusMinusKeys, true):
                        $res[] = ['type' => 'minus', 'item' => $key, 'value' => $one[$key]];
                        $res[] = ['type' => 'plus', 'item' => $key, 'value' => $two[$key]];
                        break;
                    default:
                        $res[] = ['type' => 'same', 'item' => $key, 'value' => $one[$key]];
                        break;
                }
                return $res;
            },
            []
        );
    }
}