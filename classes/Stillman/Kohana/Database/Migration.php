<?php

namespace Stillman\Kohana\Database;

abstract class Migration {

	abstract public function up();

	public function down()
	{
		echo "Migration ".get_class($this)." does not support migration down\n";
		return FALSE;
	}

}