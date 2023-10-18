<?php 
require_once('../lib/session.php');

include('../template/header.php');
// Include our login information
require_once('../lib/db_login.php');

$query = "SELECT isbn AS ISBN, title AS Title, categories.name AS Category, author AS Author, price AS Price FROM books, categories WHERE books.categoryid = categories.id";

$kategori = query_to_array("SELECT * from categories");

if (isset($_POST["search"])) {  
    $valid = TRUE;
    $keyword = test_input($_POST['keyword']);
    $categoryid = test_input($_POST['categoryid']);
    $min = test_input($_POST['min']);
    $max = test_input($_POST['max']);

    if(!is_numeric($min)){
        $min = '';
    }
    if(!is_numeric($max)){
        $max = '';
    }

    if($keyword != ''){
        $query = $query . " AND (title LIKE '%$keyword%' OR author LIKE '%$keyword%' OR isbn LIKE '%$keyword%')";
    }
    if($categoryid != ''){
        $query = $query . " AND categoryid = $categoryid";
    }
    if($min != ''){
        $query = $query . " AND price >= $min";
    }
    if($max != ''){
        $query = $query . " AND price <= $max";
    }
}

$result = $db->query($query);

if (!$result) {
    die("Could not query the database: <br />" . $db->error . "<br>Query: " . $query);
}
?>

<div class="row d-flex justify-content-center">
    <div class="col-md-12 my-3">
        <div class="card">
            <div class="card-header">Books Data</div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <input type="text" class="form-control" placeholder="keywords" name="keyword" id="keyword" autocomplete="off" value="<?php if (isset($keyword)) echo $keyword; ?>">
                        </div>
                        <div class="col-lg-3 mb-3">
                            <select class="form-select" aria-label="Default select example" id="categoryid" name="categoryid">
                                <option selected value="">Select a category</option>
                                <?php foreach($kategori as $row):?>
                                    <option value="<?= $row['id']?>" <?php if (isset($categoryid) && $categoryid == $row['id']) echo 'selected' ?>><?= $row['name']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <input type="text" class="form-control" placeholder="price min" name="min" id="min" autocomplete="off" value="<?php if (isset($min)) echo $min; ?>">
                        </div>
                        -
                        <div class="col-lg-2 mb-3">
                            <input type="text" class="form-control" placeholder="price max" name="max" id="max" autocomplete="off" value="<?php if (isset($max)) echo $max; ?>">
                        </div>
                        <div class="col-lg-auto mb-3">
                            <button class="btn btn-primary" type="submit" id="tombolCari" name="search">Search</button>
                            <a href="./index.php">
                                <button class="btn btn-secondary" name="reset">Reset</button>
                            </a>
                        </div>
                    </div>
                </form>
        
                <!-- <a  class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#formModal">+ Add Customer Data</a> -->
                <a  class="btn btn-primary mb-4" href="add_book.php">+ Add Book Data</a>
                <br>
        
                <table class="table table-striped">
                    <tr>
                        <th>No</th>
                        <th>ISBN</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
        
                    <?php
                    // Fetch and display the results
                    $i = 1;
                    while ($row = $result->fetch_object()) {
                    ?>
                        <tr>
                            <td><?= $i?></td>
                            <td><?= $row->ISBN?></td>
                            <td><?= $row->Title?></td>
                            <td><?= $row->Category?></td>
                            <td><?= $row->Author?></td>
                            <td><?= $row->Price?></td>
                            <td>
                            <a class="btn btn-primary btn-sm" href="./detail_book.php?isbn=<?= $row->ISBN?>">Detail</a>
                            <a class="btn btn-warning btn-sm" href="edit_book.php?isbn=<?= $row->ISBN?>">Edit</a>&nbsp;
                            <a class="btn btn-danger btn-sm" href="./delete_book.php?isbn=<?= $row->ISBN?>" onclick="return confirm('Are you sure')">Delete</a>
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
    </div>
</div>

<?php $db->close();?>
<?php include('../template/footer.php') ?>
