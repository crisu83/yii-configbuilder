<?php
/**
 * ConfigBuilder class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Helper for building application configurations.
 */
class ConfigBuilder
{
	/**
	 * Builds a configuration from the given array.
	 * @param array $array the configuration parts.
	 * @return array the configuration.
	 */
	public static function build($array)
	{
		$result = array();
		if (!is_array($array))
			$array = array($array);
		foreach ($array as $config)
		{
			if (is_string($config))
			{
				if (!file_exists($config))
					continue;
				$config = require($config);
			}
			if (!is_array($config))
				continue;
			$result = self::mergeArray($result, $config);
		}
		return $result;
	}

	/**
	 * Builds a configuration from the given array.
	 * @param array $array the configuration parts.
	 * @param string $envPath the path to the environment configurations.
	 * @return array the configuration.
	 */
	public static function buildForEnv($array, $envFile)
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
		return self::build($array);
	}

	/**
	 * Merges two or more arrays into one recursively.
	 * @param array $a array to be merged to
	 * @param array $b array to be merged from.
	 * @return array the merged array.
	 */
	protected static function mergeArray($a, $b)
	{
		$args = func_get_args();
		$res = array_shift($args);
		while (!empty($args))
		{
			$next = array_shift($args);
			foreach ($next as $k => $v)
			{
				if (is_integer($k))
					isset($res[$k]) ? $res[] = $v : $res[$k] = $v;
				else if (is_array($v) && isset($res[$k]) && is_array($res[$k]))
					$res[$k] = self::mergeArray($res[$k],$v);
				else
					$res[$k] = $v;
			}
		}
		return $res;
	}
}
