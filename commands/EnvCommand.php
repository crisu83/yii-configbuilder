<?php
/**
 * EnvCommand class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Console command that sets the active environment.
 */
class EnvCommand extends CConsoleCommand
{
	/**
	 * @var string the path to the environment configurations.
	 */
	public $runtimePath = 'application.runtime';
	/**
	 * @var array a list of application environment paths.
	 */
	public $envPaths = array('application.config.environments');
	/**
	 * @var string the name of the file for holding the environment name.
	 */
	public $envFile = 'environment';

	/**
	 * Provides the command description.
	 * @return string the command description.
	 */
	public function getHelp()
	{
		return <<<EOD
USAGE
  yiic env <name>

DESCRIPTION
  This command saves the name of the active environment
  in a file under the environments folder.

  Note: This command should be used with "EnvConfigBuilder"
  in your "index.php" bootstrap file.

EXAMPLES
 * yiic env dev
   Sets the environment to 'dev'.
EOD;
	}

	/**
	 * Executes the command.
	 * @param array $args command line parameters for this command.
	 * @return integer application exit code.
	 */
	public function run($args)
	{
		if (!isset($args[0]))
			$this->usageError('The environment name is not specified.');

		$envName = $args[0];
		$runtimePath = Yii::getPathOfAlias($this->runtimePath);

		if (!is_dir($runtimePath) && !mkdir($runtimePath, 0777, true))
			throw new CException('Failed to create the runtime directory.');

		$envFile = $runtimePath . DIRECTORY_SEPARATOR . $this->envFile;

		if (!file_exists($envFile))
			@chmod($envFile, 0644);

		file_put_contents($envFile, $envName);

		foreach ($this->envPaths as $path)
		{
			$envPath = Yii::getPathOfAlias($path);

			if (!is_dir($envPath) && !mkdir($envPath, 0777, true))
				throw new CException('Failed to create the environments directory.');

			$configFile = $envPath . DIRECTORY_SEPARATOR . $envName . '.php';
			if (!file_exists($configFile))
			{
				if (!$this->confirm("File `{$configFile}` does not exist, do you want to create it?", true))
					continue;

				file_put_contents($configFile, "<?php\n// {$envName} environment configuration.\nreturn array(\n);\n");
				@chmod($configFile, 0664);
				echo "Configuration file created.`\n";
			}
		}

		echo "Environment set to `{$envName}`.\n";
		return 0;
	}
}
