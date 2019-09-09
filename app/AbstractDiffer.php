<?php

namespace Differ;

abstract class AbstractDiffer
{
    protected $result;

    const SAME = 1;
    const CHANGED = 2;
    const ADDED = 3;
    const DELETED = 4;
    const CHILDREN = 5;

    abstract public function __construct($before, $after);

    private function array_merge_recursive_ex(array $array1, array $array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => & $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->array_merge_recursive_ex($merged[$key], $value);
            } else if (is_numeric($key)) {
                if (!in_array($value, $merged)) {
                    $merged[] = $value;
                }
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    public function compare(array $before, array $after): AbstractDiffer
    {
        $transitional = $this->array_merge_recursive_ex($before, $after);

        $my_array_reduce = function ($c, $a, $b, callable $callable) use (&$my_array_reduce) {
            $res = [];
            foreach ($c as $key => $value) {
                if (is_array($c[$key])) {

                    if (isset($a[$key]) &&
                        is_array($a[$key]) &&
                        isset($b[$key]) &&
                        is_array($b[$key]) &&
                        $a[$key] !== $b[$key]) {

                        $res[] = [
                            "key" => $key,
                            "type" => self::CHILDREN,
                            "value" => $my_array_reduce($c[$key], $a[$key], $b[$key], $callable)
                        ];

                    } elseif (isset($a[$key]) &&
                        is_array($a[$key]) &&
                        !isset($b[$key])) {
                        $res[] = [
                            "key" => $key,
                            "type" => self::DELETED,
                            "value" => $a[$key]
                        ];
                    } elseif (!isset($a[$key]) &&
                        isset($b[$key]) &&
                        is_array($b[$key])) {
                        $res[] = [
                            "key" => $key,
                            "type" => self::ADDED,
                            "value" => $b[$key]
                        ];
                    }

                } else {
                    $res[] = array_merge(
                        ["key" => $key],
                        $callable(
                            isset($a[$key]) ? $a[$key] : null,
                            isset($b[$key]) ? $b[$key] : null
                        )
                    );
                }
            }
            return $res;
        };

        $compareVariablesFunction = function ($a, $b) {
            if (!empty($a) && !empty($b) && $a === $b) {
                return [
                    "type" => self::SAME,
                    "value" => $a
                ];
            }
            if (!empty($a) && !empty($b) && $b !== $a) {
                return [
                    "type" => self::CHANGED,
                    "old" => $a,
                    "new" => $b
                ];
            }
            if (!empty($a) && empty($b)) {
                return [
                    "type" => self::DELETED,
                    "value" => $a
                ];
            }
            if (empty($a) && !empty($b)) {
                return [
                    "type" => self::ADDED,
                    "value" => $b
                ];
            }
        };

        $this->result = $my_array_reduce(
            $transitional,
            $before,
            $after,
            $compareVariablesFunction);

        return $this;
    }

    public function toArray()
    {
        return $this->result;
    }

    private function toRightView($value)
    {
        return is_bool($value) ? json_encode($value) : $value;
    }

    public function toString()
    {
        //var_dump($this->result);
        $convertFunc = function ($item) use (&$convertFunc) {
            switch ($item['type']) {
                case self::SAME:
                    return "    {$item['key']}: " . $this->toRightView($item['value']);
                    break;
                case self::CHANGED:
                    return "  + {$item['key']}: " . $this->toRightView($item['new']) . PHP_EOL .
                        "  - {$item['key']}: " . $this->toRightView($item['old']);
                    break;
                case self::ADDED:
                    return "  + {$item['key']}: " . $this->toRightView($item['value']);
                    break;
                case self::DELETED:
                    return "  - {$item['key']}: " . $this->toRightView($item['value']);
                    break;
                case self::CHILDREN:
                    return "    {$item['key']}: {$convertFunc($item)}";
                    break;

            }
        };

        $strings = implode(PHP_EOL, array_map($convertFunc, $this->result));
        return "{" . PHP_EOL . $strings . PHP_EOL . "}";
    }
}