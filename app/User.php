<?php

namespace App;

use \Illuminate\Contracts\Auth\Authenticatable;

class User implements Authenticatable
{
    use Auth\Authenticatable;
}
