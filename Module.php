<?php

namespace atans\actionlog;

use Yii;
use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    /**
     * @var array
     */
    public $adminRoles = ['admin'];

    /**
     * @var string
     */
    public $usernameAttribute = 'username';
}