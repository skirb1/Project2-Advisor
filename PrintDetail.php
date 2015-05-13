<?php
include 'init.php';
include 'includes/overallheader.php';

if(array_key_exists('advisorID', $_SESSION)){
  $advisorID = $_SESSION['advisorID'];

  if(array_key_exists('date', $_POST) === true && 
    array_key_exists('apptType', $_POST) === true){
    $apptType = $_POST['apptType'];
    $date = $_POST['date'];

    //Output table for individual appt info
    if($apptType == "Individual"){
        $sql = "SELECT * FROM Individual_Schedule WHERE advisorID = '$advisorID'";
        $sql .= " AND date = '$date'";
        $record = $COMMON->executeQuery($sql, $_SERVER["PrintDetail.php"]);
        echo "<div id=\"printDetail\">";
        echo "<div id=\"dateTitle\">".date_to_string($date)."</div>";
        echo "<table id=\"tableDetail\"><tr>";
        echo "<th style=\"width:15%\">Time</th>";
        echo "<th style=\"width:32.5%\">Name</th>";
        echo "<th style=\"width:32.5%\">Major</th>";
        echo "<th style=\"width:20%\">UMBC ID#</th></tr>";
        $indivAssoc = mysql_fetch_assoc($record);
        array_shift($indivAssoc); // shift recordID
        array_shift($indivAssoc); // shift advisorID
        array_shift($indivAssoc); // shift date
    
	   foreach($indivAssoc as $time => $element){
           $time = short_time($time);
           if($element == "Closed" || $element == NULL){
               /*dont output unavailable times*/
           }
	       else {
	           echo "<tr><td>".display_time($time)."</td>";
               //if its a student ID (has 2 char then numbers)
	           if(ctype_digit(substr($element, 2))){
                  //get student info to display in table
                  $studentID = $element;
                  $sql = "SELECT * FROM Students WHERE studentID = '$studentID'";
                  $record = $COMMON->executeQuery($sql, $_SERVER["PrintDetail.php"]);
                  if(mysql_num_rows($record) == 1){
                    echo "<td>".mysql_result($record, 0, 'firstName')." ";
                    echo mysql_result($record, 0, 'lastName')."</td>";
                    echo "<td>".mysql_result($record, 0, 'major')."</td>";
                    echo "<td>".mysql_result($record, 0, 'studentID')."</td></tr>";
                  } else {
                    echo "<td>".$studentID."</td></tr>";
                  }                   
	           }
	           //display all other possible appt types
               else if( $element == "Group"){
	               echo "<td>Group Advising</td><td></td><td></td></tr>";
               }
               else if( $element == "Open"){
	               echo "<td></td><td></td><td></td></tr>";
               }
               else {
	               echo "<td></td><td>".$element."</td><td></td></tr>";
               }
	       }//end else
        }//end foreach(loop through time slots)
	echo "</table></div>";
	include 'includes/printButton.php';
    }//end if(Individual)

    //Choose group schedule and Print group table
    else if($apptType == "Group"){

      //Display table to print
	   if(array_key_exists('time', $_POST) === true) {
	       $time = db_time($_POST['time']);
	       $sql = "SELECT * FROM Group_Schedule WHERE date = '$date' AND time = '$time'";
	       $record = $COMMON->executeQuery($sql, $_SERVER["PrintDetail.php"]); 
           $recordAssoc = mysql_fetch_assoc($record);
	       echo "<div id=\"printDetail\">";
	       echo "<div id=\"dateTitle\">Group Advising - ";
	       echo date_to_string($date)." - ".display_time($time)."</div>";
           
           echo "Advisors: ";
           $advisorCount = 0;
           for( $i = 1; $i <= 3; $i++){
               $key = "advisor".$i;
               if($recordAssoc[$key] != NULL){
                   if($advisorCount > 0){ echo ", "; }
                    echo name_from_advisorID($recordAssoc[$key]);
                   $advisorCount++;
               }
           }
           echo "<br><br>";

          echo "<table id=\"tableDetail\"><tr>";
          echo "<th style=\"width:40%\">Name</th>";
          echo "<th style=\"width:40%\">Major</th>";
          echo "<th style=\"width:20%\">UMBC ID#</th></tr>";
  
          for($i = 1; $i <= 10; $i++){
              $key = "student".$i;
              $element = $recordAssoc[$key];
              if( $element == "Closed" ){
                  /* output nothing */
              }
              else if ($element == "" || $element == NULL ) {
                echo "<tr><td></td><td></td><td></td></tr>";
              }
              else{
                  //get student info to display in table
                  $student = $element;
                  $sql = "SELECT * FROM Students WHERE studentID = '$student'";
                  $record = $COMMON->executeQuery($sql, $_SERVER["PrintDetail.php"]);
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
        $timecount = 0;
        
        echo "<table id=\"selectGroupTable\">";
        foreach($apptTimes as $t){
            $time = db_time($t);
            $sql = "SELECT * FROM Group_Schedule WHERE date = '$date' and time='$time'";
            $record = $COMMON->executeQuery($sql, $_SERVER["SelectGroup.php"]);
            if(mysql_num_rows($record) == 1){
                if(!is_group_null($date, $time)){
                    $time = short_time($time);
                    echo "<tr><td><b>";
                    echo "<input type=\"radio\" name=\"time\" value=\"".$time."\">";
                    echo display_time($time)."</b></td><td>Advisors: ";
                    echo count_group_advisors($date, $time);
                    echo "</td><td>Students: ".count_students($date, $time)."</td><td>";
                    echo "Limit: ".get_size($date, $time)."</td></tr>";
                    $timecount += 1;
                }
            }
        }
        
        echo "</table></div>";
        echo "<input type=\"hidden\" name=\"date\" value=\"".$date."\">";
        echo "<input type=\"hidden\" name=\"apptType\" value=\"".$apptType."\">";

	   if($timecount == 0){
            echo "<div id=\"error\">";
           echo "<img src=\"includes/error.png\" id=\"errorImg\">";
            echo "There are no group advising times for this day.</div>";
            echo "<div id=\"error\"><a href=\"SelectDetail.php\">Select another day";
            echo "</a></div>";
	   }
        else {
	       echo "<div id=\"submit\"><input type=\"submit\" name=\"submitTime\"></div>";
	       if(array_key_exists('submitTime', $_POST) === true){
	           echo "<div id=\"error\">";
                echo "<img src=\"includes/error.png\" id=\"errorImg\">";
               echo "Please select a group time to view";
	           echo "</div>";
	       }
	   }
	   echo "</form>";
	   }
    }//end of if(Group)

  } //end if(data is entered)
  else {
    if(array_key_exists('date', $_POST) === false ){
        echo "<div id=\"error\">";
        echo "<img src=\"includes/error.png\" id=\"errorImg\">";
        echo "Please select a day</div>";
    }
    if(array_key_exists('apptType', $_POST) === false ){
        echo "<div id=\"error\">";
        echo "<img src=\"includes/error.png\" id=\"errorImg\">";
        echo "Please select a schedule type</div>";
    }
    echo "<div id=\"error\"><a href=\"SelectDetail.php\" >Back</a></div>";
  }
} //end if(advisorID exists)
else 
{
    echo "<div id=\"error\">";
    echo "<img src=\"includes/error.png\" id=\"errorImg\">";
    echo "You are not logged in.</div>";
    echo "<div id=\"error\"><a href=\"index.php\" >Back</a></div>";
}
include 'includes/overallfooter.php'
?>