<?php
session_start();
if (!isset($_COOKIE['USERID'])) {
  if (!isset($_SESSION['USERID'])) {
    header("Location: index.php");
  }
}else{
  $_SESSION['USERID']=$_COOKIE['USERID'];
}
