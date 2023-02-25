<?php

session_start();

if(!isset($_SESSION['adminloggedin']) || $_SESSION['adminloggedin'] != true){
    header("location: login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>

<?php require 'partials/_nav.php' ?>

<div class="container">
<h1>View booked cars</h1>
<table class="table">
<thead>
    <tr>
    <th scope="col">Vehicle ID</th>
    <th scope="col">Days</th>
    <th scope="col">Total Price</th> 
    <th scope="col">Booked By</th> 
</tr>
</thead>
<tbody>
<?php
    include '../partials/_dbconnect.php';
    $sql = "SELECT * from `bookings`";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)){
        echo "<tr>
        <th scope='row'>".$row['cid']."</th>
        <td>".$row['days']."</td>
        <td>".$row['price']."</td>
        <td>".$row['bookedby']."</td>
        </tr>";
    }


?>
</tbody>
</table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

</body>
</html>