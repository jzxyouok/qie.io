<?php
class App {
	function __construct() {
		echo 'app_ini<br>';
		$conn = mysql_connect('localhost', 'qiezi', 'qiezi123');
		var_dump($conn);
	}
}