<?php
class Week {
  var $dates = array();

  //for adding weeks to the calendar
  function Week($month, $day, $year) {
    $year = (int)("20".$year);
    $yearStr = $year."-";

    //set the dates of the week
    //check for the end of the month
    for($i = 0; $i < 5; $i++){
        if($day > cal_days_in_month(CAL_GREGORIAN, $month, $year)){
	       $month++;
	       $day = 1;
            $dayStr = "01";
            
            if(strlen((string)$month) == 1){
                $monthStr = "0".(string)$month."-";
            }
            else{
                $monthStr = (string)$month."-";
            }
            
            $this->dates[$i] = $yearStr.$monthStr.$dayStr;
        }
        else{
            
            if(strlen((string)$day) == 1){
                $dayStr = "0".(string)$day;
            }
            else{
                $dayStr = (string)$day;
            }
            
            if(strlen((string)$month) == 1){
                $monthStr = "0".(string)$month."-";
            }
            else{
                $monthStr = (string)$month."-";
            }
            
            $this->dates[$i] = $yearStr.$monthStr.$dayStr;
        }
        $day += 1;
    }

  }

  }//end of class Week
?>