<?php
header("content-type: text/html; charset=utf-8");
ignore_user_abort();//關掉瀏覽器，PHP腳本也可以繼續執行.
set_time_limit(0);// 限制腳本執行時間

while(true){
	$db = new PDO("mysql:host=localhost;dbname=Rdone;port=3306", "root", "");
	$db->exec("set names utf8");
	$select = $db->query("SELECT * FROM raceData");
	$result = $select->fetchAll();

	$redis = new Redis();
	$redis->connect('127.0.0.1', 6379);
	$redis->set("foo", json_encode($result));

	sleep(60);
}
?>