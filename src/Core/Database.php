<?php

declare(strict_types=1);

namespace Erpia\Core;

class Database
{
    protected ?\PDO $connection = null;

    public function __construct()
    {
    }
}
