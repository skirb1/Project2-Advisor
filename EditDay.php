<?php
include 'init.php';
include 'includes/overallheader.php';
?>
<h2>Edit Schedule</h2>
<p1>
<?php
if(logged_in() === true){
  $advisorID = advisorID_from_userID($_SESSION['userID']);
  if(count($_POST) > 1){
    $submit = array_pop($_POST);
    echo "<form action=\"UpdateSchedule.php\" method=\"post\"";
    echo " style=\"padding-top:0px\">";

    foreach($_POST as $date ){
      $sql = "SELECT * FROM " . $date ." WHERE advisorID = '$advisorID'";
      $record = $COMMON->executeQuery($sql, $_SERVER["EditSchedule.php"]);

      echo "<div id=\"editSchedule\">";
      echo "<div id=\"dateTitle\">".date_to_string($date)."</div>";
      echo "<table id=\"tableEdit\"><tr>";
      foreach($apptTimes as $time){
        echo "<th>" . $time . "</th>";
      }
      echo "</tr>";
      $schedule = mysql_fetch_row($record);
      for($j = 2; $j < count($schedule); $j++) {
	echo "<td><select name=\"".$date."-".$apptTimes[$j-2]."\">";
	if($schedule[$j] == "false"){
	    echo "<option value=\"true\">Individual</option>";
	    echo "<option value=\"Group\">Group</option>";
	    echo "<option value=\"false\" selected>None</option>";
	} else if ( strpos($schedule[$j], "Group") !== false ) {
	    echo "<option value=\"true\">Individual</option>";
	    echo "<option value=\"Group\" selected>Group</option>";
	    echo "<option value=\"false\">None</option>";
	} else if ( $schedule[$j] == "true"){
	    echo "<option value=\"true\" selected>Individual</option>";
	    echo "<option value=\"Group\">Group</option>";
	    echo "<option value=\"false\">None</option>";
	} else {
	  echo "<option value=\"".$schedule[$j];
	  echo "\" selected>Individual</option>";
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