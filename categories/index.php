<?php
require_once('../lib/session.php');

include('../template/header.php') ?>

<div class="row d-flex justify-content-center">
    <div class="col-md-8 my-3">
        <div class="card">
            <div class="card-header">Categories Data</div>
            <div class="card-body">
                <a href="add_category.php" class="btn btn-primary mb-4">+ Add Category Data</a>
                <br>
                <table class="table table-striped">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    // Include our login information
                    require_once('../lib/db_login.php');
        
                    // Execute the query
                    $query = "SELECT id AS ID, name AS Name FROM categories";
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
                        <td><?= $row->Name?></td>
                        <td>
                            <a class="btn btn-warning btn-sm" href="edit_category.php?id=<?=$row->ID?>">Edit</a>
                            <a class="btn btn-danger btn-sm" href="delete_category.php?id=<?=$row->ID?>" onclick="return confirm('Are you sure')">Delete</a>
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
    </div>
</div>
<?php include('../template/footer.php') ?>