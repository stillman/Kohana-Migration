<?php defined('SYSPATH') or die('No direct script access.');

class Task_Migrate_Down extends Stillman\Kohana\Minion\Task {

	protected $_options = array(
		'steps' => NULL,
	);

	protected function _execute(array $params)
	{
		// Migrate one step down by default
		$params['steps'] === NULL and $params['steps'] = 1;

		echo "Migrating down";

		$migrations = DB::select('version')
			->from($this->_config['table_name'])
			->order_by('apply_time', 'DESC')
			->limit($params['steps'])
			->execute($this->_config['database_group'])
			->as_array(NULL, 'version');

		foreach ($migrations as $file)
		{
			echo "Reverting migration $file...";
			$migration = $this->_load_migration($file);

			try
			{
				$result = $migration->down();
			}
			catch (Exception $e)
			{
				echo "ERROR: ".$e->getMessage()."\n";
				$result = FALSE;
			}

			if ($result !== TRUE)
			{
				echo "Migration $file returned FALSE, exiting.\n";
				return;
			}
			else
			{
				DB::delete($this->_config['table_name'])
					->where('version', '=', $file)
					->execute($this->_config['database_group']);

				echo "Success\n";
			}
		}
	}

	public function confirm($message, $default = FALSE)
	{
		echo $message.' (yes|no) [' . ($default ? 'yes' : 'no') . ']:';

		$input = trim(fgets(STDIN));
		return empty($input) ? $default : ! strncasecmp($input, 'y', 1);
	}

	protected function _load_migration($migration)
	{
		require_once $this->_config['path'].$migration.'.php';
		return new $migration;
	}

}