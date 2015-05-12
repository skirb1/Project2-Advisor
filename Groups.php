<?php
//majors accommodated by COEIT advising, NULL indicates any major
$majors = array( NULL, "CMPE", "CMSC", "ENGR", "MENG", "CENG");

//returns true if element has two letters followed by 5 digits
function is_studentID($element){
    $result = false;
    if(strlen($element) == 7 && ctype_digit(substr($element, 2, 5))
       && ctype_alpha(substr($element, 0, 2)) ) {
        $result = true;   
    }
    return $result;
}

//returns array of advisor ID's stored for a group date/time
function get_group_advisors($date, $time){
    global $COMMON;
    $advisors = array();
    $sql = "SELECT * FROM Group_Schedule WHERE date = '$date' AND time = '$time'";
    $record = $COMMON->executeQuery($sql, $_SERVER["Groups.php"]);
    if(mysql_num_rows($record) == 1){
        $recordAssoc = mysql_fetch_assoc($record);

        for( $i = 1; $i <= 3; $i++){
            $key = "advisor".$i;
            if($recordAssoc[$key] != NULL){
                array_push( $advisors, $recordAssoc[$key] );
            }
        }
    } 
    return $advisors;
}

//returns major for a group date/time
function get_major($date, $time){
    global $COMMON;
    $major = NULL;
    $sql = "SELECT * FROM Group_Schedule WHERE date = '$date' AND time = '$time'";
    $record = $COMMON->executeQuery($sql, $_SERVER["Groups.php"]);
    if(mysql_num_rows($record) == 1){
        $groupArray = mysql_fetch_assoc($record);        
        $major = $groupArray['major'];   
    }
    return $major;
}

//returns student limit for group (5 or 10)
function get_size($date, $time){
    global $COMMON;
    $size = 0;
    $sql = "SELECT * FROM Group_Schedule WHERE date = '$date' AND time = '$time'";
    $record = $COMMON->executeQuery($sql, $_SERVER["Groups.php"]);
    if(mysql_num_rows($record) == 1){
        $groupArray = mysql_fetch_assoc($record);        
        for($i = 1; $i <= 10; $i++){
            $key = "student".$i;
            $element = $groupArray[$key];
            if($element != "Closed"){
                $size++;
            }
        }      
    }
    return $size;
}

//returns number of advisors stored in group
function count_group_advisors($date, $time){
    global $COMMON;
    $count = 0;
    $sql = "SELECT * FROM Group_Schedule WHERE date = '$date' AND time = '$time'";
    $record = $COMMON->executeQuery($sql, $_SERVER["Groups.php"]);
    if(mysql_num_rows($record) == 1){
        $groupArray = mysql_fetch_assoc($record);        
        for($i = 1; $i <= 3; $i++){
            $key = "advisor".$i;
            $element = $groupArray[$key];
            if($element !== NULL && $element != "Closed"){
                $count++;
            }
        }
    }
    return $count;
}

//returns number of students scheduled for group
function count_students($date, $time){
    global $COMMON;
    $count = 0;
    $sql = "SELECT * FROM Group_Schedule WHERE date = '$date' AND time = '$time'";
    $record = $COMMON->executeQuery($sql, $_SERVER["Groups.php"]);
    if(mysql_num_rows($record) == 1){
        $groupArray = mysql_fetch_assoc($record);        
        for($i = 1; $i <= 10; $i++){
            $key = "student".$i;
            $element = $groupArray[$key];
            if($element !== NULL && $element != "Closed"){
                $count++;
            }
        }      
    }
    return $count;
}

//returns true if there are no advisors or students stored for this group
function is_group_null($date, $time){
    global $COMMON;
    $isNull = true;
    if(count_advisors($date, $time) > 0 || count_students($date, $time) > 0){
        $isNull = false; 
    }
    return $isNull;
}

//updates specified date/time to Group if the advisor is available
function update_advisor($advisorID, $date, $time){
    global $COMMON;
    $result = false;
    
    $sql = "SELECT * FROM Individual_Schedule WHERE advisorID='$advisorID' AND date='$date'";
    $record = $COMMON->executeQuery($sql, $_SERVER["Groups.php"]);
    if(mysql_num_rows($record) == 1){
        $element = mysql_result($record, 0, $time);
        if(is_studentID($element) == false){
            if($element != "Closed"){
                $sql = "UPDATE Individual_Schedule SET `".db_time($time)."`='Group' WHERE";
                $sql .= " `advisorID`='$advisorID' AND `date`='$date'";
                $record = $COMMON->executeQuery($sql, $_SERVER["Groups.php"]);
                if($record != false){
                    $result = true;
                }
                else {
                    echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
                    echo "Error updating advisor schedule</div>";
                }
            }
            else {
                echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
                echo name_from_advisorID($advisorID)." is unavailable at this time</div>";
            }
        }
        else{
            echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
            echo name_from_advisorID($advisorID)." has an appointment at this time</div>";
        }
    }
    return $result;
}

//updates Group_Schedule table to match Individual_Schedule table
function update_group($advisorID, $date, $time, $value){
    global $COMMON;
    $advisorSet = false;
    $advisorField = 0;
    $result = false;
        
    $sql = "SELECT * FROM `Group_Schedule` WHERE ";
    $sql .= "`date` = '$date' AND `time` = '$time'";
    $groupRecord = $COMMON->executeQuery($sql, $_SERVER["Groups.php"]);

    //if this date and time exist in Group_Schedule
    if($groupRecord != false && mysql_num_rows($groupRecord) == 1){
        //check if advisor is scheduled in this record
        $groupAssoc = mysql_fetch_assoc($groupRecord);
        if($groupAssoc['advisor1'] == $advisorID) {
            $advisorSet = true;
            $advisorField = "advisor1";
        }
        else if ($groupAssoc['advisor2'] == $advisorID) {
            $advisorSet = true;
            $advisorField = "advisor2";
        }
        else if ($groupAssoc['advisor3'] == $advisorID ){
            $advisorSet = true;   
            $advisorField = "advisor3";
        }

        //if advisor is in group table and not scheduled in Individual_Schedule
        //delete advisorID from group table
        if( $advisorSet && $value != "Group"){
            $sql = "UPDATE `Group_Schedule` SET `".$advisorField."` = NULL" ;
            $sql .= " WHERE `date` = '$date' AND `time` = '".$time."'";
            $record = $COMMON->executeQuery($sql, $_SERVER["Groups.php"]);
            if($record === false){
                echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
                echo "Error removing advisor from group schedule</div>";
                $result = false;
            }
            else {
                $result = true;   
            }
        }
        
        //if advisor is in Group_Schedule, and is scheduled in Individual_Schedule,
        //return true, no updates need to be made
        if($advisorSet && $value == "Group"){
            $result = true;   
        }
        
        //if advisor is not in group, and not scheduled for group in Individual Sched
        //return true, no updates need to be made
        if($advisorSet == false && $value != "Group"){
            $result = true;
        }
        
        //if advisor is not set and they are scheduled in Individual Sched
        //we need to add advisor if there are any advisor spots
        if($advisorSet == false && $value == "Group"){
            $openField = false;
            //find open advisor field
            if($groupAssoc['advisor1'] == NULL) {
                $advisorField = "advisor1";
                $openField = true;
            }
            else if ($groupAssoc['advisor2'] == NULL) {
                $advisorField = "advisor2";
                $openField = true;
            }
            else if ($groupAssoc['advisor3'] == NULL ){ 
                $advisorField = "advisor3";
                $openField = true;
            }

            if($openField){
                $sql = "UPDATE `Group_Schedule` SET `".$advisorField."` = '$advisorID'" ;
                $sql .= " WHERE `date` = '$date' AND `time` = '".$time."'";
                $record = $COMMON->executeQuery($sql, $_SERVER["Groups.php"]);
                if($record === false){
                    echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
                    echo "Error adding advisor to group schedule</div>";
                    $result = false;
                }
                else {
                 $result = true;   
                }
            }
            else {
                echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
                echo "This group already has three advisors</div>";
                $result = false;
            }
        }  
    }

    //if group doesnt exist yet but advisor is scheduled for group in Indiv
    //add group record for this date and time w/ advisorID
    else if(mysql_num_rows($groupRecord) == 0){
        if ( $value == "Group"){
            $sql = "INSERT INTO Group_Schedule (date, time, advisor1)";
            $sql .= " VALUES ( '$date', '$time', '$advisorID');";
            $record = $COMMON->executeQuery($sql, $_SERVER["Groups.php"]);
            if($record == false){
                echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
                echo "Error adding advisor to group schedule</div>";
                $result = false;
            }
            else {
                $result = true;   
            }
        }
        //advisor is not scheduled for group, no update    
        else {
            $result = true;
        }
    }
        
    else {
     echo "<div id=\"error\">Missing error....</div>"; 
    }
    return $result;
}//end of function update_tables

?>