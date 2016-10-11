<?php

namespace App;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Collections\AutoIncrementCollection as Collection;

class User implements Authenticatable
{
    use Auth\Authenticatable;

    private static $fileName = 'users';

    public $id = 0;
    public $name = '';
    public $password = '';

    public function save()
    {
        $list = $this->getList();

        if ($this->id) {
            foreach ($list as $key => $value) {
                if ($value->id === $this->id) {
                    $list->put($key, $this);
                }
            }
        } else {
            $this->id = $list->getNextId();

            $list->push($this);
        }

        $this->putList($list);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function get(Array $conditions = [])
    {
        $list = collect(self::getList());

        if ($conditions) {
            foreach ($conditions as $key => $value) {
                $list = $list->where($key, $value, true);
            }
        }

        return $list;
    }

    /**
     * @return self
     */
    public static function find($id)
    {
        return self::get(['id' => $id])->first();
    }

    /**
     * @return Collection
     */
    private static function getList()
    {
        if (\Storage::exists(self::$fileName)) {
            $list = unserialize(\Storage::get(self::$fileName));
        } else {
            $list = new Collection();
        }

        return $list;
    }

    private static function putList(Collection $list)
    {
        return \Storage::put(self::$fileName, serialize($list));
    }
}
