<?php

namespace App\Helper;

abstract class ImportDataHelper
{
    abstract public static function transform($data): array;

    public static function tofloat($val) {
        return floatval(str_replace(",","",$val));
    }
}
