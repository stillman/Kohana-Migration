<?php defined('SYSPATH') or die('No direct script access.');

class Task_Migrate_Create extends Stillman\Kohana\Minion\Task {

	protected function _execute(array $params)
	{
		isset($params[1]) or exit("Please enter migration name\n");

		$uniqid = str_replace('.', '_', uniqid('', TRUE));
		$class_name = 'm'.$uniqid.'_'.$params[1];
		$view = new View('Stillman/Kohana/Migrate/template');
		$view->class_name = $class_name;

		file_put_contents(
			$this->_config['path'].$class_name.'.php',
			$view->render()
		);

		echo "Migration $class_name created successfully.\n";
	}
}