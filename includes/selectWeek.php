<div id="selectTitle">Select week:</div>
<select name="week" id="week">
<?php
   for($i = 0; $i < count($CALENDAR->weeks); $i++){
       $weekDates = $CALENDAR->weeks[$i]->dates;
        $first = $weekDates[0];
        $last = $weekDates[4];
        $weekString = short_string($first)." - ".short_string($last);
        echo "<option ";
        if( date("Y-m-d") == $weekDates[0] || date("Y-m-d") == $weekDates[1]
           || date("Y-m-d") == $weekDates[2] || date("Y-m-d") == $weekDates[3]
           || date("Y-m-d") == $weekDates[4] ) {
            echo "selected ";   
        }
        echo "value=\"".$i."\">".$weekString."</option>";
   }
?>
</select>
<input type="submit" name="Select Week">