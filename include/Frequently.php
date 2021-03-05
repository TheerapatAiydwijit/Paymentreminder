<?php 
function DateThai($strDate)
{
    $strYear = date("Y", strtotime($strDate)) + 543;
    $strMonth = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    $strHour = date("H", strtotime($strDate));
    $strMinute = date("i", strtotime($strDate));
    $strSeconds = date("s", strtotime($strDate));
    $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
    $strMonthThai = $strMonthCut[$strMonth];
    return "$strDay $strMonthThai $strYear";
}
function difference($date)
{
    $datatime = new DateTime($date);
    $second_date = new DateTime(date("Y-m-d G:i:s"));
    $difference = $datatime->diff($second_date);
    $years = $difference->format('%y');
    $months  = $difference->format('%m');
    $days  = $difference->format('%d');
    $hours  = $difference->format('%h');
    $minutes = $difference->format('%i');
    $seconds = $difference->format('%s');
    if (!$years == "0") {
        return $years . " ปี";
    } elseif (!$months == "0") {
        return $months . " เดือน";
    } elseif (!$days == "0") {
        return $days . " วัน";
    } elseif (!$hours == "0") {
        return $hours . " ชม";
    } elseif (!$minutes == "0") {
        return $minutes . " นาที";
    } elseif (!$seconds == "0") {
        return $seconds . " วินาที";
    }
}
?>