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
	public $envPath = 'application.config.environments';

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
		$envPath = Yii::getPathOfAlias($this->envPath);

		if (!is_dir($envPath) && !mkdir($envPath, 0777, true))
			throw new CException('Failed to create the environments directory.');

		$envFile = $envPath . DIRECTORY_SEPARATOR . 'env';

		if (!file_exists($envFile))
			@chmod($envFile, 0644);

		file_put_contents($envFile, $envName);
		echo "\nEnvironment set to `{$envName}`.\n";
		return 0; // all ok.
	}
}
