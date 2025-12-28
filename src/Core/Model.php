<?php

declare(strict_types=1);

namespace Erpia\Core;

use PDO;

abstract class Model
{
    protected static function db(): PDO
    {
        return Database::getConnection();
    }
}