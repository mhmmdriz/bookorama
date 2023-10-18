<?php 
$db_host = 'localhost';
$db_database = 'bookorama2';
$db_username = 'root';
$db_password = '';

//  Connect database
$db = new mysqli($db_host, $db_username, $db_password, $db_database);
if ($db->connect_errno) {
    die('Could not connect to the database: <br/>' . $db->connect_error);
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function query_to_array($kueri){
    global $db;
    $result = $db->query($kueri);
    $rows = [];
    while($row = $result->fetch_assoc()){
        $rows[] = $row;
    }

    return $rows;
}

?>