<?php

$majors = array( NULL, "CMPE", "CMSC", "ENGR", "MENG", "CENG");

function get_advisors($date, $time){
    global $COMMON;
    $advisors = array();
    $sql = "SELECT * FROM Group_Schedule WHERE date = '$date' AND time = '$time'";
    $record = $COMMON->executeQuery($sql, $_SERVER["Groups.php"]);
    if(mysql_num_rows($record) == 1){
        $recordAssoc = mysql_fetch_assoc($record);

        for( $i = 1; $i <= 4; $i++){
            $key = "advisor".$i;
            if($recordAssoc[$key] != NULL){
                array_push( $advisors, $recordAssoc[$key] );
            }
        }
    }
    
    return $advisors;
}

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

function count_advisors($date, $time){
    global $COMMON;
    $count = 0;
    $sql = "SELECT * FROM Group_Schedule WHERE date = '$date' AND time = '$time'";
    $record = $COMMON->executeQuery($sql, $_SERVER["Groups.php"]);
    if(mysql_num_rows($record) == 1){
        $groupArray = mysql_fetch_assoc($record);        
        for($i = 1; $i <= 4; $i++){
            $key = "advisor".$i;
            $element = $groupArray[$key];
            if($element !== NULL && $element != "Closed"){
                $count++;
            }
        }
    }
    return $count;
}

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


function is_group_null($date, $time){
    global $COMMON;
    $isNull = true;
    if(count_advisors($date, $time) > 0 || count_students($date, $time) > 0){
        $isNull = false; 
    }
    return $isNull;
}

function update_group($advisorID, $date, $time){
    global $COMMON;
    $advisorSet = false;
    $advisorField = 0;
    $result = true;

    //see if advisor is in Group_Schedule
    $sql = "SELECT * FROM Individual_Schedule WHERE `advisorID` = '$advisorID'";
    $sql .= " AND `date` = '$date'";
    $indivRecord = $COMMON->executeQuery($sql, $_SERVER["Groups.php"]);
    //get advisor schedule for the date specified, get group times
    if($indivRecord !== false && mysql_num_rows($indivRecord) == 1){
        $element = mysql_result($indivRecord, 0, $time);
        
        $sql = "SELECT * FROM `Group_Schedule` WHERE ";
        $sql .= "`date` = '$date' AND `time` = '$time'";
        $groupRecord = $COMMON->executeQuery($sql, $_SERVER["Groups.php"]);
        echo mysql_num_rows($groupRecord)."   ";
            
        //if Individual_Sched doesnt say group, but advisor is in Group_Sched
        //we need to delete advisor from Group_Sched for that time
        if($groupRecord !== false && mysql_num_rows($groupRecord) == 1){
            //check if advisor is scheduled in group table
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
            else if ($groupAssoc['advisor4'] == $advisorID ){
                $advisorSet = true;   
                $advisorField = "advisor4";
            }

            //if advisor is in group table and not scheduled for group
            //in individual table, delete advisorID from group table
            if( $advisorSet && $element != "Group"){
                $sql = "UPDATE `Group_Schedule` SET `".$advisorField."` = NULL" ;
                $sql .= " WHERE `date` = '$date' AND `time` = '".$time."'";
                $record = $COMMON->executeQuery($sql, $_SERVER["Groups.php"]);
                if($record === false){
                    echo "<div id=\"error\">Error removing advisor from group schedule</div>";
                    $result = false;
                }
            }
        }
        //other possibile update: if advisor is scheduled for group in Individual_Sched
        //but not in Group_Sched
        else if ( !$advisorSet && $element == "Group"){
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
            else if ($groupAssoc['advisor4'] == NULL ){ 
                $advisorField = "advisor4";
                $openField = true;
            }

            if($openField){
                $sql = "UPDATE `Group_Schedule` SET `".$advisorField."` = '$advisorID'" ;
                $sql .= " WHERE `date` = '$date' AND `time` = '".$time."'";
                $record = $COMMON->executeQuery($sql, $_SERVER["Groups.php"]);
                if($record === false){
                    echo "<div id=\"error\">Error adding advisor to group schedule</div>";
                    $result = false;
                }
            }
            else {
                echo "<div id=\"error\">This group has enough advisors</div>";
                $result = false;
            }
        }//end of else if(no group record found)

        //if group doesnt exist yet but advisor is scheduled for group in Indiv
        //add group record w/ advisorID
        else if($element == "Group"){
            $sql = "INSERT INTO Group_Schedule (date, time, advisor1)";
            $sql .= " VALUES ( '$date', '$time', '$advisorID');";
            $record = $COMMON->executeQuery($sql, $_SERVER["Groups.php"]);
            if($record === false){
                echo "<div id=\"error\">Error adding advisor to group schedule (new)</div>";
                $result = false;
            }
        }

  }//end of if(indiv record is valid)
  else {
    echo "<div id=\"error\">Error accessing individual table</div>";
    $result = false;
  }
  return result;
}//end of function update_tables

?>