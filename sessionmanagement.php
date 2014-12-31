<?php
session_start();

if ( (!isset($_SESSION['userID'])) OR
     (!isset($_SESSION['administrator'])) OR
     (!isset($_SESSION['manager']) ))  
{
   header('Location: login.php');
   exit();
}

$userID          = $_SESSION['userID'];
$isAdministrator = $_SESSION['administrator']; 
$isManager       = $_SESSION['manager'];    

?>