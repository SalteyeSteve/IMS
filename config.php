<?php
include "functions.php";
session_start();

setlocale(LC_ALL, 'nld_nld');
// Login configuration

$conf["Username"]= 'root';
$conf["Password"]= '';
$conf["Host"]= 'localhost';
$conf["Database"]= "dbIMS";

// Connect with the server
$mysqli = mysqli_connect($conf["Host"], $conf["Username"], $conf["Password"], $conf["Database"]);
$link = mysqli_connect($conf["Host"], $conf["Username"], $conf["Password"], $conf["Database"]);
$db = new PDO('mysql:dbname=dbIMS; host:localhost;', $conf["Username"], $conf["Password"]);

//    $query = $db->prepare('select Incident_ID, Datum, Behandelaar from Incident order by Datum asc');
//    $query->execute();
//    $row = $query->fetchAll(PDO::FETCH_OBJ);

// If connection fails
if($mysqli == false)
{
    echo "Can't establish connection with the database server";
}
if ($db == false)
{
    echo "nope, something went wrong";
}
if($link === false)
{
    die("ERROR: Could not connect to database. " . mysqli_connect_error());
}

