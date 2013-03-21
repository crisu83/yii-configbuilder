<?php
/**
 * EnvConfigBuilder class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

require_once(__DIR__ . '/ConfigBuilder.php');

/**
 * Helper for building application configurations that automatically applies the environment config last.
 */
class EnvConfigBuilder extends ConfigBuilder
{
	/**
	 * Builds a configuration from the given array.
	 * @param array $array the configuration parts.
	 * @param string $envPath the path to the environment configurations.
	 * @return array the configuration.
	 */
	public static function build($array, $envPath)
	{
		$result = parent::build($array);
		$envFile = $envPath . DIRECTORY_SEPARATOR . 'env';
		if (!file_exists($envFile))
			throw new CException('Environment not set.');
		$envName = file_get_contents($envFile);
		$envConfig = $envPath . DIRECTORY_SEPARATOR . $envName . '.php';
		if (!file_exists($envConfig))
			throw new CException('Environment file does not exist.');
		$config = require($envConfig);
		if (!is_array($config))
			throw new CException('Environment file does not return an array.');
		return CMap::mergeArray($result, $config);
	}
}