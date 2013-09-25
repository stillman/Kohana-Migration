<?php

namespace Stillman\Kohana\Minion;

abstract class Task extends \Minion_Task {

	protected $_config;

	public function __construct()
	{
		parent::__construct();
		$this->_config = \Kohana::$config->load('migration');
	}
}