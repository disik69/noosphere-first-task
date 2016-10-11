<?php

namespace App\Collections;

use Illuminate\Support\Collection;

class AutoIncrementCollection extends Collection
{
    private $counter = 0;

    public function getNextId()
    {
        return ++$this->counter;
    }
}