<?php
$startTime = microtime();
require_once('web.config.php');
require_once(APP_PATH.'/config/require.php');

new App('','',$startTime);