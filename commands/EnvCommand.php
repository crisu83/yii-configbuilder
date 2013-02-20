<?php
/**
 * EnvCommand class file.
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class EnvCommand extends CConsoleCommand
{

	private $_envPath;
	private $_configPath;

	public function getHelp()
	{
		return <<<EOD
USAGE
  yiic env <env> <environments-path> <config-path>

DESCRIPTION
  This command retrieves the environment configuration from the "environments folder" and adds it to a "local.php"
  config file. If the environment configuration file is not found, it will create an empty file on the config folder.

  Note: This command should be used with "ConfigBuilder" at your "index.php" bootstrap file and make sure it overrides
  the rest of the configuration files. For example:

	\$builder = __DIR__ . '/../app/vendor/yii-configbuilder/ConfigBuilder.php';

	require_once(\$builder);

	\$config = ConfigBuilder::build(array(
		__DIR__ . '/../app/config/common.php',
		__DIR__ . '/../app/config/web.php',
		__DIR__ . '/../app/config/main.php',
		__DIR__ . '/../app/config/local.php',
	));

PARAMETERS
 * env: required, the environment type.
 * environments-path: required, the directory where to locate the environments.
 * config-path: required, the directory where to locate the yii config files.
EOD;
	}

	public function run($args)
	{
		if (!isset($args[0]))
			$this->usageError('the environment type is not specified.');
		if (!isset($args[1]))
			$this->usageError('the environments directory location is not specified.');
		if (!isset($args[2]))
			$this->usageError('the application config files directory location is not specified.');

		$this->_envPath = $this->getRealPath($args[1]);
		$this->_configPath = $this->getRealPath($args[2]);
		$envType = $args[0];
		$envFile = $this->_envPath . DIRECTORY_SEPARATOR . $envType . ".php";

		// if environment file do not exists, create one (maybe the user wishes to configure it afterwards)
		// but in order to get the new configuration, it will have to run this command again.
		if (!file_exists($envType))
		{
			file_put_contents($envFile, "<?php\nreturn array(\n\t'env.code' => '{$envType}',\n);");
			@chmod($envFile, 0644);
		}

		$configFile = $this->_configPath . DIRECTORY_SEPARATOR . "local.php";
		if (!copy($envFile, $configFile))
			$this->usageError("failed to copy {$envFile} to {$configFile}");

		@chmod($configFile, 0644);
		echo "\nYour environment configuration file has been created successfully under {$this->_configPath}.\n";

	}

	protected function getRealPath($path)
	{
		$path = strtr($path, '/\\', DIRECTORY_SEPARATOR);
		if (strpos($path, DIRECTORY_SEPARATOR) === false)
			$path = '.' . DIRECTORY_SEPARATOR . $path;
		if (basename($path) == '..')
			$path .= DIRECTORY_SEPARATOR . '.';
		$dir = rtrim(realpath(dirname($path)), '\\/');
		if ($dir === false || !is_dir($dir))
			$this->usageError("The directory '$path' is not valid. Please make sure the parent directory exists.");

		return (basename($path) === '.')
			? $dir
			: $dir . DIRECTORY_SEPARATOR . basename($path);
	}
}
