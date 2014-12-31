<?php
session_start();
include 'databasefunctions.php';
  
if (isset($_POST["submit"])) {
    $filter[EMP_EMAIL] = $_POST["emailName"];
    $employees = RetrieveEmployees($filter);
    if (count($employees)==1)
    {
        $encryptedPassword = $employees[0][EMP_PASSWORD];
        $temp = md5(md5($_POST["emailName"]).$_POST["password"]);
        
        if ($temp == $encryptedPassword)
        {
            $_SESSION['userID'] = $employees[0][EMP_ID];
            $_SESSION['administrator'] = $employees[0][EMP_ADMIN_PERM];
            $_SESSION['manager'] = $employees[0][EMP_MANAGER_PERM];
            header('Location: index.php');
        }
        else
        {
            echo "Login Failed";
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
 
    <body>
        <form method="post">
            <label for="userName">Email Address</label>
            <input type="text" name="emailName" id="emailName" placeholder="Enter your email address"/> 

            <br/>    

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter Password"/> 
                  
            <input type="submit" name="submit" id="submit" value="Login"/> 
            
        </form>

    </body>

</html>
