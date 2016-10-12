<?php

namespace App\FileModel;

use Illuminate\Support\Collection;

class AutoIncrementCollection extends Collection
{
    private $counter = 0;

    public function getNextId()
    {
        return ++$this->counter;
    }
}