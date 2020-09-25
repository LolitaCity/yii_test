<?php

return [
	'class' => 'yii\db\Connection',
	'dsn' => 'mysql:host=' . \Yaconf::get("dev.test_mysql.host") . ';dbname=' . \Yaconf::get("dev.test_mysql.db_name"),
	'username' => \Yaconf::get("dev.test_mysql.user_name"),
	'password' => \Yaconf::get("dev.test_mysql.password"),
	'charset' => \Yaconf::get("dev.test_mysql.charset"),

	// Schema cache options (for production environment)
	//'enableSchemaCache' => true,
	//'schemaCacheDuration' => 60,
	//'schemaCache' => 'cache',
];
