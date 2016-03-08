<?php
namespace Application\Service;

class DataClass extends \stdClass
{
    public function __call($method, $args)
    {
        if (isset($this->$method)) {
            $func = $this->$method;
            return call_user_func_array($func, $args);
        }
    }

    public static function toObject(Array $arr)
    {
        $result = new DataClass();
        foreach ($arr as $key => $value) {
            $result->$key = $value;
        }

        return $result;
    }
}