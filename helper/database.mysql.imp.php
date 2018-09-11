<?php
/**
 * @file database.mysql.imp.php
 * @date 2018-09-10
 * @author Go Namhyeon <gnh1201@gmail.com>
 * @brief MySQLi database helper
 */

if(!function_exists("get_db_mysql_imp_connect")) {
	function get_db_mysql_imp_connect() {
		$conn = false;
		$config = get_config();

		$conn = @mysqli_connect($config['db_host'], $config['db_username'], $config['db_password'], $config['db_name']);

		$errno = @mysqli_connect_errno();
		if($errno) {
			set_error(sprintf("Failed to connect to MySQL: %s", $errno));
			show_errors();
		}

		return $conn;
	}
}

if(!function_exists("exec_db_mysql_imp_query")) {
	function exec_db_mysql_imp_query($sql, $bind) {
		$result = false;
		$dbc = get_dbc_object();

		$binded_sql = get_db_binded_sql($sql, $bind);
		$result = @mysqli_query($dbc, $binded_sql);

		return $result;
	}
}