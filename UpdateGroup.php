<?php
include_once 'init.php';
include_once 'includes/overallheader.php';

if(array_key_exists('advisorID', $_SESSION)){
  $advisorID = $_SESSION['advisorID'];    
?>
<h2>Update Group</h2>
<?php
    if(array_key_exists('size', $_POST) &&
        array_key_exists('major', $_POST) &&
        array_key_exists('date', $_POST) &&
        array_key_exists('time', $_POST)){
        $size = $_POST['size'];
        $major = $_POST['major'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $advisorArr = array();
        
        //if the group is not in db, add it
        $sql = "SELECT * FROM Group_Schedule WHERE date='$date' AND time='$time'";
        $record = $COMMON->executeQuery($sql, $_SERVER["UpdateGroup.php"]);
        if(mysql_num_rows($record) < 1){
            $sql = "INSERT INTO Group_Schedule (date, time) VALUES ( '$date', '$time' );";
            $record = $COMMON->executeQuery($sql, $_SERVER["UpdateGroup.php"]);
            if($record == false){
                echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
                echo "Error adding new group time.</div>";
            }
        }
        
        if(count_students($date, $time) > $size){
            echo "<div id=\"error\">";
            echo "<img src=\"includes/error.png\" id=\"errorImg\">";
            echo "There are already more than ".$size." students";
            echo " signed up for this time.</div>";
            /*back button*/
        }
        else {
            //check number of advisors checked
            $count = 0;
            for($i = 0; $i < count_advisors(); $i++){
                $checkName = "advisor".$i;
                if (array_key_exists($checkName, $_POST)){
                    $count += 1;
                    array_push($advisorArr, $_POST[$checkName]);
                }
            }

            if ($count == 0){
                echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
                echo "You must select at least one advisor.</div>";
                /*back button*/
            }
            else if ($count > 3) {
                echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
                echo "You may only select three advisors per group.</div>";   
                /*back button*/
            }
            else if ($count > 0 && $count <= 3){
                $addedAdvisors = array();
                //update each advisors schedule
                foreach($advisorArr as $adv){
                    if(update_advisor($adv, $date, $time)){
                        echo "<div id=\"error\">".name_from_advisorID($adv);
                        echo "'s schedule updated successfully.</div>";
                        array_push($addedAdvisors, $adv);
                    }
                }
                
                $newCount = sizeof($addedAdvisors);
                // update group info
                $sql = "UPDATE Group_Schedule SET major=";
                if($major == "NULL"){
                    $sql .= "null";
                }
                else { $sql .= "'$major'"; }

                //update advisor spots (up to 3)
                for($i=1; $i <= $newCount; $i++){
                    $adv = $addedAdvisors[$i-1];
                    $sql .= ", advisor".$i."='$adv'";
                }
                for($i=$newCount+1; $i<4; $i++){
                    $sql .= ", advisor".$i."=null"; 
                }
                
                //update student spots
                if($size == 5){
                    for($i=6; $i<=10; $i++){
                        $sql .= ", student".$i."='Closed'";
                    }
                }
                else if($size == 10){
                    for($i=6; $i<=10; $i++){
                        $sql .= ", student".$i."=null";
                    } 
                }
                
                $sql .= " WHERE date='$date' AND time='$time'";
                $result = $COMMON->executeQuery($sql, $_SERVER["Advisor.php"]);
                if($result == false){
                    echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
                    echo "Error updating group</div>";
                }
                else {
                    echo "<div id=\"error\">Group schedule updated successfully with: ";
                    foreach($addedAdvisors as $adv){
                     echo name_from_advisorID($adv)." ";
                    }
                    echo "</div>";
                }
                
            }
        }
    }
    else {
        echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
        echo "Please enter both size and major.</div>";   
        //back button
    }
} 
else {
    echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
    echo "You are not logged in.</div>";   
}
include_once 'includes/overallfooter.php';
?>