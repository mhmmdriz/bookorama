<?php
require_once('../lib/session.php');

include('../template/header.php');
// Include our login information
require_once('../lib/db_login.php');

$query1 = "SELECT categories.name AS Category, COUNT(*) as jumlah_buku
        FROM books, categories
        WHERE books.categoryid = categories.id
        GROUP BY categories.name
        ORDER BY categories.name;";

$result1 = query_to_array($query1);

$query2 = "SELECT categories.name AS Category, isbn AS ISBN, title AS Title, author AS Author, price AS Price
                FROM books, categories
                WHERE books.categoryid = categories.id
                ORDER BY categories.name";
        
$result2 = query_to_array($query2);
?>

<div class="card col-md-12 my-5">
    <div class="card-header">Categories</div>
    <div class="card-body">
        <table class="table table-striped" border="1">
            <tr>
                <th>Category</th>
                <th>ISBN</th>
                <th>Title</th>
                <th>Author</th>
                <th>Price</th>
            </tr>

            <?php
                $quantity_count = 0;
                $i = 0;
                foreach($result2 as $row2){
                    echo "<tr>";
                    if($quantity_count == 0){
                        echo "<td rowspan=". $result1[$i]['jumlah_buku'] .">".$row2['Category']."</td>";

                    }
                    if($quantity_count == $result1[$i]['jumlah_buku'] - 1){
                        $quantity_count = -1;
                        $i++;
                    }
                    echo "<td>".$row2['ISBN']."</td>";
                    echo "<td>".$row2['Title']."</td>";
                    echo "<td>".$row2['Author']."</td>";
                    echo "<td>".$row2['Price']."</td>";
                    echo "</tr>";

                    $quantity_count++;
                }
            ?>
            </tr>
            <?php
            
        echo '</table>';
        echo '<br />';

        ?>
    </div>
</div>

<?php include('../template/footer.php');?>