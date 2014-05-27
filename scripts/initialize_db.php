<?php
  require_once(dirname(__FILE__) . "/../inc/commons.inc.php");
  
  $oMysqli = getMysqli();

  echo "Creating student table...";
	$oMysqli->query("CREATE TABLE IF NOT EXISTS student (organisation_id INT, id INT, firstname VARCHAR(128), insertion VARCHAR(32), lastname VARCHAR(128), PRIMARY KEY (organisation_id, id));");
	echo "OK!\n";

  echo "Creating groupset table...";
	$oMysqli->query("CREATE TABLE IF NOT EXISTS groupset (organisation_id INT, name VARCHAR(32), owner_user_id INT, PRIMARY KEY (organisation_id, name));");
	echo "OK!\n";

  echo "Creating groupstudents table...";
	$oMysqli->query("CREATE TABLE IF NOT EXISTS groupsetstudents (organisation_id INT, group_name VARCHAR(32), student_id INT, PRIMARY KEY (organisation_id, group_name, student_id));");
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