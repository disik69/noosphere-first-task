<?php

namespace App;

use App\FileModel\FileModel;
use App\FileModel\AutoIncrementCollection as Collection;
use Illuminate\Contracts\Auth\Authenticatable;

class User extends FileModel implements Authenticatable
{
    use Auth\Authenticatable;

    protected static $fileName = 'users';

    public $name = '';
    public $password = '';
    public $remember_token = '';
}
