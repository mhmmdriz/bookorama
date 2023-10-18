<?php
require_once('../lib/session.php');

require_once('../lib/db_login.php');

$orderid = $_GET['orderid'];

$query = "DELETE FROM orders where orderid = $orderid";
$query1 = "DELETE FROM order_items where orderid = $orderid";

        
// Execute the query
$result = $db->query($query);
$result1 = $db->query($query1);
header("Location:index.php");
?>