<?php

//region Namespace
namespace App\Helpers;
//endregion

//region Using
use App\Functions;
use Respect\Validation\Validator as v;
//endregion

class MyTwigExtensions extends \Twig_Extension
{
    private $router;
    private $uri;

    public function __construct($router, $uri)
    {
        $this->router = $router;
        $this->uri = $uri;
    }

    public function getName()
    {
        return 'slim';
    }

    //region Filters
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('htmlSpecialCharsDecode', array($this, 'htmlSpecialCharsDecode'))
        ];
    }

    /**
     * @param $value
     * @return null|string
     */
    public function htmlSpecialCharsDecode($value)
    {
        $returnString = null;

        try {
            if (v::stringType()->notEmpty()->validate($value)) {
                $returnString = htmlspecialchars_decode((string)$value);
            }
        }
        catch (\Exception $e) {
            //region Log
            $this->logger->addCritical($e->getMessage());
            //endregion
        }

        return $returnString;
    }
    //endregion

    //region Twig Functions
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getTable', array($this, 'getTable')),
            new \Twig_SimpleFunction('getRow', array($this, 'getRow')),
            new \Twig_SimpleFunction('getColumn', array($this, 'getColumn')),
            new \Twig_SimpleFunction('getCount', array($this, 'getCount'))
        ];
    }

    /**
     * @param $model
     * @param null $isActive
     * @param null $orderByAscColumn
     * @param null $orderByDescColumn
     * @return null
     */
    public function getTable($model, $isActive = null, $orderByAscColumn = null, $orderByDescColumn = null)
    {
        $returnData = null;

        try {
            if (v::stringType()->notEmpty()->validate($model)) {
                $returnData = Functions\Application::getTable($model,$isActive, $orderByAscColumn, $orderByDescColumn);
            }
        }
        catch (\Exception $e) {
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
    public function getRow($model, $isActive = null, $id)
    {
        $returnData = null;

        try {
            if (v::stringType()->notEmpty()->validate($model)) {
                $returnData = Functions\Application::getRow($model,$isActive, $id);
            }
        }
        catch (\Exception $e) {
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
    public function getColumn($model, $isActive = null, $id, $column)
    {
        $returnData = null;

        try {
            if (v::stringType()->notEmpty()->validate($model)) {
                $returnData = Functions\Application::getColumn($model,$isActive, $id, $column);
            }
        }
        catch (\Exception $e) {
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
    public function getCount($model, $isActive = null)
    {
        $returnData = null;

        try {
            if (v::stringType()->notEmpty()->validate($model)) {
                $returnData = Functions\Application::getCount($model,$isActive);
            }
        }
        catch (\Exception $e) {
            //region Log
            $container->logger->addCritical($e->getMessage());
            //endregion
        }

        return $returnData;
    }
    //endregion
}
