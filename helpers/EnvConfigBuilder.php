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
	public static function build($array, $envFile)
	{
		if (file_exists($envFile))
		{
			$envName = file_get_contents($envFile);
			foreach ($array as $i => $config)
			{
				if (is_string($config))
					$array[$i] = str_replace('{environment}', $envName, $config);
			}
		}
		return parent::build($array);
	}
}