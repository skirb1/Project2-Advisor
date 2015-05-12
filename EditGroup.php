<?php
include_once 'init.php';
include_once 'includes/overallheader.php';

if(array_key_exists('advisorID', $_SESSION)){
  $advisorID = $_SESSION['advisorID'];
?>
<h2>Edit Group</h2>
<?php
/*error checking*/
    if(array_key_exists('time', $_POST) &&
      array_key_exists('date', $_POST)){
        $time = db_time($_POST['time']);
        $date = $_POST['date'];
        
        //if(mysql_num_rows(record) == 1){
            echo "<form name=\"EditGroup\" action=\"UpdateGroup.php\" method=\"post\">";
            echo "<div id=\"selectTitle\">".display_time($time)." on ";
            echo date_to_string($date)."</div>";
            echo "<div id = \"indent\">";
        //get group size and display select
            $size = get_size($date, $time);
            echo "<ul id=\"radio\">";
            echo "<li><div id=\"selectTitle\">Group Size:</div></li>";
            echo "<li><input type=\"radio\" name=\"size\" value=\"5\"";
            if($size == 5) { echo " checked "; }
            echo ">5</li>";
            echo "<li><input type=\"radio\" name=\"size\" value=\"10\"";
            if($size == 10) { echo " checked "; }
            echo ">10</li>";
            echo "</ul>";

        //get group major and display select
            $major = get_major($date, $time);
            echo "<ul id=\"radio\">";
            echo "<li><div id=\"selectTitle\">Majors:</div></li>";
            foreach($majors as $m){
                if($m == NULL){
                    echo "<li><input type=\"radio\" name=\"major\" value=\"NULL\"";
                    if ($major == NULL) { echo " checked "; }
                    echo ">All Majors</li>";
                }
                else {
                    echo "<li><input type=\"radio\" name=\"major\" value=\"".$m."\"";
                    if($major == $m) { echo " checked "; }
                    echo ">".$m."</li>";
                }
            }
            echo "</ul>";

        // get advisors and display checkboxes
            $advisorArray = get_group_advisors($date, $time);
            $sql = "SELECT * FROM Advisors";
            $record = $COMMON->executeQuery($sql, $_SERVER["Advisor.php"]);
            echo "<ul id=\"radio\">";
            echo "<li><div id=\"selectTitle\">Advisors (up to three):</div></li>";
            for($i = 0; $i < count_advisors(); $i++){
                $fname = mysql_result($record, $i, 'firstName');
                $lname = mysql_result($record, $i, 'lastName');
                $advisorID = mysql_result($record, $i, 'advisorID');
                echo "<li><input type=\"checkbox\" name=\"advisor".$i;
                echo "\" value=\"".$advisorID."\"";
                if( in_array( $advisorID, $advisorArray ) ){ echo " checked "; }
                echo ">".$fname." ".$lname."</li>";

            }
            echo "</ul>";
        //pass on date and time
            echo "<input type=\"hidden\" name=\"date\" value=\"".$date."\">";
            echo "<input type=\"hidden\" name=\"time\" value=\"".$time."\">";
            echo "<div id=\"submit\"><input type=\"submit\" name=\"submitGroup\">";
            echo "</div></div></form>";

        }
   // }
    else {
        echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
        echo "Please enter a time</div>";
        /*back button*/
    }
} else {
    echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
    echo "You are not logged in<div>";   
}
include_once 'includes/overallfooter.php';
?>