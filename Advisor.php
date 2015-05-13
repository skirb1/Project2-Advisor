<?php

//counts number of advisors stored in database
function count_Advisors(){
    global $COMMON;
    $sql = "SELECT * FROM Advisors";
    $record = $COMMON->executeQuery($sql, $_SERVER["Advisor.php"]);  
    return mysql_num_rows($record);
}

//returns full advisor name from advisorID
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

//set all closed times to open for the date
function open_appts($advisorID, $date){
    global $COMMON;
    global $apptTimes;
    
    
    if(substr($date, 6, 1) == "3" && substr($date, 8, 2) < "23"){
        return true;   
    }
    else {
        $sql = "SELECT * FROM Individual_Schedule WHERE advisorID='$advisorID' AND date='$date'";
        $record = $COMMON->executeQuery($sql, $_SERVER["Advisor.php"]);
        if(mysql_num_rows($record) == 1){
            $recordAssoc = mysql_fetch_assoc($record);
            foreach($apptTimes as $time){
                $time = db_time($time);
                if($recordAssoc[$time] == "Closed" || $recordAssoc[$time] == NULL ){
                    $sql = "UPDATE Individual_Schedule SET `".$time."`=\"Open\" WHERE ";
                    $sql .= " advisorID='$advisorID' AND date='$date'";
                    $result = $COMMON->executeQuery($sql, $_SERVER["Advisor.php"]);
                    if($result == false){
                        return false;
                    }
                }
            }
        }
        else if(mysql_num_rows($record) == 0){
            $sql = "INSERT INTO Individual_Schedule ( `advisorID`, `date` ";
            foreach($apptTimes as $time){
                $sql .= ", `".db_time($time)."`";
            }
            $sql .= " ) VALUES ( '$advisorID', '$date'";
            foreach($apptTimes as $time){
                $sql .= ", 'Open'";
            }       
            $sql .= ");";
            $result = $COMMON->executeQuery($sql, $_SERVER["Advisor.php"]);
            if($result == false){
                return false;
            }
        }
        else {
            return false;       
        }
    }
    return true;
}

//Set all un-scheduled times (open or closed) to Group from 11am-1pm
function set_group_appts($advisorID, $date){
    global $COMMON;
    $groupTimes = array("11:00", "11:30", "12:00", "12:30", "1:00");
    
    $sql = "SELECT * FROM Individual_Schedule WHERE advisorID='$advisorID' AND date='$date'";
    $record = $COMMON->executeQuery($sql, $_SERVER["Advisor.php"]);
    if(mysql_num_rows($record) == 1){
        $recordAssoc = mysql_fetch_assoc($record);
        foreach($groupTimes as $time){
            $time = db_time($time);
            if(is_studentID($recordAssoc['time']) == false){
                $sql = "UPDATE Individual_Schedule SET `".$time."`=\"Group\" WHERE ";
                $sql .= " advisorID='$advisorID' AND date='$date'";
                $result = $COMMON->executeQuery($sql, $_SERVER["Advisor.php"]);
                if($result == false){
                    return false;
                }
                else {
                    if( update_group($advisorID, $date, $time, "Group") == false ){
                        return false;   
                    }
                }
            }
        }
    }
    else if (mysql_num_rows($record) == 0){
        $sql = "INSERT INTO Individual_Schedule ( `advisorID`, `date` ";
        foreach($groupTimes as $time){
            $sql .= ", `".db_time($time)."`";
        }
        $sql .= " ) VALUES ( '$advisorID', '$date'";
        foreach($groupTimes as $time){
            $sql .= ", 'Group'";
        }       
        $sql .= ");";
        $result = $COMMON->executeQuery($sql, $_SERVER["Advisor.php"]);
        if($result == false){
            return false;
        }
    }
    return true;
}

//displays full week, each day as a table with icons to represent indiv/group appts
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
        $record = $COMMON->executeQuery($sql, $_SERVER["Advisor.php"]);
        $schedule = mysql_fetch_assoc($record);
        echo "<div id=\"scheduleDisplay\">";
        echo "<div id=\"dateTitle\">".date_to_string($date)."</div>";
        echo "<table id=\"tableDisplay\"><tr>";
        foreach($apptTimes as $time){
	       echo "<th>" . $time . "</th>";
        }
        echo "</tr>";
        if(count($schedule) > 1){
            foreach($apptTimes as $time) {
                $element = $schedule[db_time($time)];
	           if($element == "Closed" || $element == NULL || $element == "NULL" ){
	               echo "<td id=\"tdUnavailable\">X</td>";
	           } else if ( $element == "Open" ){
	               echo "<td></td>";
                } else if ( $element == "Group" ){
                    echo "<td><img src=\"includes/group-icon.png\"";
                    echo "style=\"width:34px;height:24px\"></td>";
               } else if ($element == "CMSC" || $element == "CMPE"
                         || $element == "ENGR" || $element == "ENCH" || $element == "ENME" ){
                   echo "<td>".$element."</td>";
	           }
                else {
                    echo "<td><img src=\"includes/student-icon.png\"";
                    echo "style=\"width:23px;height:22px\"></td>";            
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

?>