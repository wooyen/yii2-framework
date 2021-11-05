<?php

return yii\helpers\ArrayHelper::merge(
    require dirname(dirname(__DIR__)) . '/common/config/codeception.php',
    require __DIR__ . '/main.php',
    require __DIR__ . "/main-{$yii_env['ENV']}.php",
    require __DIR__ . '/test.php',
    require __DIR__ . "/test-{$yii_env['ENV']}.php",
    [
    ]
);
