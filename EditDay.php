<?php
include 'init.php';
include 'includes/overallheader.php';
?>
<h2>Edit Schedule</h2>
<p1>
<?php
if(array_key_exists('advisorID', $_SESSION)){
  $advisorID = $_SESSION['advisorID'];
  if(count($_POST) > 1){
    $submit = array_pop($_POST);
    echo "<form action=\"UpdateSchedule.php\" method=\"post\"";
    echo " style=\"padding-top:0px\">";

    foreach($_POST as $date ){
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
          
          echo "<option value=\"Closed\"";
          if($value == "Closed" || $value == "NULL" || $value == NULL) {
              echo " selected ";
          }
          echo ">Closed</option>";
          
          echo "<option value=\"CMPE\"";
          if($value == "CMPE") {
              echo " selected ";
          }
          echo ">CMPE</option>";  
          
          echo "<option value=\"CMSC\"";
          if($value == "CMSC") {
              echo " selected ";
          }
          echo ">CMSC</option>"; 
          
          echo "<option value=\"ENME\"";
          if($value == "ENME") {
              echo " selected ";
          }
          echo ">ENME</option>";
          
          echo "<option value=\"ENCH\"";
          if($value == "ENCH") {
              echo " selected ";
          }
          echo ">ENCH</option>";
          
          echo "<option value=\"ENGR\"";
          if($value == "ENGR") {
              echo " selected ";
          }
          echo ">ENGR</option>";
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
    echo "<div id=\"error\">No days selected<br><br>"; 
    echo "<a href=\"EditSchedule.php\" >Back</a></div>";
  }
} //end of if(logged in)
else {
  echo "<div id=\"error\">You are not logged in</div>";
}
?>
</p1>
<?php include 'includes/overallfooter.php';?>