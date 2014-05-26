<?php
  require_once(dirname(__FILE__) . "/../inc/commons.inc.php");
  
  $oMysqli = getMysqli();

  echo "Creating student table...";
	$oMysqli->query("CREATE TABLE IF NOT EXISTS student (id INT, firstname VARCHAR(128), insertion VARCHAR(32), lastnaam VARCHAR(128), PRIMARY KEY (id));");
	echo "OK!\n";

	echo "Creating lesson part table...";
	$oMysqli->query("CREATE TABLE IF NOT EXISTS lesson (code VARCHAR(16), name VARCHAR(128), PRIMARY KEY (code));");
	echo "OK!\n";

	echo "Creating activity table...";
	$oMysqli->query("CREATE TABLE IF NOT EXISTS activity (lesson_code VARCHAR(16), number INT, description VARCHAR(128), PRIMARY KEY (lesson_code, number));");
	echo "OK!\n";
	
	echo "Creating presence table...";
	$oMysqli->query("CREATE TABLE IF NOT EXISTS presence (lesson_code VARCHAR(16), number INT, student_id INT, PRIMARY KEY (lesson_code, number, student_id));");
	echo "OK!\n";
?>