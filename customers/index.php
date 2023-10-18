<?php
require_once('../lib/session.php');

include('../template/header.php') ?>
<div class="card col-md-12 mt-5">
    <div class="card-header">Customers Data</div>
    <div class="card-body">
        <a href="add_customer.php" class="btn btn-primary mb-4">+ Add Customer Data</a>
        <br>
        <table class="table table-striped">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Address</th>
                <th>City</th>
                <th>Action</th>
            </tr>
            <?php
            // Include our login information
            require_once('../lib/db_login.php');

            // Execute the query
            $query = "SELECT customerid AS ID, name AS Nama, address AS Alamat, city AS Kota FROM customers ORDER BY customerid";
            $result = $db->query($query);

            if (!$result) {
                die("Could not query the database: <br />" . $db->error . "<br>Query: " . $query);
            }

            // Fetch and display the results
            $i = 1;
            while ($row = $result->fetch_object()) {
            ?>
                <tr>
                <td><?= $i?></td>
                <td><?= $row->Nama?></td>
                <td><?= $row->Alamat?></td>
                <td><?= $row->Kota?></td>
                <td>
                    <a class="btn btn-warning btn-sm" href="edit_customer.php?id=<?=$row->ID?>">Edit</a>
                    <a class="btn btn-danger btn-sm" href="delete_customer.php?id=<?=$row->ID?>" onclick="return confirm('Are you sure')">Delete</a>
                </td>
                </tr>

            <?php
                $i++;
            }
            echo '</table>';
            echo '<br />';
            echo 'Total Rows = ' . $result->num_rows;

            $result->free();
            $db->close();
            ?>
    </div>
</div>
<?php include('../template/footer.php') ?>