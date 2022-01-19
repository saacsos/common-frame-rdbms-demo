<?php

namespace App\Helper;

abstract class ImportDataHelper
{
    abstract public static function transform($data): array;
}
