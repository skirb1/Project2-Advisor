<?php
include 'init.php';
include 'includes/overallheader.php';

if(logged_in() === true){
  if(userType_from_userID($_SESSION['userID']) == "Advisor"){
    $advisorID = advisorID_from_userID($_SESSION['userID']);

  if(array_key_exists('date', $_POST) === true && 
     array_key_exists('apptType', $_POST) === true){
      $apptType = $_POST['apptType'];
      $date = $_POST['date'];

      //Output table for individual appt info
      if($apptType == "Individual"){
        $sql = "SELECT * FROM " . $date ." WHERE advisorID = '$advisorID'";
        $record = $COMMON->executeQuery($sql, $_SERVER["PrintDetail.php"]);
	echo "<div id=\"printDetail\">";
        echo "<div id=\"dateTitle\">".date_to_string($date)."</div>";
	echo "<table id=\"tableDetail\"><tr>";
	echo "<th style=\"width:15%\">Time</th>";
        echo "<th style=\"width:32.5%\">Name</th>";
	echo "<th style=\"width:32.5%\">Major</th>";
        echo "<th style=\"width:20%\">UMBC ID#</th></tr>";
	$schedule = mysql_fetch_row($record);
	array_shift($schedule);
	array_shift($schedule);
     
	for($i = 0; $i < count($schedule); $i++){
     	  if($schedule[$i] == "false")
	    { /*dont output unavailable times*/}
	  else{
	    echo "<tr><td>".$apptTimes[$i]."</td>";
	    if($schedule[$i] == "Group"){
	      echo "<td>Group Advising</td><td></td><td></td></tr>";
	    }
	    //diaplay empty row for available time slot
	    else if ($schedule[$i] == "true"){
	      echo "<td></td><td></td><td></td></tr>";
	    }
	    else {
	      //get student info to display in table
	      $student = $schedule[$i];
	      $sql = "SELECT * FROM Students WHERE studentID = '$student'";
	      $record = $COMMON->executeQuery($sql, $_SERVER["ViewDay.php"]);
	      if($record !== false){
		echo "<td>".mysql_result($record, 0, 'firstName')." ";
		echo mysql_result($record, 0, 'lastName')."</td>";
		echo "<td>".mysql_result($record, 0, 'major')."</td>";
		echo "<td>".mysql_result($record, 0, 'studentID')."</td></tr>";
	      } else {
		echo "<td>".$student."</td></tr>";
	      }
	    }
	  }//end else
        }//end for (loop through time slots)
	echo "</table></div>";
	include 'includes/printButton.php';
    }//end if(Individual)

      //Choose group schedule and Print group table
    else if($apptType == "Group"){

      //Display table to print
	if(array_key_exists('time', $_POST) === true) {
	  $time = $_POST['time'];
	  $sql = "SELECT * FROM ". $date ."Groups WHERE time = '$time'";
	  $record = $COMMON->executeQuery($sql, $_SERVER["View Day.php"]);     
	  echo "<div id=\"printDetail\">";
	  echo "<div id=\"dateTitle\">Group Advising - ";
	  echo date_to_string($date)." - ".$time."</div>";

	  echo "<table id=\"tableDetail\"><tr>";
	  echo "<th style=\"width:40%\">Name</th>";
	  echo "<th style=\"width:40%\">Major</th>";
	  echo "<th style=\"width:20%\">UMBC ID#</th></tr>";
  
	  $students = mysql_fetch_row($record);
	  $studentCount = 0;
	  for($i = 6; $i < count($students); $i++){
	    if($students[$i] == "")
	      { /* don't output anything */ }
	    else{
	      //get student info to display in table
	      $student = $students[$i];
	      $sql = "SELECT * FROM Students WHERE studentID = '$student'";
	      $record = $COMMON->executeQuery($sql, $_SERVER["ViewDay.php"]);
	      if($record !== false && mysql_num_rows($record) > 0){
		$studentCount += 1;
		echo "<tr><td>".mysql_result($record, 0, 'firstName')." ";
		echo mysql_result($record, 0, 'lastName')."</td>";
		echo "<td>".mysql_result($record, 0, 'major')."</td>";
		echo "<td>".mysql_result($record, 0, 'studentID')."</td></tr>";
	      } else {
		echo "<td>".$student."</td></tr>";
	      }
	    }//end else
	  }//end for (loop through time slots)
	  for($j = $studentCount; $j < 10; $j++){
	    echo "<tr><td></td><td></td><td></td></tr>";
	  }
	  echo "</table></div>";
	  include 'includes/printButton.php';
	}//end if(group data exists)

	//If no time selected, choose a group advising time to print
	else {
	  echo "<h2>Print Detail</h2>";
	  echo "<div id=\"selectTitle\">Select a group advising time for ";
	  echo date_to_string($date)."</div>";
	  echo "<div id=\"selectGroup\">";
	  echo "<form action=\"PrintDetail.php\" method=\"post\">";
	  echo "<div id=\"list\"><ul>";
	  $groupTable = $date."Groups";
	  $timecount = 0;
	  foreach($apptTimes as $time){
	    $sql = "SELECT * FROM ".$groupTable." WHERE time = '$time'";
	    $record = $COMMON->executeQuery($sql, $_SERVER["ViewDay.php"]);
	    if($record !== false && mysql_num_rows($record) > 0 ){
	      echo "<li>";
	      echo "<input type=\"radio\" name=\"time\" value=\"".$time."\">";
	      echo $time."</li>";
	      $timecount += 1;
	    }
	  }
	  echo "</ul></div>";
	  echo "<input type=\"hidden\" name=\"date\" value=\"".$date."\">";
	  echo "<input type=\"hidden\" name=\"apptType\" value=\"";
	  echo $apptType."\">";

	  if($timecount == 0){
	    echo "<div id=\"error\">";
	    echo "There are no group advising times for this day.";
	    echo "<br><br><a href=\"SelectDetail.php\">Select another day";
	    echo "</a></div>";
	  } else {
	    echo "<input type=\"submit\" name=\"submitTime\">";

	    if(array_key_exists('submitTime', $_POST) === true){
	      echo "<div id=\"error\">You must select a group time to view";
	      echo "</div>";
	    }
	  }
	  echo "</form></div>";
	}
      }//end of if(Group)

    } //end if(data is entered)
  else {
    if(array_key_exists('date', $_POST) === false ){
      echo "<br>You must select a day.";
    }
    if(array_key_exists('apptType', $_POST) === false ){
      echo "<br>You must select a schedule type.";
    }
    echo "<br><br><a href=\"SelectDetail.php\" >Back</a>";
  }
} //end of if(user is Advisor)
} //end if(logged in)
else {
  echo "<br><div id=\"error\">You are not logged in.</div>";
}
include 'includes/overallfooter.php'
?>