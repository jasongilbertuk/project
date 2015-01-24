<?php

function ClearStatus()
{
    $_SESSION["StatusDiv"]="";
}

function GenerateStatus($isSuccess,$statusMessage)
{
    $statusClass = "alert-danger";
            
    if ($isSuccess)
    {
        $statusClass = "alert-success";
    }
    
    $_SESSION["StatusDiv"] = 
            "<div class='alert $statusClass alert-dismissable'>$statusMessage".
            '<button type="button" class="close" data-dismiss="alert">x</button>'.
            "</div>";
}
?>