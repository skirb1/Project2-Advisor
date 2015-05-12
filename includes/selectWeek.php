<div id="selectTitle">Select week:</div>
<select name="week" id="week">
<?php
   for($i = 0; $i < count($CALENDAR->weeks); $i++){
       $week = $CALENDAR->weeks[$i]->dates;
        $first = $week[0];
        $last = $week[4];
        $weekString = short_string($first)." - ".short_string($last);
        echo "<option ";
        if( array_key_exists('week', $_POST)){
            if($_POST['week'] == $i){
                echo "selected ";   
            }
        }
        else if( date("Y-m-d") == $week[0] || date("Y-m-d") == $week[1]
           || date("Y-m-d") == $week[2] || date("Y-m-d") == $week[3]
           || date("Y-m-d") == $week[4] ) {
            echo "selected ";
        }
        echo "value=\"".$i."\">".$weekString."</option>";
   }
?>
</select>
<input type="submit" name="submitWeek">