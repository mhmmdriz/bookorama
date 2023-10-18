<?php
require_once('../lib/session.php');
require_once('../lib/db_login.php');
include('../template/header.php');
$kategori = query_to_array("SELECT * from categories");

if (isset($_POST["submit"])) {  
    $valid = TRUE;
    $isbn = test_input($_POST['isbn']);
    if ($isbn == '') {
        $error_isbn = "ISBN is required";
        $valid = FALSE;
    }

    $author = test_input($_POST['author']);
    if ($author == '') {
        $error_author = "Author is required";
        $valid = FALSE;
    }
    
    $title = test_input($_POST['title']);
    if ($title == '') {
        $error_title = "Title is required";
        $valid = FALSE;
    }

    $categoryid = $_POST['categoryid'];
    if ($categoryid == '' || $categoryid == 'none') {
        $error_categoryid = "Category is required";
        $valid = FALSE;
    }

    $price = test_input($_POST['price']);
    if ($price == '') {
        $error_price = "Price is required";
        $valid = FALSE;
    }else if(!is_numeric($price)){
        $error_price = "Price must be a number";
        $valid = FALSE;
    }

    if ($valid) {
        $query = "UPDATE books SET isbn = '$isbn', title = '$title', categoryid = '$categoryid', author = '$author', price = $price WHERE isbn = '$isbn'";
        
        // Execute the query
        $result = $db->query($query);
        if (!$result) {
            die("Could not query the database: <br />" . $db->error . "<br>Query: " . $query);
        } else {
            $db->close();
            header("Location:index.php");
        }
    }
}else{
    $isbn = $_GET['isbn'];
    
    $query = "SELECT * FROM books WHERE isbn = '$isbn'";

    // Execute the query
    $result = $db->query($query);
    if (!$result) {
        die("Could not query the database: <br />" . $db->error);
    } else {
        while ($row = $result->fetch_object()) {
            $title = $row->title;
            $author = $row->author;
            $categoryid = $row->categoryid;
            $price = $row->price;
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
            <div class="card-header">Book Data</div>
            <div class="card-body">
                <form action="" method="POST" class="needs-validation">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3">
                        <label for="isbn" class="form-label" aria-describedby="validationServer04Feedback">ISBN</label>
                        <input type="text" class="form-control" id="isbn" name="isbn" value="<?php if (isset($isbn)) echo $isbn; ?>">
                        <div class="error text-danger"><?php if (isset($error_isbn)) echo $error_isbn ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php if (isset($title)) echo $title; ?>">
                        <div class="error text-danger"><?php if (isset($error_title)) echo $error_title ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="categoryid" class="form-label">Category</label>
                        <select class="form-select" aria-label="Default select example" id="categoryid" name="categoryid">
                            <option selected value="">Select a category</option>
                            <?php foreach($kategori as $row):?>
                                <option value="<?= $row['id']?>" <?php if (isset($categoryid) && $categoryid == $row['id']) echo 'selected' ?>>
                                    <?= $row['name']?>
                                </option>
                            <?php endforeach;?>
                        </select>
                        <div class="error text-danger"><?php if (isset($error_categoryid)) echo $error_categoryid ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" class="form-control" id="author" name="author" value="<?php if (isset($author)) echo $author; ?>">
                        <div class="error text-danger"><?php if (isset($error_author)) echo $error_author ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" class="form-control" id="price" name="price" value="<?php if (isset($price)) echo $price; ?>">
                        <div class="error text-danger"><?php if (isset($error_price)) echo $error_price ?></div>
                    </div>
        
                    <button type="submit" class="btn btn-primary" name="submit">Update Data</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('../template/footer.php');?>