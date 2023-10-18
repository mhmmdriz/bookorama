<?php 
require_once('../lib/session.php');
require_once('../lib/db_login.php');
include('../template/header.php');

$isbn = $_GET['isbn'];

$query = "SELECT isbn AS ISBN, title AS Title, categories.name AS Category, author AS Author, price AS Price FROM books, categories WHERE books.categoryid = categories.id AND isbn = '$isbn'";
$result = $db->query($query);
$row = $result->fetch_object();

$query2 = "SELECT review from book_reviews WHERE isbn = '$isbn'";
$result2 = $db->query($query2);
$row2 = $result2->fetch_object();

if ($result2->num_rows == 0) {
    $is_reviewed = false;
    $review = "The review hasn't been added <br>";
}else{
    $is_reviewed = true;
    $review = $row2->review;
    if($review == ''){
        $is_reviewed = false;
        $review = "The review hasn't been added <br>";
    }
}

if (isset($_POST["submit"])) {  
    $review_new = $_POST['review'];
    $review_new = $db->real_escape_string($review_new);

    if (!$is_reviewed && $result2->num_rows == 0) {
        // Assign a query
        $query3 = "INSERT INTO book_reviews VALUES('$isbn','$review_new')";
        
        // Execute the query
        $result3 = $db->query($query3);
        if (!$result3) {
            die("Could not query the database: <br />" . $db->error . "<br>Query: " . $query3);
        } else {
            $db->close();
            header("Location: ". $_SERVER['PHP_SELF']."?isbn=".$isbn);
        }
    }else{
        // Assign a query
        $query3 = "UPDATE book_reviews SET review = '$review_new' WHERE isbn = '$isbn'";
        
        // Execute the query
        $result3 = $db->query($query3);
        if (!$result3) {
            die("Could not query the database: <br />" . $db->error . "<br>Query: " . $query3);
        } else {
            $db->close();
            header("Location: ". $_SERVER['PHP_SELF']."?isbn=".$isbn);
        }

    }
}

?>

<div class="row d-flex justify-content-center">
    <div class="col-md-6 col-sm-6 col-xs-6">
        <a href="./" class="btn btn-secondary">
            <i class="bi bi-arrow-left"> </i>
            Back
        </a>
    </div>
</div>

<div class="row d-flex justify-content-center">
    <div class="col-md-6 my-3">
        <div class="card">
            <div class="card-header">Book Details</div>
            <div class="card-body">
                <h3><u><?= $row->Title?></u></h3>
                <br>
                <h5>ISBN</h5>
                <p><?= $row->ISBN?></p>
                <h5>Category</h5>
                <p><?= $row->Category?></p>
                <h5>Author</h5>
                <p><?= $row->Author?></p>
                <h5>Price</h5>
                <p><?= $row->Price?></p>
                <h5>Review</h5>
                <?= $review?>
                <br>
        
                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal">
                    <?php
                        if(!$is_reviewed){
                            echo "Add Review";
                        }else{
                            echo "Edit Review";
                        }
                    ?>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="judulModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="judulModal">Review</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="" method="post" class="formReview" name="formReview">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3">
                        <input id="review" type="hidden" name="review" value="<?= ($is_reviewed) ? $review : '' ?>" required>
                        <trix-editor input="review" required></trix-editor>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('../template/footer.php') ?>
