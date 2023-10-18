<?php 
require_once('../lib/session.php');

include('../template/header.php');
require_once('../lib/db_login.php');

if (isset($_POST["submit"])) {  
    $valid = TRUE;
    $name = test_input($_POST['name']);
    if ($name == '') {
        $error_name = "Name is required";
        $valid = FALSE;
    }

    $city = test_input($_POST['city']);
    if ($city == '') {
        $error_city = "City is required";
        $valid = FALSE;
    }
    
    $address = test_input($_POST['address']);
    if ($address == '') {
        $error_address = "Address is required";
        $valid = FALSE;
    }

    if ($valid) {
        // Assign a query
        $query = "INSERT INTO customers VALUES(0, '$name', '$address', '$city')";
        
        // Execute the query
        $result = $db->query($query);
        if (!$result) {
            die("Could not query the database: <br />" . $db->error . "<br>Query: " . $query);
        } else {
            $db->close();
            header('Location: index.php');
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
                <form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST" class="needs-validation">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php if (isset($name)) echo $name; ?>">
                        <div class="error text-danger"><?php if (isset($error_name)) echo $error_name ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php if (isset($address)) echo $address; ?>">
                        <div class="error text-danger"><?php if (isset($error_address)) echo $error_address ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city" value="<?php if (isset($city)) echo $city; ?>">
                        <div class="error text-danger"><?php if (isset($error_city)) echo $error_city ?></div>
                    </div>
        
                    <button type="submit" class="btn btn-primary" name="submit">Tambah Data</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('../template/footer.php');?>