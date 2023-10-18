<?php 
require_once('../lib/session.php');

include('../template/header.php');
require_once('../lib/db_login.php');

$id = $_GET['id'];

if (isset($_POST["submit"])) {  
    $valid = TRUE;
    $name = test_input($_POST['name']);
    if ($name == '') {
        $error_name = "Name is required";
        $valid = FALSE;
    }

    if ($valid) {
        // Assign a query
        $query = "UPDATE categories SET name = '$name' WHERE id = '$id'";

        // Execute the query
        $result = $db->query($query);
        if (!$result) {
            die("Could not query the database: <br />" . $db->error . "<br>Query: " . $query);
        } else {
            $db->close();
            header('Location: index.php');
        }
    }
}else{
    $query = "SELECT * FROM categories WHERE id = '$id'";

    // Execute the query
    $result = $db->query($query);
    if (!$result) {
        die("Could not query the database: <br />" . $db->error);
    } else {
        while ($row = $result->fetch_object()) {
            $name = $row->name;
        }
    } 
}
?>

<div class="row">
    <div class="col-md-auto col-sm-auto col-xs-auto">
        <a href="./" class="btn btn-secondary">
            <i class="bi bi-arrow-left"> </i>
            Back
        </a>
    </div>
</div>

<div class="row d-flex justify-content-center">
    <div class="col-md-12 my-3">
        <div class="card">
            <div class="card-header">Customers Data</div>
            <div class="card-body">
                <form action="" method="POST" class="needs-validation">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php if (isset($name)) echo $name; ?>">
                        <div class="error text-danger"><?php if (isset($error_name)) echo $error_name ?></div>
                    </div>
        
                    <button type="submit" class="btn btn-primary" name="submit">Update Data</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('../template/footer.php');?>