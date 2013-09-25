<?php defined('SYSPATH') or die('No direct script access.');

class Task_Migrate_Init extends Stillman\Kohana\Minion\Task {

	protected function _execute(array $params)
	{
		echo "Initializing migrate tool...";

		$applied_migrations = DB::query(NULL, $this->_config['create_table_sql'])
			->execute($this->_config['database_group']);

		echo "Done\n";
	}

}