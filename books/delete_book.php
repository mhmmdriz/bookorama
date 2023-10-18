<?php
require_once('../lib/session.php');
require_once('../lib/db_login.php');

$isbn = $_GET['isbn'];

$query = "DELETE FROM books where isbn = '$isbn'";
        
// Execute the query
$result = $db->query($query);
header("Location:index.php");
?>