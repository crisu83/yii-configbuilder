yii-configbuilder
=================

Helper for building application configurations for the Yii PHP framework.

## Usage

To use the config builder you simply need to load it in your entry script:

```php
<?php
$yii = __DIR__ . '/../app/vendor/yiisoft/yii/framework/yii.php';
$builder = __DIR__ . '/../app/helpers/ConfigBuilder.php';

require_once($yii);
require_once($builder);

$config = ConfigBuilder::build(array(
    __DIR__ . '/../app/config/common.php',
    __DIR__ . '/../app/config/web.php',
    __DIR__ . '/../app/config/dev.php',
    __DIR__ . '/../app/config/local.php',
));

Yii::createWebApplication($config)->run();
```
***index.php***
