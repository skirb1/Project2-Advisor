<?php
class Week {
  var $dates = array();

  //for adding weeks to the calendar
  function Week($month, $day, $year) {
    //there won't be advising appts new years week
    //so we know the year will be the same
    $yearStr = "_" . (string)$year;
    $year = (int)("20".$year);

    //set the dates of the week
    //check for the end of the month
    for($i = 0; $i < 5; $i++){
      if($day > cal_days_in_month(CAL_GREGORIAN, $month, $year)){
	$month++;
	$day = 1;
	$monthStr = (string)$month . "_";
        $this->dates[$i] = $monthStr . (string)($day) . $yearStr;
      }
      else{
        $monthStr = (string)$month . "_";
	$this->dates[$i] = $monthStr . (string)($day) . $yearStr;
      }
      $day += 1;
    }

    if($this->update_CalendarWeeks()){
      echo "Calendar updated";
      $this->create_tables();
    }

  }

  function update_CalendarWeeks(){
    global $debug;
    global $COMMON;

    $monday = $this->dates[0];
    $sql = "SELECT * FROM CalendarWeeks WHERE monday = '$monday'";
    $record = $COMMON->executeQuery($sql, $_SERVER["Week.php"]);
    if($record === false){
      echo "div id=\"error\">Error accessing calendar</div>";
      return false;
    }
    else if(mysql_num_rows($record) > 0){
      return false;
    }
    else {
      $sql = "INSERT INTO `CalendarWeeks` ( `weekID`, `monday`, `tuesday`, ";
      $sql .= "`wednesday`, `thursday`, `friday` ) ";
      $sql .= "VALUES ( NULL ";
      foreach($this->dates as $date){
	$sql .= ", '".$date."'";
      }
      $sql .= " );";
      $record = $COMMON->executeQuery($sql, $_SERVER["Week.php"]);
      if($record !== false){
	return true;
      }
      else{
	return false;
      }
    }
  }

  function create_tables(){
    global $debug;
    global $COMMON;

    foreach($this->dates as $date){
      //create table for indiv appts
      $sql = "CREATE TABLE `".$date."` (";
      $sql .= "`recordID` int(10) unsigned NOT NULL AUTO_INCREMENT, ";
      $sql .= "`advisorID` int(10) unsigned NOT NULL, ";
      $sql .= "`9:00` varchar(25), `9:30` varchar(25), ";
      $sql .= "`10:00` varchar(25), `10:30` varchar(25), ";
      $sql .= "`11:00` varchar(25), `11:30` varchar(25), ";
      $sql .= "`12:00` varchar(25), `12:30` varchar(25), ";
      $sql .= "`1:00` varchar(25), `1:30` varchar(25), ";
      $sql .= "`2:00` varchar(25), `2:30` varchar(25), ";
      $sql .= "`3:00` varchar(25), `3:30` varchar(25), ";
      $sql .= " PRIMARY KEY(recordID) )";
      $indiv = $COMMON->executeQuery($sql,  $_SERVER["Week.php"]);
      echo $indiv." ";
      $this->initialize_indiv_table($date);

      //create table for group appts
      $sql = "CREATE TABLE ".$date."Groups (";
      $sql .= "groupID int(10) unsigned NOT NULL AUTO_INCREMENT, ";
      $sql .= "time varchar(5) NOT NULL, ";
      $sql .= "advisor1 int(10) unsigned, ";
      $sql .= "advisor2 int(10) unsigned, ";
      $sql .= "advisor3 int(10) unsigned, ";
      $sql .= "advisor4 int(10) unsigned, ";
      $sql .= "student1 varchar(8), student2 varchar(8), ";
      $sql .= "student3 varchar(8), student4 varchar(8), ";
      $sql .= "student5 varchar(8), student6 varchar(8), ";
      $sql .= "student7 varchar(8), student8 varchar(8), ";
      $sql .= "student9 varchar(8), student10 varchar(8), ";
      $sql .= "PRIMARY KEY(groupID) )";
      $group = $COMMON->executeQuery($sql,  $_SERVER["Week.php"]);
      echo $group." ";
      $this->initialize_group_table($date);
    }
  }

  function initialize_group_table($date){
    global $debug;
    global $COMMON;
    global $apptTimes;

    //add empty row for each time
    foreach($apptTimes as $time){
      $sql = "INSERT INTO `".$date."Groups` (";
      $sql .= "`groupID`, `time`, `advisor1`, `advisor2`,";
      $sql .= "`advisor3`, `advisor4`, `student1`, `student2`,";
      $sql .= "`student3`, `student4`, `student5`, `student6`,";
      $sql .= "`student7`, `student8`, `student9`, `student10` )";
      $sql .= " VALUES ( NULL ,  '".$time."', NULL , NULL , NULL , ";
      $sql .= "NULL , NULL , NULL , NULL , NULL , NULL , NULL , ";
      $sql .= "NULL , NULL , NULL , NULL);";

      $record = $COMMON->executeQuery($sql, $_SERVER["Week.php"]);
      if($record === false){
	echo "<div id=\"error\">Error initializing group table</div>";
      }
    }
  }

  function initialize_indiv_table($date){
    global $debug;
    global $COMMON;
    global $apptTimes;

    $sql = "SELECT * FROM `Advisors`";
    $advisors = $COMMON->executeQuery($sql,  $_SERVER["Week.php"]);
    //get advisor IDs
    if($advisors !== false){
	for($i = 0; $i < mysql_num_rows($advisors); $i++){
	  $sql = "INSERT INTO `".$date."` ( `recordID`, `advisorID`";
	  foreach($apptTimes as $time){
	    $sql .= ", `".$time."`";
	  }
	  $sql .= ") VALUES ( NULL, '";
	  $sql .= mysql_result($advisors, $i, 'advisorID')."'";
	  foreach($apptTimes as $time){
	    $sql .= ", 'false'";
	  }
	  $sql .= " );";
	  
	  $record = $COMMON->executeQuery($sql, $_SERVER["Week.php"]);
	  if($record === false){
	    echo "<div id=\"error\">Error initializing individual table</div>";
	  }
	}//end of for
    }
  }//end of initialize_indiv_table

  function remove_tables(){
    global $debug;
    global $COMMON;

    foreach($this->dates as $date){
      //delete individual appt table
      $sqlIndiv = "DROP TABLE ".$date;
      $indiv = $COMMON->executeQuery($sqlIndiv, $_SERVER["Week.php"]);
      if($indiv === false){
	echo "<div id=\"error\">Error deleting individual appt table</div>";
      }
      //delete group appt table
      $sqlGroup = "DROP TABLE ".$date."Groups";
      $group = $COMMON->executeQuery($sqlGroup, $_SERVER["Week.php"]);
      if($group === false){
	echo "<div id=\"error\">Error deleting group appt table</div>";
      }
    }
    //delete week from CalendarWeeks table
    echo " ".$this->dates[0]." ";
    $sqlCal = "DELETE FROM `CalendarWeeks` WHERE `monday` = '";
    $sqlCal .= $this->dates[0]."'";
    $cal = $COMMON->executeQuery($sqlCal, $_SERVER["Week.php"]);
    if($cal === false){
      echo "<div id=\"error\">Error deleting week from calendar</div>";
    }

  }


  }//end of class Week
?>