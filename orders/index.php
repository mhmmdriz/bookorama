<?php
require_once('../lib/session.php');

include('../template/header.php');
// Include our login information
require_once('../lib/db_login.php');
$kategori = query_to_array("SELECT * from categories");

$query = "SELECT orderid AS OrderID, amount AS Amount, date AS Date, customers.name AS Customer
        FROM orders, customers
        WHERE orders.customerid = customers.customerid";

if (isset($_POST["submit"])) {  
    $start = test_input($_POST['start']);
    $finish = test_input($_POST['finish']);


    if($start != ''){
        $query = $query . " AND date >= '$start'";
    }
    if($finish != ''){
        $query = $query . " AND date <= '$finish'";
    }

}

// Execute the query
$result = $db->query($query);

if (!$result) {
    die("Could not query the database: <br />" . $db->error . "<br>Query: " . $query);
}
?>

<div class="card col-md-12 my-5">
    <div class="card-header">Orders Data</div>
    <div class="card-body">
        <form action="" method="post">
            <div class="row">
                <div class="col-lg-2 mb-3">
                    <input type="date" class="form-control" name="start" id="start" autocomplete="off" value="<?php if (isset($start)) echo $start; ?>">
                </div>
                -
                <div class="col-lg-2 mb-3">
                    <input type="date" class="form-control" name="finish" id="finish" autocomplete="off" value="<?php if (isset($finish)) echo $finish; ?>">
                </div>
                <div class="col-lg-auto mb-3">
                    <button class="btn btn-primary" type="submit" id="tombolCari" name="submit">Search</button>
                    <a href="./index.php">
                        <button class="btn btn-secondary" name="reset">Reset</button>
                    </a>
                </div>
            </div>
        </form>

        <!-- <a  class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#formModal">+ Add Customer Data</a> -->
        <a  class="btn btn-primary mb-4" href="add_order.php">+ Add Order Data</a>
        <br>

        <table class="table table-striped">
            <tr>
                <th>Order ID</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Action</th>
            </tr>

            <?php
            $i = 1;
            // Fetch and display the results
            while ($row = $result->fetch_object()) {
            ?>
                <tr>
                    <td><?= $i?></td>
                    <td><?= $row->Amount?></td>
                    <td><?= $row->Date?></td>
                    <td><?= $row->Customer?></td>
                    <td>
                    <a class="btn btn-primary btn-sm" href="./detail_order.php?orderid=<?= $row->OrderID?>">Detail</a>
                    <a class="btn btn-danger btn-sm" href="./delete_order.php?orderid=<?= $row->OrderID?>" onclick="return confirm('Are you sure')">Delete</a>
                    </td>
                </tr>
            <?php
                $i++;
            }
            echo '</table>';
            echo '<br />';
            echo 'Total Rows = ' . $result->num_rows;

            $result->free();
            ?>
    </div>
</div>

<?php $db->close();?>
<?php include('../template/footer.php') ?>
