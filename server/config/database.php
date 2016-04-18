<?php
/*
 * 数据库配置文件
 *
 * 如果为localhost，可以留空；如果是默认端口，port写0或者3306
 */

$DBList = array('default'=>array('user'=>'qie',
					'password'=>'qiezi123',
					'host'=>'localhost', //如果为localhost，可以留空
					'db'=>'qiezi',
					'port'=>3306, //如果是默认端口，写0或者3306
					'charset'=>'utf8', //可以不填
					'prefix'=>''),
					'test'=>array('user'=>'test_user',
					'password'=>'test_qiezi123',
					'host'=>'localhost', //如果为localhost，可以留空
					'db'=>'test_qiezi',
					'port'=>3308, //如果是默认端口，写0或者3306
					'charset'=>'utf8', //可以不填
					'prefix'=>'')
				);