<?php

class Task_Migrate_Mark extends Stillman\Kohana\Minion\Task
{
	protected function _execute(array $params)
	{
		if ( ! isset($params[1]))
		{
			echo "Specify migration name (--name=migration)\n";
			return;
		}

		$name = $params[1];

		if ( ! file_exists($this->_config['path'].$name.'.php'))
		{
			echo "Migration not found";
			return;
		}

		$exists = DB::query(Database::SELECT, "SELECT 1 `version` FROM {$this->_config['table_name']} WHERE `version` = :version")
			->param(':version', $name)
			->execute()
			->get('version');

		if ($exists)
		{
			echo "Migration has been already applied or marked\n";
			return;
		}

		DB::insert($this->_config['table_name'], ['version', 'apply_time'])
			->values([$name, time()])
			->execute($this->_config['database_group']);

		echo "Migration marked\n";
	}
}