<?php

namespace app\controllers;

use app\traits\SerialService;
use app\traits\Template;

abstract class Base
{
    use Template, SerialService;
}
