<?php

function printCallstackAndDie()
{
	echo "Fatal Error. Please contact your system administrator.<br/>";
	
	$callers=debug_backtrace();

        echo "Dump Trace<br/>";
	foreach ($callers as $caller)
	{
		echo "Function:   ".$caller['function']."    Line:   ".$caller['line']."<br/>";
	}
        die();
}      

function connectToSql($server,$username,$password)
{
    $connection= mysqli_connect($server,$username,$password);
    if (! $connection)
    { 
    	printCallstackAndDie();
    }
    return $connection;
}

function performSQL($connection,$sql)
{
    $result = mysqli_query($connection,$sql);
    if (! $result)
    {
    	printCallstackAndDie();
    }   
    return TRUE;
}      

function performSQLInsert($connection,$sql)
{
    $result = mysqli_query($connection,$sql);
    if (! $result)
    {
    	printCallstackAndDie();
    }   
    return mysqli_insert_id($connection);
}

function performSQLSelect($connection,$tableName,$filter)     
{
    $sql ="SELECT * FROM ".$tableName;
	
    if ($filter != NULL)
    {
	$sql = $sql." WHERE ";

        foreach($filter as $key=>$value)
	{
        	$whereClause[] = $key."='".$value."'";
	}
	
	$sql = $sql.implode(" AND ",$whereClause);
    }
      	
    $result = mysqli_query($connection,$sql);
    if (! $result)
    {
 	  	printCallstackAndDie();
    }
    
    $results[] = NULL;
    while ($row = mysqli_fetch_array($result))
    {
    	$results[] = $row;
    }   
    return $results; 
}

function performSQLUpdate($connection,$tableName,$idFieldName,$fields)     
{
 	$sql ="UPDATE ".$tableName." SET ";
	
	if ($fields <> NULL)
	{
           	foreach($fields as $key=>$value)
		{
                    if (!is_numeric($key) AND $key <> $idFieldName)
                    {
                        if ($value <> NULL )
                        {
                            $updateClause[] = $key."='".$value."'";
                        }
                        else
                        {
                            $updateClause[] = $key."=NULL";
                        
                        }
                    }
		}
	
        	$sql = $sql.implode(",",$updateClause);
	}
   	$sql = $sql." WHERE ".$idFieldName."='".$fields[$idFieldName]."';";
        
       $result = mysqli_query($connection,$sql);
    if (! $result)
    {
           echo mysqli_error($connection);
 	  	printCallstackAndDie();
    }
    
    return TRUE;
 }

function useDB($connection)
{
	$sql ="USE mydb;";
	performSQL($connection,$sql);
}

function dropDB($connection)
{
    $sql ="DROP DATABASE IF EXISTS `mydb`;";
    performSQL($connection,$sql);
}

function createDB($connection)
{
    $sql ="CREATE SCHEMA IF NOT EXISTS `mydb`".
    		"DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;";
    performSQL($connection,$sql);
}

?>