<?php
/**
 * Roosterious API Dispatcher
 */
require_once(dirname(__FILE__) . "/../../inc/commons.inc.php");
require_once(dirname(__FILE__) . "/../../inc/lib/flight/flight/Flight.php");

/**
 * Format a database result to output format
 */
function dbResultToFormat($oResult, $sFormat) {
  if ($sFormat == "json") {
    $sJson = "{\"status\": \"ok\", \"response\": [";
    $bFirst = true;
    while($aObject = $oResult->fetch_assoc()) {
      if (!$bFirst) {
      	$sJson .= ",";
      } else {
      	$bFirst = false;
      }
 	  $sJson .= json_encode($aObject);
    }
    $sJson .= "]}";
    return $sJson;
  } else if ($sFormat == "csv") {
    $sCsv = "";
    while($aObject = $oResult->fetch_assoc()) {
      $aPropNames = array_keys($aObject);
      foreach ($aPropNames as $sPropName) {
        $sCsv .= $aObject[$sPropName] . ";";
      }
      $sCsv .= "\n";
    }
    return $sCsv;
  }
}

function errorInFormat() {
  return "{\"status\": \"error\", \"message\": \"Error in database query\"}";
}

// ============================================================================
// STUDENT RESOURCE API CALLS
// ============================================================================

/**
 * Add students in CSV format and returns a list of added student in JSON format
 */
Flight::route('POST /students\.json', function(){
  $oRequest = Flight::request();
  $sBody = $oRequest->body;
  $sFileName = $oRequest->files->csvfile['tmp_name'];

  $aValues = csv_to_array($sFileName);

  $sJson = "{\"status\": \"ok\", \"response\": [";
  $bFirst = true;
  $oMysqli = getMysqli();
  foreach ($aValues as $iIndex => $aStudent) {
      $iId = $aStudent['id'];
      $sLastname = $aStudent['lastname'];
      $sInsertion = $aStudent['insertion'];
      $sFirstname = $aStudent['firstname'];
  
      $sQuery = "INSERT INTO student VALUES ($iId, \"$sLastname\", \"$sInsertion\", \"$sFirstname\");";
  
      if ($oResult = $oMysqli->query($sQuery)) {
        if ($bFirst) {
          $bFirst = false;
          $sJson .= "{\"id\": $iId, \"lastname\": $sLastname, \"insertion\": $sInsertion, \"firstname\": $sFirstname}";
        } else {
          $sJson .= ",{\"id\": $iId, \"lastname\": $sLastname, \"insertion\": $sInsertion, \"firstname\": $sFirstname}";
        }
      }
  }
  $sJson .= "]}";

  echo $sJson;
});

/**
 * Get the list of all students in the given format
 */
Flight::route('GET /students\.@sFormat', function($sFormat){
  $oMysqli = getMysqli();
  $sQuery = "SELECT * FROM student;";    
  if ($oResult = $oMysqli->query($sQuery)) {
    echo dbResultToFormat($oResult, $sFormat); 
  } else {
    echo errorInFormat();
  }
});

/**
 * Get the properties of one student in the given format
 */
Flight::route('GET /students/@sStudentId\.@sFormat', function($sStudentId, $sFormat){
  $oMysqli = getMysqli();
  preg_match('/^[0-9]*$/', $sStudentId, $aMatches);
  $sStudentId = $aMatches[0];
  $sQuery = "SELECT * FROM student WHERE id=$sStudentId;";    
  if ($oResult = $oMysqli->query($sQuery)) {
    echo dbResultToFormat($oResult, $sFormat); 
  } else {
    echo errorInFormat();
  }
});


// ============================================================================
// LESSON RESOURCE API CALLS
// ============================================================================








/**
 * Get's the schedule for a specified class
 */
Flight::route('GET /schedule/class/@sClassId\.@sFormat', function($sClassId, $sFormat){
    $oMysqli = getMysqli();
    $sQuery = generateLessonQuery("FROM lesson,lessonclasses WHERE lesson.id = lessonclasses.lesson_id AND class_id = \"" . $sClassId . "\" AND date >= " . getFromDateString() . " ORDER BY date, starttime");
    
    if ($oResult = $oMysqli->query($sQuery)) {
      echo formatLessonResult($sFormat, $oResult); 
    } else {
      echo errorInFormat($sFormat);
    }
});

/**
 * Get's the schedule for a specified room
 */
Flight::route('GET /schedule/room/@sRoomId\.@sFormat', function($sRoomId, $sFormat){
    $oMysqli = getMysqli();
    $sQuery = generateLessonQuery("FROM lesson,lessonrooms WHERE lesson.id = lessonrooms.lesson_id AND room_id = \"" . $sRoomId . "\" AND date >= " . getFromDateString() . " ORDER BY date, starttime");
    
    if ($oResult = $oMysqli->query($sQuery)) {
      echo formatLessonResult($sFormat, $oResult); 
    } else {
      echo errorInFormat($sFormat);
    }
});

/**
 * Get's the schedule for a specified activity
 */
Flight::route('GET /schedule/activity/@sActivityId\.@sFormat', function($sActivityId, $sFormat){
    $oMysqli = getMysqli();
    $sQuery = generateLessonQuery("FROM lesson WHERE activity_id = \"" . $sActivityId . "\" AND date >= " . getFromDateString() . " ORDER BY date, starttime");
    
    if ($oResult = $oMysqli->query($sQuery)) {
      echo formatLessonResult($sFormat, $oResult); 
    } else {
      echo errorInFormat($sFormat);
    }
});

/**
 * Get's the schedule for now
 */
Flight::route('GET /schedule/now.@sFormat', function($sFormat){
	$oMysqli = getMysqli();
	
	$sQuery = generateLessonQuery("FROM lesson, lessonrooms WHERE lesson.id = lessonrooms.lesson_id AND date = DATE_FORMAT(NOW(),\"%Y-%m-%d\") AND starttime < NOW() AND endtime > NOW() ORDER BY starttime, room_id;");
	
    if ($oResult = $oMysqli->query($sQuery)) {
      echo formatLessonResult($sFormat, $oResult); 
    } else {
      echo errorInFormat($sFormat);
    }	
});


/**
 * Get's the stats for lecturer
 */
 /**
Flight::route('GET /stats/lecturer/@sLecturerId.json', function($sLecturerId) {
	$oMysqli = getMysqli();
	
	$sQuery = "SELECT DISTINCT YEARWEEK(date) AS weeknr, activity_id AS activity_id_summary, (SELECT count(*) AS number FROM lesson,lessonlecturers WHERE activity_id = activity_id_summary AND lesson.id = lessonlecturers.lesson_id AND lecturer_id=\"" . $sLecturerId . "\" AND YEARWEEK(date) = weeknr) AS number FROM lesson,lessonlecturers WHERE lesson.id = lessonlecturers.lesson_id AND lecturer_id = \"" . $sLecturerId . "\" ORDER BY weeknr, activity_id_summary;";
    if ($oResult = $oMysqli->query($sQuery)) {
      echo formatLessonResult("json", $oResult); 
    } else {
      echo errorInFormat($sFormat);
    }	
}); 
**/
Flight::start();
?>
