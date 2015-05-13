<?php
include 'init.php';
include 'includes/overallheader.php';
?>
<script language="JavaScript">
function toggle(source, name) {
  checkboxes = document.getElementsByName(name);
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>
<?php
if(array_key_exists('advisorID', $_SESSION)){
?>
<h2>Edit Schedule</h2>
<form id="weekForm" action="EditSchedule.php" method="post">
<?php include 'includes/selectWeek.php'; ?>
</form>
<?php
  if(count($_POST) > 1){
       $openAllowed = true;
    echo "<form action=\"EditDay.php\" method=\"post\">";
    echo "<br><div id=\"selectTitle\">Select a day to edit:</div>";
    $week = $CALENDAR->weeks[(int)$_POST['week']];
    echo "<div id=\"selectGroup\"><table id=\"selectGroupTable\">";
    for($i = 0; $i < 5; $i++){
      $date = $week->dates[$i];
        echo "<tr><td><input type=\"checkbox\" name=\"date[]\" value=\"".$date."\">";
        echo date_to_string($date)."</td>";
        
        
        //only allow set open if dates are after 3/23
        if(substr($date, 6, 1) == "3" && substr($date, 8, 2) < "23"){
            $openAllowed = false;
        }
        if($openAllowed){
            echo "<td><input type=\"checkbox\" name=\"SetOpen[]\" value=\"".$date."\">";
            echo "Open Appts</td>";
        }
        
        echo "<td><input type=\"checkbox\" name=\"SetGroups[]\" value=\"".$date."\">";
        echo "Set Groups</td>";
      echo "</tr>";
    }
?>
<tr><td><input type="checkbox" onClick="toggle(this, 'date[]')" />Select All</td>
    <?php if($openAllowed){ ?>
    <td><input type="checkbox" onClick="toggle(this, 'SetOpen[]')" />Open All Appts</td>
    <?php } ?>
    <td><input type="checkbox" onClick="toggle(this, 'SetGroups[]')" />Set All Groups</td></tr>
<?php
    echo "</table></div>";
    echo "<div id=\"submit\"><input type=\"submit\" name=\"Edit Day\">";
    echo "</div></form>";
  }
}
else {
    echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
    echo "You are not logged in.</div>";
}
include 'includes/overallfooter.php';
?>