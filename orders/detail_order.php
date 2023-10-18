<?php
require_once('../lib/session.php');
include('../template/header.php');
// Include our login information
require_once('../lib/db_login.php');

$orderid = $_GET['orderid'];

$query = "SELECT orders.orderid AS OrderID, books.isbn AS ISBN, books.title AS BookTitle, customers.name AS CustomerName, quantity AS Quantity, amount AS Amount, date AS Date
        FROM orders, order_items, customers, books
        WHERE orders.orderid = order_items.orderid
        AND order_items.isbn = books.isbn
        AND orders.customerid = customers.customerid
        AND orders.orderid = '$orderid'";
$result = query_to_array($query);

?>

<div class="row d-flex justify-content-center">
    <div class="col-md-9 col-sm-9 col-xs-9">
        <a href="./" class="btn btn-secondary">
            <i class="bi bi-arrow-left"> </i>
            Back
        </a>
    </div>
</div>

<div class="row d-flex justify-content-center">
    <div class="col-md-9 my-3">
        <div class="card">
            <div class="card-header">Order Details</div>
            <div class="card-body">
                <h3>Order : <?= $result[0]['OrderID']?></h3>
                <br>
                <h5>Book and Quantity</h5>
                <ul>
                    <?php
                    $length = count($result);
                    $i = 0;
                    foreach($result as $row){
                        echo '<li>'.$row['BookTitle']."(". $row['ISBN'] .") : ". $row['Quantity'].'</li>';
                    }
                    
                    ?>
                </ul>
                
                <h5>Amount</h5>
                <p><?= $result[0]['Amount']?></p>
                
                <h5>Customer</h5>
                <p><?= $result[0]['CustomerName']?></p>
                
                <h5>Date</h5>
                <p><?= $result[0]['Date']?></p>
                <br>
            </div>
        </div>
    </div>
</div>


<?php include('../template/footer.php') ?>
