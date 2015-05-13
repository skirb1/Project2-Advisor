<?php
        include_once 'init.php';
        include_once 'includes/overallheader.php';
        include 'includes/widgets/logout.php';
if(array_key_exists('studentID', $_SESSION))
    {
        $advisorID = $_SESSION['studentID'];
?>
        <h2>Appointment Selection</h2>
        <br><br><br>
        <h1>You do not have an appointment</h1>
        <h1>Please select an appointment below</h1>
    
<?php
    }
    
include_once 'includes/selectWeek.php';
<form id="weekForm" action="searchAppts.php" method="post">
<?php include 'includes/selectWeek.php'; ?>
</form>
<script language="JavaScript">
function toggle(source, name) {
  checkboxes = document.getElementsByName(name);
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>
<?php
  if(count($_POST) > 1){
      //form to select search criteria
?>
<br><form action="selectAppt.php" method="post">
    <table id="outerTable"><tr>

    <td><div id="selectTitle">Select dates:</div>
    <div id="selectGroup"><table id="transparentTable">
<?php
    $week = $CALENDAR->weeks[(int)$_POST['week']];
    for($i = 0; $i < 5; $i++){
      $date = $week->dates[$i];
      echo "<tr><td><input type=\"checkbox\" name=\"dates[]\" value=\"".$date."\">";
      echo date_to_string($date)."</td>";
      echo "</tr>";
    }
      ?><tr><td><input type="checkbox" onClick="toggle(this, 'date[]')"/>Select All</td></tr></table></div></td>
      
    <td><div id="selectTitle">Select times:</div>
    <div id="selectGroup"><table id="transparentTable">
<?php
    foreach($apptTimes as $time){
      echo "<tr><td><input type=\"checkbox\" name=\"times[]\" value=\"".db_time($time)."\">";
      echo display_time($time)."</td>";
      echo "</tr>";
    }
?>
<tr><td><input type="checkbox" onClick="toggle(this, 'date[]')"/>Select All</td></tr></table></div></td>
      
    <td><div id="selectTitle">Appointment Type:</div>
    <div id="selectGroup"><table id="transparentTable">
    <tr><td><input type="radio" name="type" value="indiv">Individual
    </td></tr>
    <tr><td><input type="radio" name="type" value="group">Group
    </td></tr>
        </table></div></td>
<?php      //select advisors
  /*  $advisorArr = advisor_array();
    echo "<br><div id=\"selectTitle\">Select Advisor (individual appointments only):</div>";
    echo "<div id=\"selectGroup\"><table id=\"transparentTable\">";
    foreach($advisorArr as $adv){
      echo "<tr><td><input type=\"checkbox\" name=\"advisors[]\" value=\"".$adv."\">".name_from_advisorID($adv);
      echo "</td></tr>";
    }
      echo "<tr><td><input type=\"checkbox\" onClick=\"toggle(this, 'date[]')\"/>Select All</td></tr></table></div>";*/
      //echo "</td>";?>

    <td><div id="submit"><input type="submit" name="search" value="Search">
        </div></td>
    </tr></table></form>
<?php
}
}
else {
    echo "<br><div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
    echo "You are not logged in.</div>";
}

include_once 'includes/overallfooter.php';
?>