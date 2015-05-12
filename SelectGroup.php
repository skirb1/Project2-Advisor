<?php
include_once 'init.php';
include_once 'includes/overallheader.php';

if(array_key_exists('advisorID', $_SESSION)){
  $advisorID = $_SESSION['advisorID'];
?>
<h2>Select Group</h2>
<?php
if(!array_key_exists('date', $_POST)){  
?>
<form id="weekForm" action="SelectGroup.php" method="post">
<?php include 'includes/selectWeek.php'; ?>
</form>
<?php
    }
    if(array_key_exists('week', $_POST) && !array_key_exists('date', $_POST)){
        echo "<form action=\"SelectGroup.php\" method=\"post\">";
        echo "<br><div id=\"selectTitle\">Select day:</div>";
        $week = $CALENDAR->weeks[(int)$_POST['week']];
        echo "<div id=\"list\"><ul id=\"listDaysToEdit\">";
        for($i = 0; $i < 5; $i++){
          $date = $week->dates[$i];
          echo "<li><input type=\"radio\" name=\"date\" value=\"".$date."\">";
          echo date_to_string($date)."</li>";
        }
        echo "</ul></div>";       
        echo "<div id=\"submit\"><input type=\"submit\" name=\"submitDay\">";
        echo "</div>";
        echo "<input type=\"hidden\" name=\"week\" value=\"".$_POST['week']."\">";
        if(array_key_exists('submitDay', $_POST)){
            echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
            echo "Please select a date</div>";   
        }
    }
    
    else if (array_key_exists('date', $_POST) && !array_key_exists('time', $_POST) ){
        echo "<div id=\"selectTitle\">Select group advising time for ";
        $date = $_POST['date'];
        echo date_to_string($date)."</div>";
        echo "<div id=\"selectGroup\">";
        echo "<form action=\"EditGroup.php\" method=\"post\">";
        $timecount = 0;
        
        echo "<table id=\"selectGroupTable\">";
       // echo "<tr><td><input type=\"radio\" name=\"time\" value=\"New\"><b>Add New</b></td></tr>"; 
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
            //if time is not in db
            else {
                echo "<tr><td><b>";
                echo "<input type=\"radio\" name=\"time\" value=\"".$time."\">";
                echo display_time($time)."</b></td><td>(Add time)</td>";
            }
        }
        echo "</table></div>";
        echo "<input type=\"hidden\" name=\"date\" value=\"".$date."\">";
        echo "<div id=\"submit\"><input type=\"submit\" name=\"submitTime\"></div>";
    }
    echo "</form>";
}
else {
    echo "<div id=\"error\">";
    echo "<img src=\"includes/error.png\" id=\"errorImg\">";
    echo "You are not logged in.</div>";
}
include_once 'includes/overallfooter.php';
?>