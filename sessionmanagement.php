<?php
session_start();

if (!isset($_SESSION['StatusDiv']))
{
    $_SESSION['StatusDiv'] = "";
}

//----------------------------------------------------------------------------
// If the session variables are not set, then the user is not logged in.
// If this happens, redirect the user to the login page.
//----------------------------------------------------------------------------
if ( (!isset($_SESSION['userID'])) OR
     (!isset($_SESSION['administrator'])) OR
     (!isset($_SESSION['manager']) ))  
{
   header('Location: login.php');
   exit();
}

//Set up some local variables using the values of the session variables.
//All of our web code can then refer to these variable names.
$userID          = $_SESSION['userID'];
$isAdministrator = $_SESSION['administrator']; 
$isManager       = $_SESSION['manager'];    

?>