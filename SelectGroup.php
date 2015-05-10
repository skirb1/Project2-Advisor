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
    if(array_key_exists('week', $_POST)){
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
    }
    
    else if (array_key_exists('date', $_POST) && !array_key_exists('time', $_POST) ){
        echo "<div id=\"selectTitle\">Select group advising time for ";
        $date = $_POST['date'];
        echo date_to_string($date)."</div>";
        echo "<div id=\"selectGroup\">";
        echo "<form action=\"EditGroup.php\" method=\"post\">";
        echo "<div id=\"list\"><ul>";
        $timecount = 0;
        $sql = "SELECT * FROM Group_Schedule WHERE date = '$date'";
        $record = $COMMON->executeQuery($sql, $_SERVER["SelectGroup.php"]);
        
        for($i = 0; $i < mysql_num_rows($record); $i++){
            $db_time = mysql_result($record, $i, 'time');
            if(!is_group_null($date, $db_time)){
                $time = short_time($db_time);
                echo "<li>";
                echo "<input type=\"radio\" name=\"time\" value=\"".$time."\">";
                echo display_time($time)."<div id=\"tab\">Advisors: ";
                echo count_advisors($date, $time);
                echo "<div id=\"tab\">Students: ".count_students($date, $time)."</li>";
                $timecount += 1;
            }
        }
        echo "</ul></div>";
        echo "<input type=\"hidden\" name=\"date\" value=\"".$date."\">";
        echo "<div id=\"submit\"><input type=\"submit\" name=\"submitDay\"></div>";
    }
    echo "</form>";
}

include_once 'includes/overallfooter.php';
?>