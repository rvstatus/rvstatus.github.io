<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
    abstract function getModelClass();

    private static function getModel()
    {
        $me = new static;
        return $me->getModelClass();
    }

    public static function findAll()
    {
        return self::getModel()->get();
    }

    public static function getTotalCount()
    {
        return self::getModel()->count();
    }

    public static function truncate()
    {
        return self::getModel()->truncate();
    }

    public static function findByPKey($pval)
    {
        if (is_array($pval)) {
            $model = static::getModel()->newQuery();
            foreach($pval as $key => $val) {
                $model->where($key, '=', $val);
            }
            return $model->first();
        }
        return static::getModel()->find($pval);
    }

    public static function initialize()
    {
        return self::getModel();
    }

    public static function save($params)
    {
        $pKey = self::getPKeyName();

        $currentObject = self::findByPKey($params[$pKey]);
        if (empty($currentObject)) {
            $currentObject = self::initialize();
        }

        return $currentObject->fill($params)->save();
    }

    public static function setKeyName($params)
    {
        $keys = self::getKeys();
        return collect($keys)->mapWithKeys(function($item, $key) use($params) {
            return [$item => (isset($params[$key]) && $params[$key] != "")? preg_replace('/\A[\x00\s]++|[\x00\s]++\z/u', '', $params[$key]) : null];
        })->toArray();
    }

    public static function getKeys()
    {
        return self::getModel()->getFillable();
    }

    public static function getPKeyName()
    {
        return self::getModel()->getKeyName();
    }

    public static function validate($params)
    {
        $model = self::getModel();

        $validator = Validator::make(
            $params,
            $model->validationRules(),
            $model->validationMessages()
        );

        if($validator->fails()){
            throw new ValidationException($validator);
        }
    }

    public static function begin()
    {
        $connection = self::getModel()->getConnectionName();
        DB::connection($connection)->beginTransaction();
    }

    public static function commit()
    {
        $connection = self::getModel()->getConnectionName();
        DB::connection($connection)->commit();
    }

    public static function rollback()
    {
        $connection = self::getModel()->getConnectionName();
        DB::connection($connection)->rollback();
    }

}
