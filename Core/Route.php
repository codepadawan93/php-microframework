<?php

namespace Core;

/*
*  A Route entity for storing infoirmation about mapping URL patterns to functions
*/
class Route
{
    public $name;
    public $pattern;
    public $class;
    public $method;
    public $params;
}
