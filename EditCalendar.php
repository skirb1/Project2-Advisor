<?php
include 'init.php';
include 'includes/overallheader.php';
?>
<h2>Edit Calendar</h2>
<p1>
<?php
if(array_key_exists('advisorID', $_SESSION)){
  echo "<div id=\"selectTitle\">Remove Week:</div>";
  echo "<br><br><p2>This will remove all data for the selected week</p2>";
  echo "<form name=\"removeWeek\" action=\"EditCalendar.php\" method=\"post\">";
  echo "<div id=\"list\"><ul>";
  for($i = 0; $i < count($CALENDAR->weeks); $i++){
    $week = $CALENDAR->weeks[$i];
    $first = $week->dates[0];
    $last = $week->dates[4];
    echo "<li><input type=\"radio\" name=\"date\" value=\"".$first;
    echo "\">".short_string($first)." - ".short_string($last);
    echo "</li>";
  }
  echo "<li><input type=\"submit\" name=\"submitRemove\"></li>";
  echo "</ul></div>";

  echo "<div id=\"selectTitle\">Add Week:</div>";
  echo "<br><br><p2>Select the date of a Monday to add that week: ";
  include 'includes/addWeekForm.php';
  echo "</p2>";

  //handle add week form
  if(array_key_exists('day', $_POST) &&
     array_key_exists('month', $_POST) &&
     array_key_exists('year', $_POST) &&
     array_key_exists('submitAdd', $_POST) ){
    $day = jddayofweek(cal_to_jd(CAL_GREGORIAN, (int)$_POST['month'], (int)$_POST['day'], (int)$_POST['year']), 1);

    if($day == "Monday"){
      $CALENDAR->add_week($_POST['month'], $_POST['day'], $_POST['year']);
      header('Location: EditCalendar.php');
    }
    else {
      echo "<div id=\"error\">This date is not a Monday, week not added</div>";
    }
  }
  //handle remove week form
  else if(array_key_exists('date', $_POST) &&
	  array_key_exists('submitRemove', $_POST)){
    echo $_POST['date'];
    $CALENDAR->remove_week($_POST['date']);
    header('Location: EditCalendar.php');
  }
  //handle empty form
  else if(array_key_exists('submitAdd', $_POST) || 
	  array_key_exists('submitRemove', $_POST)){
      echo "<div id=\"error\">Missing Field</div>";
  }

}
else {
  echo "<br><div id=\"error\">You are not logged in.</div>";
}
?>
</p1>
<?php include 'includes/overallfooter.php'; ?>