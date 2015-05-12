<?php
include 'init.php';
include 'includes/overallheader.php';

if(array_key_exists('advisorID', $_SESSION)){
?>
<h2>Edit Schedule</h2>
<?php
  $advisorID = $_SESSION['advisorID'];
  if(count($_POST) > 1){
    $submit = array_pop($_POST);
    echo "<form action=\"UpdateSchedule.php\" method=\"post\">";

    foreach($_POST['date'] as $date ){
        
        //set all closed appts to open if checked for the date
        if(array_key_exists('SetOpen', $_POST)){
            if( in_array($date, $_POST['SetOpen']) ){
                if( open_appts($advisorID, $date) == false){
                    echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
                    echo "Error setting appointments to open</div>";
                }
            }
        }
        
        //set group appts if checked for the date
        if(array_key_exists('SetGroups', $_POST)){
            if( in_array($date, $_POST['SetGroups']) ){
                if( set_group_appts($advisorID, $date) == false){
                    echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
                    echo "Error setting group appointments</div>";
                }
            }
        }
        
      $sql = "SELECT * FROM Individual_Schedule WHERE advisorID = '$advisorID'";
        $sql .= "  AND date = '$date'";
      $record = $COMMON->executeQuery($sql, $_SERVER["EditSchedule.php"]);

      echo "<div id=\"editSchedule\">";
      echo "<div id=\"dateTitle\">".date_to_string($date)."</div>";
      echo "<table id=\"tableEdit\"><tr>";
      foreach($apptTimes as $time){
        echo "<th>" . $time . "</th>";
      }
      echo "</tr>";
      $schedule = mysql_fetch_assoc($record);
      foreach($apptTimes as $time) {
          $value = $schedule[db_time($time)];
          echo "<td><select name=\"".$date."_".$time."\">";
          
          //only group appt before March 23
          if(substr($date, 6, 1) == "3" && substr($date, 8, 2) < "23"){
              echo "<option value=\"Group\"";
              if($value == "Group") {
                  echo " selected ";
              }
              echo ">Group</option>";
              
                echo "<option value=\"Closed\"";
                if($value == "Closed" || $value == "NULL" || $value == NULL) {
                    echo " selected ";
                }
                echo ">Closed</option>";
          }
          
          //after March 23, all appointments are available
          else{
              if($value != NULL && $value != "Group" && $value != "Closed" &&
                 $value != "CMPE" && $value != "CMSC" && $value != "ENME" &&
                 $value != "ENCH" && $value != "ENGR" && $value != "NULL" && 
                 $value != "Open" ) {
               echo "<option value=\"".$value."\">Appt</option>";
              }
              else{
                  echo "<option value=\"Open\"";
                  if($value == "Open"){
                      echo " selected ";
                  }
                  echo ">Open</option>";

                  echo "<option value=\"Group\"";
                  if($value == "Group") {
                      echo " selected ";
                  }
                  echo ">Group</option>";

                    foreach($majors as $major){
                        if( $major == NULL){
                            echo "<option value=\"Closed\"";
                            if($value == "Closed" || $value == "NULL" || $value == NULL) {
                                echo " selected ";
                            }
                            echo ">Closed</option>";
                        }
                        else {
                            echo "<option value=\"".$major."\"";
                            if($value == $major) {
                                echo " selected ";
                            }
                            echo ">".$major."</option>"; 
                        }
                    }
                }
          }
	       echo "</select></td>";
      }//end of foreach date
      echo "</table></div>";
    }
    echo "<br><div id=\"submit\">";
    echo "<input type=\"submit\" name=\"Update Schedule\"></div>";
    echo "</form>";
  } //end of if(data entered)
  else { 
    echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
    echo "Please select a day</div>"; 
    echo "<div id=\"error\"><a href=\"EditSchedule.php\" >Back</a></div>";
  }
} //end of if(logged in)
else {
    echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
    echo "You are not logged in</div>";
}

include 'includes/overallfooter.php';
?>