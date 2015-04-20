<?php
include 'Week.php';

$months = array("January", "February", "March", "April", "May", "June",
		"July", "August", "September", "October", "November",
		"December");

$apptTimes = array('9:00', '9:30', '10:00', '10:30', '11:00',
		   '11:30', '12:00', '12:30', '1:00', '1:30',
		   '2:00', '2:30', '3:00', '3:30');

class Calendar {
  var $weeks = array();

  function Calendar(){
    global $debug;
    global $COMMON;

    $sql = "SELECT * FROM CalendarWeeks";
    $record = $COMMON->executeQuery($sql, $_SERVER["Week.php"]);

    if($record !== false){
      for( $i = 0; $i < mysql_num_rows($record); $i++){
	$date = mysql_result($record, $i, 'monday');
	$dateArray = explode( "_", $date, 3);
	$this->weeks[] = new Week($dateArray[0], $dateArray[1], $dateArray[2]);
      }
    }
  }

  function add_week($month, $day, $year){
    $newWeek = true;
    $date = $month."_".$day."_".$year;
    foreach($this->weeks as $week){
      if($week->dates[0] == $date){
	echo "<div id=\"error\">This week is already in your calendar</div>";
	$newWeek = false;
      }
    }
    if($newWeek){
      $this->weeks[] = new Week($month, $day, $year);
    }
  }

  function remove_week($monday){
    for($i = 0; $i < count($this->weeks); $i++) {
      $week = $this->weeks[$i];
      if($week->dates[0] == $monday ){
	$week->remove_tables();
	array_splice($this->weeks, $i, 1, NULL);
	foreach($this->weeks as $week){
	  echo "New CalWeeks Array: <br>".array_search($week, $this->weeks);
	  echo " ".$week."<br>" ;
	}
	echo " true ";

      }
      else echo " false ";
    }
  }

}//end of Calendar class

function short_string($date) {
  $dateArray = explode( "_", $date, 3);
  return $dateArray[0]."/".$dateArray[1];
}

function date_to_string($date){
  global $months;
  $dateArray = explode( "_", $date, 3);
  $result = $months[(int)$dateArray[0]-1]." ".$dateArray[1];
  $result .= ", 20".$dateArray[2];
  return day_of_week($date).", ".$result;
}

function day_of_week($date){
  $dateArray = explode( "_", $date, 3);
  return jddayofweek(cal_to_jd(CAL_GREGORIAN, date($dateArray[0]), date($dateArray[1]), date($dateArray[2])), 1);
}

?>