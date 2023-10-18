<?php
require_once('../lib/session.php');

require_once('../lib/db_login.php');

$id = $_GET['id'];

$query = "DELETE FROM customers where customerid = $id";

        
// Execute the query
$result = $db->query($query);
header("Location:index.php");
?>