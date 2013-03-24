yii-configbuilder
=================

Helper for building application configurations for the Yii PHP framework.

## Basic configuration

### Usage

To use the config builder you simply need to load it in your entry script:

```php
<?php
$yii = __DIR__ . '/path/to/yii.php';
$builder = __DIR__ . '/path/to/ConfigBuilder.php';

require_once($yii);
require_once($builder);

$config = ConfigBuilder::build(array(
    __DIR__ . '/protected/config/common.php',
    __DIR__ . '/protected/config/web.php',
    __DIR__ . '/protected/config/dev.php',
    __DIR__ . '/protected/config/local.php',
));

Yii::createWebApplication($config)->run();
```
***index.php***

## Environment specific configurations

If you need different environments in your application you can use the **EnvConfigBuilder** together with the **EnvCommand** to easily set environment specific configurations. 

### Configuration

Add the env command to your console config file (usually **protected/config/console.php**):

```php
// console application configuration
return array(
    .....
    'commandMap' => array(
        'env' => array(
            'class' => 'path.alias.to.EnvCommand',
            // optional configurations
            'runtimePath' => 'application.runtime', // the path to the application runtime folder
            'envPaths' => array('application.config.environments'), // the paths to application environment configs
        ),
    ),
);
```
***console.php***

### Usage

Update your entry script (usually **index.php**) to use the **EnvConfigBuilder**:

```php
<?php
$yii = __DIR__ . '/path/to/yii.php';
$builder = __DIR__ . '/path/to/EnvConfigBuilder.php';

require_once($yii);
require_once($builder);

$config = EnvConfigBuilder::build(array(
    __DIR__ . '/protected/config/common.php',
    __DIR__ . '/protected/config/web.php',
    __DIR__ . '/protected/config/environments/{environment}.php',
    __DIR__ . '/protected/config/local.php',
), __DIR__ . '/protected/runtime/environment');

Yii::createWebApplication($config)->run();
```
***index.php***

Now you can use the env command to set the active environment (replace `{environment}` is the name of the environment):

```
yiic env {environment}
```

*Note: You do not need to create the environment configuration files, the command will prompt you to create them if they do not exist.*
