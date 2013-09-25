<?php defined('SYSPATH') or die('No direct script access.');

class Task_Migrate_Up extends Stillman\Kohana\Minion\Task {

	protected function _execute(array $params)
	{
		$all_migrations = array();

		$applied_migrations = DB::select()
			->from($this->_config['table_name'])
			->execute($this->_config['database_group'])
			->as_array('version', 'apply_time');

		foreach (glob($this->_config['path'].'m*.php') as $file)
		{
			$all_migrations[pathinfo($file, PATHINFO_FILENAME)] = 1;
		}

		$unapplied_migrations = array_keys(array_diff_key($all_migrations, $applied_migrations));

		if ( ! $unapplied_migrations)
		{
			echo "Everything is up to date.\n";
			return;
		}

		echo "Migrations to apply:\n";
		echo implode("\n", $unapplied_migrations)."\n\n";
		//$this->confirm("Apply?");

		foreach ($unapplied_migrations as $file)
		{
			echo "Applying migration $file...";
			$migration = $this->_load_migration($file);

			try
			{
				$result = $migration->up();
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
				DB::insert($this->_config['table_name'], array('version', 'apply_time'))
					->values(array($file, time()))
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