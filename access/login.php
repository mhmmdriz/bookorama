<?php
session_start();
if(isset($_SESSION['email'])){
    header('Location: ../dashboard/index.php');
}

require_once('../lib/db_login.php');

// Memeriksa apakah user sudah submit form
if (isset($_POST['submit'])) {
    $valid = TRUE;

    // Memeriksa validasi email
    $email = test_input($_POST['email']);
    if ($email == '') {
        $error_email = 'Email is required';
        $valid = FALSE;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_email = 'Invalid email format';
        $valid = FALSE;
    }

    // Memeriksa validasi password
    $password = test_input($_POST['password']);
    if ($password == '') {
        $error_password = 'Password is required';
        $valid = FALSE;
    }

    // Memeriksa validasi
    if ($valid) {
        // Assign a query
        $query = "SELECT * FROM admin WHERE email = '" . $email . "' AND password = '" . md5($password) . "'";

        // Execute query
        $result = $db->query($query);
        if (!$result) {
            die("Could not query the database: <br />" . $db->error);
        } else {
            if ($result->num_rows > 0) {
                $_SESSION['email'] = $email;
                header('Location: ../dashboard');
                exit;
            } else {
                $error_data = '<span class "error">Combination of email and password are not correct.</span>';
            }
        }

        $db->close();
    }
}
?>
<?php include('../template/header.php') ?>
<div class="row d-flex justify-content-center">
    <div class="col-md-5 my-3">
        <div class="card">
            <div class="card-header">Login Form</div>
            <div class="card-body">
                <form method="POST" autocomplete="on" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                    <div class="form-group">
                        <div class="error text-danger"><?php if (isset($error_data)) echo $error_data.'<br>'?></div>
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php if (isset($email)) echo $email; ?>">
                        <div class="error text-danger"><?php if (isset($error_email)) echo $error_email ?></div>
                    </div>
                    <div class="form-group mt-2">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" value="">
                        <div class="error text-danger"><?php if (isset($error_password)) echo $error_password ?></div>
                    </div>
                    <br>
                    
                    <button type="submit" class="btn btn-primary" name="submit" value="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('../template/footer.php') ?>