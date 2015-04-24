<?php

function name_from_advisorID($advisorID){
    global $debug;
    global $COMMON;
    $name = "";
    
    $sql = "SELECT * FROM Advisors WHERE advisorID = '".$advisorID."'";
    $record = $COMMON->executeQuery($sql, $_SERVER["Advisor.php"]);
    if($record !== false){
     $advisor = mysql_fetch_row($record);
        $name = $advisor[1]." ".$advisor[2];
    }
    
    return $name;
}

//Display list of advisors and contact info
function advisor_list(){
  global $debug;
  global $COMMON;

    $sql = "SELECT * FROM Advisors";
    $record = $COMMON->executeQuery($sql, $_SERVER["Advisor.php"]);
    echo "<div id=\"list\">";
    while($advisor = mysql_fetch_row($record))
      {
	echo $advisor[2] . " ";
	echo $advisor[3] . "<br>";
        echo $advisor[4] . "<br>";
        echo "Room " . $advisor[5] . "<br><br>";
      }
    echo "</div>";
}

function display_week($advisorID, $weekIndex){
  global $CALENDAR;
  global $apptTimes;
  global $debug;
  global $COMMON;

  if(count($_POST) > 1){
    $week = $CALENDAR->weeks[$weekIndex];
    for($i = 0; $i < 5; $i++){
        $date = $week->dates[$i];    
        $sql = "SELECT * FROM Individual_Schedule WHERE advisorID = '$advisorID'";
        $sql.= "AND date = '$date'";
        $record = $COMMON->executeQuery($sql, $_SERVER["ScheduleDisplay.php"]);
        $schedule = mysql_fetch_row($record);
        echo "<div id=\"scheduleDisplay\">";
        echo "<div id=\"dateTitle\">".date_to_string($date)."</div>";
        echo "<table id=\"tableDisplay\"><tr>";
        foreach($apptTimes as $time){
	       echo "<th>" . $time . "</th>";
        }
        echo "</tr>";
        if(count($schedule) > 1){
            for($j = 3; $j < count($schedule); $j++) {
	           if($schedule[$j] == "Closed"){
	               echo "<td id=\"tdUnavailable\">X</td>";
	           } else if($schedule[$j] == NULL){
	               echo "<td></td>";
                } else {
	               echo "<td>" . $schedule[$j] . "</td>";
	           }
            }
        //if record doesnt exist, schedule is empty (set all to unavailable)
        } else {
            foreach($apptTimes as $time){
                echo "<td id=\"tdUnavailable\">X</td>";
            }
        }
        echo "</table></div>";
    }//end of for(each day)
    include 'includes/printButton.php';
  }//end of if(count($_POST)>1)
}//end of function

function update_group_tables($advisorID, $date, $time){
  global $debug;
  global $COMMON;
  $groupTable = $date."Groups";
  $result = true;

  $sql = "SELECT * FROM `$date` WHERE `advisorID` = '$advisorID'";
  $indivRecord = $COMMON->executeQuery($sql, $_SERVER["Schedule.php"]);

  //get advisor schedule for the date specified, get group times
  if($indivRecord !== false && mysql_num_rows($indivRecord) == 1){
    $element = mysql_result($indivRecord, 0, $time);
    $sql = "SELECT * FROM `".$groupTable."` WHERE `time` = '".$time."'";
    $groupRecord = $COMMON->executeQuery($sql, $_SERVER["Schedule.php"]);

    if($groupRecord !== false && mysql_num_rows($groupRecord) == 1){
	$advisorSet = false;
        for ($i = 1; $i <= 4; $i++){
            $field = "advisor".$i;
            if(mysql_result($groupRecord, 0, $field) == $advisorID){
              $advisorSet = true;
	      //if advisor is in group table and not scheduled for group
	      //at that time, delete advisorID from group
	      if( $element != "Group"){
		$sql = "UPDATE `".$date."Groups` SET `".$field."` = NULL" ;
                $sql .= " WHERE `time` = '".$time."'";
                $record = $COMMON->executeQuery($sql, $_SERVER["Schedule.php"]);
		if($record === false){
		  echo "<div id=\"error\">Error removing advisor from group</div>";
                  $result = false;
		}
	      }//end of if(advisor not scheduled for group)
            }//end of if(advisor found in group table)
          }//and of for(loop through advisors in group table

	  //if advisor not in table and element contains group
	  //add advisor to table for that time
          if($advisorSet === false && $element == "Group"){
            for ($j = 1; $j <= 4; $j++){
              $field = "advisor".$j;
              if(mysql_result($groupRecord, 0, $field) == NULL){
                $sql = "UPDATE `".$date."Groups` SET `".$field."` = '" ;
                $sql .= $advisorID."' WHERE `time` = '".$time."'";
		$record = $COMMON->executeQuery($sql, $_SERVER["Schedule.php"]);
		if($record === false){
		  echo "<div id=\"error\">Error adding advisor to group</div>";
		  $result = false;
		}
		break;
              }
            }//end of for
          }//end of if(advisor not in group table and scheduled for group)
        }//end of if(group record valid)
	else {
	  echo "<div id=\"error\">Error accessing group table</div>";
	  $result = false;
	}

  }//end of if(indiv record is valid)
  else {
    echo "<div id=\"error\">Error accessing individual table</div>";
    $result = false;
  }
  return result;
}//end of function update_tables


?>