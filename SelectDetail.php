<?php
include 'init.php';
include 'includes/overallheader.php';

if(array_key_exists('advisorID', $_SESSION)){
?>
<h2>Print Detail</h2>
<form id="weekForm" action="SelectDetail.php" method="post">
<?php include 'includes/selectWeek.php'; ?>
</form>
<?php  
    
  if(array_key_exists('week', $_POST) === true ){
    echo "<form action=\"PrintDetail.php\" method=\"post\">";
    echo "<br><div id=\"selectTitle\">Select day:</div>";
    $week = $CALENDAR->weeks[(int)$_POST['week']];
    echo "<div id=\"list\"><ul id=\"listDaysToEdit\">";
    for($i = 0; $i < 5; $i++){
      $date = $week->dates[$i];
      echo "<li><input type=\"radio\" name=\"date\" value=\"".$date."\">";
      echo date_to_string($date)."</li>";
    }
    echo "</ul></div>";  
    echo "<div id=\"selectTitle\">Schedule type:</div>";
    echo "<div id=\"list\"><ul id=\"radio\">";
    echo "<li><input type=\"radio\" name=\"apptType\" value=\"Individual\">";
    echo "Individual</li>";
    echo "<li><input type=\"radio\" name=\"apptType\" value=\"Group\">Group";
    echo "</li></ul></div>";
    echo "<div id=\"submit\"><input type=\"submit\" name=\"submitDay\">";
    echo "</div></form>";
  }
}
else {
    echo "<div id=\"error\">";
    echo "<img src=\"includes/error.png\" id=\"errorImg\">";
    echo "You are not logged in.</div>";
}
?>
<?php include 'includes/overallfooter.php'; ?>