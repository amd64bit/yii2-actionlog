<?php

namespace atans\actionlog;

use Yii;
use yii\base\BootstrapInterface;
use yii\web\Application as WebApplication;
use yii\i18n\PhpMessageSource;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof WebApplication) {
            if (! isset($app->i18n->translations['actionlog*'])) {
                $app->i18n->translations['actionlog*'] = [
                    'class'    => PhpMessageSource::className(),
                    'basePath' => __DIR__ . '/messages',
                ];
            }

        }
    }
}
