<?php


namespace App\Helpers;


use App\Lang;
use Illuminate\Database\Eloquent\Model;

class StoreHelper
{
    private function __construct()
    {
    }

    public static function getColumnValue(string $column, Model $model)
    {
        $attributes = $model->getAttributes();

        return isset($attributes[$column . '_' . Lang::locale()])
            ? $attributes[$column . '_' . Lang::locale()]
            : $attributes[$column];
    }
}
