<?php
require_once('../lib/session.php');
include('../template/header.php');
require_once('../lib/db_login.php');

$books = query_to_array("SELECT * from books");
$customers = query_to_array("SELECT * from customers");

if (isset($_POST["submit"])) {  
    $valid = TRUE;
    if(!isset($_POST['buku'])){
        $error_books_input = "book is required";
        $valid = FALSE;
    }else{
        $bukuTerpilih = [];
        $jumlahBuku = [];

        // Loop melalui data POST untuk mengumpulkan buku yang dipilih dan jumlahnya
        foreach ($_POST as $key => $value) {
            if (strpos($key, "jumlah_") === 0) {
                // Jika nama input dimulai dengan "jumlah_", itu adalah input jumlah
                // Ambil nama buku yang sesuai
                $namaBuku = substr($key, 7);

                // Tambahkan buku dan jumlahnya ke dalam array
                $bukuTerpilih[] = $namaBuku;
                $jumlahBuku[] = $value;
            }
        }

        $cek = false;

        foreach ($jumlahBuku as $jumlah) {
            if ($jumlah <= 0) {
                $cek = true;
                break; // Keluar dari loop jika nilai negatif ditemukan
            }
        }

        if ($cek) {
            $error_books_input = "Number of Books is Invalid";
            $valid = FALSE;
        }
    }
    
    // Validasi terhadap field title
    $date = test_input($_POST['date']);
    if ($date == '') {
        $error_date = "date is required";
        $valid = FALSE;
    }

    $customerid = $_POST['customerid'];
    if ($customerid == '') {
        $error_customerid = "Customer is required";
        $valid = FALSE;
    }

    if ($valid) {
        $amount = 0;
        $i = 0;
        foreach($bukuTerpilih as $book_isbn){
            $book_price = query_to_array("SELECT price from books WHERE isbn = '$book_isbn'");
            $amount += $book_price[0]['price'] * $jumlahBuku[$i];
            $i++;
        }

        $query1 = "INSERT INTO orders VALUES(0, '$customerid', '$amount', '$date')";
        $select = "SELECT orderid FROM orders
                    ORDER BY orderid DESC
                    LIMIT 1";
        
        // // Execute the query
        $result1 = $db->query($query1);
        $resultOrders = query_to_array($select);

        $newOrderId = $resultOrders[0]['orderid'];

        $i = 0;
        foreach($bukuTerpilih as $book_isbn){
            $query2 = "INSERT INTO order_items VALUES('$newOrderId' ,'$book_isbn', $jumlahBuku[$i])";
            $result2 = $db->query($query2);
            $i++;
        }


        if (!$result1 || !$result2) {
            echo("Could not query the database: <br />" . $db->error . "<br>Query: " . $query1);
            die("Could not query the database: <br />" . $db->error . "<br>Query: " . $query1);
        } else {
            $db->close();
            header('Location: index.php');
        }
    }
}
?>

<script>
    function tambahInput() {
        var select = document.getElementById("buku");
        var selectedOption = select.options[select.selectedIndex].value;
        var selectedOptionName = select.options[select.selectedIndex].text;

        var container = document.getElementById("jumlah-container");

        // Cek apakah input teks untuk buku ini sudah ada
        if (!document.querySelector(`input[name='jumlah_${selectedOption}']`)) {
            // Jika belum ada, tambahkan label dan input teks baru
            var inputContainer = document.createElement("div");
            inputContainer.className = "input-container";

            var label = document.createElement("label");
            label.textContent = `Number of ${selectedOptionName}:`;

            var br = document.createElement("br"); // Tambahkan baris baru di antara label dan input

            var input = document.createElement("input");
            input.type = "number";
            input.name = `jumlah_${selectedOption}`;
            input.className = "form-control mt-2";
            input.style.width = "100px";
            input.required = true;

            // Tambahkan tombol "Hapus"
            var removeButton = document.createElement("button");
            removeButton.innerHTML = "Hapus";
            removeButton.className = "btn btn-danger btn-sm";
            removeButton.onclick = function () {
                container.removeChild(inputContainer);
            };

            inputContainer.appendChild(label);
            inputContainer.appendChild(br);
            inputContainer.appendChild(input);
            inputContainer.appendChild(removeButton);

            container.appendChild(inputContainer);
        }
    }
</script>



<link rel="stylesheet" type="text/css" href="../css/style.css">

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
                <form action="" method="POST" class="needs-validation" name="formOrder">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3">
                        <label for="buku">Select Book:</label>
                        <select class="form-select" name="buku" id="buku" onchange="tambahInput()">
                            <option value="" selected disabled></option>
                            <?php foreach($books as $row):?>
                                <option value="<?= $row['isbn']?>" <?php if (isset($books_input) && in_array($row['isbn'], $books_input)) echo 'selected' ?>>
                                    <?= $row['title']?>
                                </option>
                            <?php endforeach;?>
                        </select>
        
                        <div id="jumlah-container">
                            
                        </div>
                        <div class="error text-danger"><?php if (isset($error_books_input)) echo $error_books_input ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="<?php if (isset($date)) echo $date; ?>" style="width:150px">
                        <div class="error text-danger"><?php if (isset($error_date)) echo $error_date ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="customerid" class="form-label">Customer</label>
                        <select class="js-example-basic-single" name="customerid" style="width: 100%; height:50px">
                            <option selected value="">Select a customer</option>
                            <?php foreach($customers as $row):?>
                                <option value="<?= $row['customerid']?>" <?php if (isset($customerid) && $customerid == $row['customerid']) echo 'selected' ?>>
                                    <?= $row['name']?>
                                </option>
                            <?php endforeach;?>
                        </select>
                        <div class="error text-danger"><?php if (isset($error_customerid)) echo $error_customerid ?></div>
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Tambah Data</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('../template/footer.php');?>