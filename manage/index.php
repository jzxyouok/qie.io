<?php
$startTime = microtime();
require('../web.config.php');
require(APP_PATH.'/config/require.php');

new App('','',$startTime);