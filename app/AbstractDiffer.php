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
            if (is_array($value) &&
                isset($merged[$key]) &&
                is_array($merged[$key])) {
                $merged[$key] = $this->array_merge_recursive_ex($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    private function getTypeOfArray(array $a, array $b, $key)
    {
        if (isset($a[$key]) &&
            is_array($a[$key]) &&
            isset($b[$key]) &&
            is_array($b[$key]) &&
            $a[$key] !== $b[$key]) {

            $type = self::CHILDREN;

        } elseif (isset($a[$key]) &&
            is_array($a[$key]) &&
            !isset($b[$key])) {

            $type = self::DELETED;

        } elseif (!isset($a[$key]) &&
            isset($b[$key]) &&
            is_array($b[$key])) {

            $type = self::ADDED;
        }
        return $type;
    }

    public function compare(array $before, array $after): AbstractDiffer
    {
        $transitional = $this->array_merge_recursive_ex($before, $after);

        $my_array_reduce = function ($c, $a, $b, callable $callable) use (&$my_array_reduce) {
            $res = [];
            foreach ($c as $key => $value) {
                if (is_array($c[$key])) {
                    $type = $this->getTypeOfArray($a, $b, $key);
                    switch ($type) {
                        case self::CHILDREN:
                            $res[] = [
                                "key" => $key,
                                "type" => self::CHILDREN,
                                "value" => $my_array_reduce($c[$key], $a[$key], $b[$key], $callable)
                            ];
                            break;
                        case self::DELETED:
                            $res[] = [
                                "key" => $key,
                                "type" => self::DELETED,
                                "value" => $a[$key]
                            ];
                            break;
                        case self::ADDED:
                            $res[] = [
                                "key" => $key,
                                "type" => self::ADDED,
                                "value" => $b[$key]
                            ];
                            break;
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

        $compareVariables = function ($a, $b) {
            if (!empty($a) && !empty($b) && $a === $b) {
                $res = [
                    "type" => self::SAME,
                    "value" => $a
                ];
            }
            if (!empty($a) && !empty($b) && $b !== $a) {
                $res = [
                    "type" => self::CHANGED,
                    "old" => $a,
                    "new" => $b
                ];
            }
            if (!empty($a) && empty($b)) {
                $res = [
                    "type" => self::DELETED,
                    "value" => $a
                ];
            }
            if (empty($a) && !empty($b)) {
                $res = [
                    "type" => self::ADDED,
                    "value" => $b
                ];
            }
            return $res;
        };

        $this->result = $my_array_reduce(
            $transitional,
            $before,
            $after,
            $compareVariables
        );

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
        $convertFunc = function ($item) use (&$convertFunc) {
            switch ($item['type']) {
                case self::SAME:
                    $string = "    {$item['key']}: " . $this->toRightView($item['value']);
                    break;
                case self::CHANGED:
                    $string = "  + {$item['key']}: " . $this->toRightView($item['new']) . PHP_EOL .
                        "  - {$item['key']}: " . $this->toRightView($item['old']);
                    break;
                case self::ADDED:
                    $string = "  + {$item['key']}: " . $this->toRightView($item['value']);
                    break;
                case self::DELETED:
                    $string = "  - {$item['key']}: " . $this->toRightView($item['value']);
                    break;
                case self::CHILDREN:
                    $string = "    {$item['key']}: {$convertFunc($item)}";
                    break;
            }
            return $string;
        };

        $strings = implode(PHP_EOL, array_map($convertFunc, $this->result));
        return "{" . PHP_EOL . $strings . PHP_EOL . "}";
    }
}