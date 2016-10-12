<?php

namespace App\FileModel;

class FileModel
{
    protected static $fileName = '';

    public $id = 0;

    public function save()
    {
        $list = self::getList();

        if ($this->id) {
            foreach ($list as $key => $value) {
                if ($value->id === $this->id) {
                    $list->put($key, $this);

                    break;
                }
            }
        } else {
            $this->id = $list->getNextId();

            $list->push($this);
        }

        self::putList($list);
    }

    public function delete()
    {
        $list = self::getList();

        foreach ($list as $key => $value) {
            if ($value->id === $this->id) {
                $list->forget($key);

                break;
            }
        }

        self::putList($list);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function get(array $conditions = [])
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
     * @return static
     */
    public static function find($id)
    {
        return static::get(['id' => $id])->first();
    }

    /**
     * @return AutoIncrementCollection
     */
    protected static function getList()
    {
        if (\Storage::exists(static::$fileName)) {
            $list = unserialize(\Storage::get(static::$fileName));
        } else {
            $list = new AutoIncrementCollection();
        }

        return $list;
    }

    protected static function putList(AutoIncrementCollection $list)
    {
        return \Storage::put(static::$fileName, serialize($list));
    }
}