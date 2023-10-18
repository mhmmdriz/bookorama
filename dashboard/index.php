<?php
require_once('../lib/session.php');

include('../template/header.php');
require_once('../lib/db_login.php');

$query1 = "SELECT categories.name, COUNT(*) as jumlah_buku
        FROM books, categories
        WHERE books.categoryid = categories.id
        GROUP BY categoryid;";

$result1 = query_to_array($query1);
$resultJSON1 = json_encode($result1);

$query2 = "SELECT categories.name, SUM(quantity) as jumlah_buku
FROM orders, order_items, categories, books
WHERE orders.orderid = order_items.orderid
AND order_items.isbn = books.isbn
AND books.categoryid = categories.id
GROUP BY categories.name";

$result2 = query_to_array($query2);
$resultJSON2 = json_encode($result2);

?>

<div class="card col-md-12 my-5">
    <div class="card-header">Dashboard</div>
    <div class="card-body row">
        <div class="col-md-6">
            <div class="row justify-content-center">
                <div class="col-md-auto">
                <h4>number of books per category</h4>
                

                </div>
            </div>
            <div class="row">
                <div class="col">
                    <canvas id="doughnut" width="300" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row justify-content-center">
                <div class="col-md-auto">
                <h4>number of orders per category</h4>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <canvas id="doughnut2" width="300" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    Chart.defaults.color = '#fff';
    var booksPerCategory = <?php echo $resultJSON1; ?>;
    var ctx = document.getElementById('doughnut').getContext('2d');

    // Siapkan data untuk pie chart
    var labels = [];
    var data = [];

    for (var i = 0; i < booksPerCategory.length; i++) {
        labels.push(booksPerCategory[i].name);
        data.push(booksPerCategory[i].jumlah_buku);
    }

    var doughnutData = {
        labels: labels,
        datasets: [{
            data: data,
            backgroundColor: [
                'rgba(255, 205, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(255, 99, 132, 0.7)',
                'rgba(255, 159, 64, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(153, 102, 255, 0.7)',
                'rgba(201, 203, 207, 0.7)'
                // Tambahkan warna lain sesuai kebutuhan
            ]
        }]
    };
    
    // Buat doughnut
    var doughnut = new Chart(ctx, {
        type: 'doughnut',
        data: doughnutData,
        options: {
            maintainAspectRatio: false, // Mengizinkan perubahan aspek ratio
            responsive: true // Mengizinkan responsif terhadap ukuran layar
        }
    });


    var ordersPerCategory = <?php echo $resultJSON2; ?>;
    
    // Ambil elemen canvas yang akan digunakan untuk menampilkan pie chart
    var ctx2 = document.getElementById('doughnut2').getContext('2d');

    var labels2 = [];
    var data2 = [];

    
    for (var i = 0; i < ordersPerCategory.length; i++) {
        labels2.push(ordersPerCategory[i].name);
        data2.push(ordersPerCategory[i].jumlah_buku);
    }

    
    var doughnutData2 = {
        labels: labels2,
        datasets: [{
            data: data2,
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)',
                'rgba(255, 159, 64, 0.7)',
                'rgba(255, 205, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(153, 102, 255, 0.7)',
                'rgba(201, 203, 207, 0.7)'
                // Tambahkan warna lain sesuai kebutuhan
            ]
        }]
    };

    var doughnut2 = new Chart(ctx2, {
        type: 'doughnut',
        data: doughnutData2,
        options: {
            maintainAspectRatio: false, // Mengizinkan perubahan aspek ratio
            responsive: true // Mengizinkan responsif terhadap ukuran layar
        }
    });
</script>

<?php include('../template/footer.php');?>