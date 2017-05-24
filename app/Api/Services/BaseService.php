<?php

namespace GetCandy\Api\Services;

abstract class BaseService
{
    public function __call($name, $arguments)
    {
        $method = $this->normaliseMethodName($name);

        switch (substr($name, 0, 4)) {
            case 'data':
                $class = $this->repo;
                break;
            default:
                $class = $this;
                break;
        }
        if (method_exists($class, $method)) {
            return call_user_func_array([$class, $method], $arguments);
        }
    }

    private function normaliseMethodName($name)
    {
        return camel_case(str_replace('data', '', $name));
    }
}
