<?php
session_start();
    if(isset($_SESSION['USERID'])){
        // remove all session variables
        session_unset();
        // destroy the session
        session_destroy();
    }
header("Location: ../index.php");