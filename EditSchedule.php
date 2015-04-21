<?php
include 'init.php';
include 'includes/overallheader.php';
?>
<h2>Edit Schedule</h2>
<p1>
<form id="weekForm" action="EditSchedule.php" method="post">
<?php include 'includes/selectWeek.php'; ?>
</form>
<?php
if(array_key_exists('advisorID', $_SESSION)){
  if(count($_POST) > 1){
    echo "<form action=\"EditDay.php\" method=\"post\">";
    echo "<br><div id=\"selectTitle\">Select a day to edit:</div>";
    $week = $CALENDAR->weeks[(int)$_POST['week']];
    echo "<div id=\"list\"><ul id=\"listDaysToEdit\">";
    for($i = 0; $i < 5; $i++){
      $date = $week->dates[$i];
      echo "<li><input type=\"checkbox\" name=\"".$date;
      echo "\" value=\"".$date."\">";
      echo date_to_string($date)."</li>";
    }
    echo "</ul></div>";
    echo "<div id=\"submit\"><input type=\"submit\" name=\"Edit Day\">";
    echo "</div></form>";
  }
}
else {
  echo "<br><div id=\"error\">You are not logged in.</div>";
}
?>
</p1>
<?php include 'includes/overallfooter.php'; ?>