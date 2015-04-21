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

      //group advising starts 3/2
      $this->weeks[] = new Week(3, 2, 15);
      $this->weeks[] = new Week(3, 9, 15);
      $this->weeks[] = new Week(3, 16, 15);
      //individual advising starts 3/23
      $this->weeks[] = new Week(3, 23, 15);
      $this->weeks[] = new Week(3, 30, 15);
      $this->weeks[] = new Week(4, 6, 15);
      $this->weeks[] = new Week(4, 13, 15);
      $this->weeks[] = new Week(4, 20, 15);
      $this->weeks[] = new Week(4, 27, 15);

  }

/*  function add_week($month, $day, $year){
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
  }*/

}//end of Calendar class

function short_string($date) {
  $dateArray = explode( "-", $date, 3);
    $month = $dateArray[1];
    $day = $dateArray[2];
    if($month[0] == "0"){
        $month = substr($month, 1, 1);
    }
    if($day[0] == "0"){
        $day = substr($day, 1, 1);
    }
  return $month."/".$day;
}

function date_to_string($date){
  global $months;
  $dateArray = explode( "-", $date, 3);
  $result = $months[(int)$dateArray[1]-1]." ".$dateArray[2];
  $result .= ", ".$dateArray[0];
  return day_of_week($date).", ".$result;
}

function day_of_week($date){
  $dateArray = explode( "-", $date, 3);
  return jddayofweek(cal_to_jd(CAL_GREGORIAN, date($dateArray[1]), date($dateArray[2]), date($dateArray[0])), 1);
}

?>