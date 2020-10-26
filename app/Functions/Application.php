<?php

//region Namespace
namespace App\Functions;
//endregion

//region Using
use App\Models\TblCategory;
use Respect\Validation\Validator as v;
//endregion

class Application
{
    /**
     * @param $model
     * @param null $isActive
     * @param null $orderByAscColumn
     * @param null $orderByDescColumn
     * @return null
     */
    public static function getTable($model, $isActive = null, $orderByAscColumn = null, $orderByDescColumn = null)
    {
        $returnData = null;

        try {
            if (v::stringType()->notEmpty()->validate($model)) {
                $table = new $model();

                if (v::boolType()->validate($isActive)) {
                    $table = $table->where('is_active', $isActive);
                }

                if (v::stringType()->notEmpty()->validate($orderByAscColumn)) {
                    $table = $table->orderBy($orderByAscColumn, 'asc');
                }

                if (v::stringType()->notEmpty()->validate($orderByDescColumn)) {
                    $table = $table->orderBy($orderByDescColumn, 'desc');
                }

                if (!empty($table) && !empty($table->get()) && !$table->get()->isEmpty()) {
                    $returnData = $table->get();
                }
            }
        } catch (\Exception $e) {
            //region Log
            $container->logger->addCritical($e->getMessage());
            //endregion
        }

        return $returnData;
    }

    /**
     * @param $model
     * @param null $isActive
     * @param $id
     * @return null
     */
    public static function getRow($model, $isActive = null, $id)
    {
        $returnData = null;

        try {
            if (v::stringType()->notEmpty()->validate($model)) {
                $table = $model::where('id', $id);

                if (v::boolType()->validate($isActive)) {
                    $table = $table->where('is_active', $isActive);
                }

                $returnData = $table->first();
            }
        } catch (\Exception $e) {
            //region Log
            $container->logger->addCritical($e->getMessage());
            //endregion
        }

        return $returnData;
    }

    /**
     * @param $model
     * @param null $isActive
     * @param $id
     * @param $column
     * @return null
     */
    public static function getColumn($model, $isActive = null, $id, $column)
    {
        $returnData = null;

        try {
            if (v::stringType()->notEmpty()->validate($model)) {
                $table = $model::where('id', $id);

                if (v::boolType()->validate($isActive)) {
                    $table = $table->where('is_active', $isActive);
                }

                if (!empty($table) && !empty($table->get()) && !$table->get()->isEmpty()) {
                    $returnData = $table->first()->$column;
                }
            }
        } catch (\Exception $e) {
            //region Log
            $container->logger->addCritical($e->getMessage());
            //endregion
        }

        return $returnData;
    }

    /**
     * @param $model
     * @param null $isActive
     * @return null
     */
    public static function getCount($model, $isActive = null)
    {
        $returnData = null;

        try {
            if (v::stringType()->notEmpty()->validate($model)) {
                $table = new $model();

                if (v::boolType()->validate($isActive)) {
                    $table = $table->where('is_active', $isActive);
                }

                $returnData = $table->count();
            }
        } catch (\Exception $e) {
            //region Log
            $container->logger->addCritical($e->getMessage());
            //endregion
        }

        return $returnData;
    }
}


