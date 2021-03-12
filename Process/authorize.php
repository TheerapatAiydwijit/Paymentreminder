<?php
// include("../include/loginChek.php");
define('CLIENT_ID', '1Z7fhAlcpFRdHoZFsQDVQv');
define('LINE_API_URI', 'https://notify-bot.line.me/oauth/authorize?');
define('CALLBACK_URI', 'https://reminderthaiweb2.000webhostapp.com/Process/subscription.php');
date_default_timezone_set('asia/bangkok');
$Unit = date('dmyis');
$random = rand(1,9);
$random .=$Unit;
$random .= $_SESSION['USERID'];
$queryStrings = [
    'response_type' => 'code',
    'client_id' => CLIENT_ID,
    'redirect_uri' => CALLBACK_URI,
    'scope' => 'notify',
    'response_mode' => 'form_post',
    'state' => $random
];
$queryString = LINE_API_URI . http_build_query($queryStrings);
?>
