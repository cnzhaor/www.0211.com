<?php
error_reporting(0);
mysql_connect('localhost', 'root', '');
mysql_select_db('test');

$fp = fopen('./a.lock', 'r');
flock($fp, LOCK_EX);        // 多个人只有一个能抢到 文件 ，其他人阻塞在这。。

$rs = mysql_query('SELECT id FROM a');
$id = mysql_result($rs, 0, 0);
if($id > 0)
{
	$id--;
	mysql_query('UPDATE a SET id='.$id);
}

flock($fp, LOCK_UN);  // 释放锁
fclose($fp);



