<?php

session_start();

if(!isset($_SESSION['adminloggedin']) || $_SESSION['adminloggedin'] != true){
    header("location: login.php");
    exit;
}

$showAlert = false;

if(isset($_GET['delete'])){
    include '../partials/_dbconnect.php';
    $sno = $_GET['delete'];
    $sql = "DELETE FROM `cars` WHERE `cars`.`cid` = $sno";
    $result = mysqli_query($conn, $sql);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    include '../partials/_dbconnect.php';
    if(isset($_POST['snoEdit'])){
        $sno = $_POST["snoEdit"];
        $vehmodel = $_POST["vehmodelEdit"];
        $vehnumber = $_POST["vehnumberEdit"];
        $seating = $_POST["seatingEdit"];
        $rent = $_POST["rentEdit"];
    
        $sql = "UPDATE `cars` SET `vehmodel` = '$vehmodel', `vehnumber` = '$vehnumber', `seating` = '$seating', `rent` = '$rent' WHERE `cars`.`cid` = $sno";
        $result = mysqli_query($conn, $sql);
        
    }
    else{
        $vehmodel = $_POST["vehmodel"];
        $vehnumber = $_POST["vehnumber"];
        $seating = $_POST["seating"];
        $rent = $_POST["rent"];
    
        $sql = "INSERT INTO `cars` (`vehmodel`, `vehnumber`, `seating`, `rent`) VALUES ('$vehmodel', '$vehnumber', '$seating', '$rent')";
        $result = mysqli_query($conn, $sql);
        if($result){
            $showAlert = true;
        }
    }
    
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  </head>
  <body>

  <!-- Edit modal -->
<!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
  Edit Modal
</button> -->

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel">Edit Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="/carRental/admin/addCar.php" method="POST">
        <input type="hidden" name="snoEdit" id="snoEdit"> 
        <div class="mb-3">
            <label for="vm" class="form-label">Vehicle model</label>
            <input type="text" name="vehmodelEdit" class="form-control" id="vehmodelEdit" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
            <label for="vn" class="form-label">Vehicle number</label>
            <input type="text" name="vehnumberEdit" class="form-control" id="vehnumberEdit" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
            <label for="sc" class="form-label">Seating capacity</label>
            <input type="text" name="seatingEdit" class="form-control" id="seatingEdit" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
            <label for="rpd" class="form-label">Rent per day</label>
            <input type="text" name="rentEdit" class="form-control" id="rentEdit" aria-describedby="emailHelp">
        </div>
        <button type="submit" class="btn btn-primary">Update now</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php require 'partials/_nav.php' ?>

<?php
if($showAlert){
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success!</strong> Car is added.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

?>

<div class="container my-4">
    <h1 class="text-center">Add new car</h1>
    <form action="/carRental/admin/addCar.php" method="POST">
    <div class="mb-3">
        <label for="vm" class="form-label">Vehicle model</label>
        <input type="text" name="vehmodel" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
        <label for="vn" class="form-label">Vehicle number</label>
        <input type="text" name="vehnumber" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
        <label for="sc" class="form-label">Seating capacity</label>
        <input type="text" name="seating" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
        <label for="rpd" class="form-label">Rent per day</label>
        <input type="text" name="rent" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    </div>
    <button type="submit" class="btn btn-primary">Add a new car</button>
    </form>
</div>

<div class="container">

    <table class="table">
    <thead>
        <tr>
        <th scope="col">S.No</th>
        <th scope="col">Vehicle model</th>
        <th scope="col">Vehicle number</th>
        <th scope="col">Seating capacity</th> 
        <th scope="col">Rent</th> 
        <th scope="col">Actions</th> 
    </tr>
</thead>
<tbody>
    <?php
        include '../partials/_dbconnect.php';
        $sql = "SELECT * from `cars`";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            echo "<tr>
            <th scope='row'>".$row['cid']."</th>
            <td>".$row['vehmodel']."</td>
            <td>".$row['vehnumber']."</td>
            <td>".$row['seating']."</td>
            <td>".$row['rent']."</td>
            <td> <button class='edit btn btn-sm btn-primary' id=".$row['cid']." data-bs-toggle='modal' data-bs-target='#editModal'>Edit</button> <button class='delete btn btn-sm btn-danger' id=d".$row['cid'].">Delete</button></td>
           
            </tr>";
        }


    ?>
    </tbody>
    </table>
</div>
    


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script>
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log(e.target.parentNode.parentNode);
                tr = e.target.parentNode.parentNode;
                vehmodel = tr.getElementsByTagName("td")[0].innerText;
                vehnumber = tr.getElementsByTagName("td")[1].innerText;
                seating = tr.getElementsByTagName("td")[2].innerText;
                rent = tr.getElementsByTagName("td")[3].innerText;
                
                vehmodelEdit.value = vehmodel;
                vehnumberEdit.value = vehnumber;
                seatingEdit.value = seating;
                rentEdit.value = rent;
                console.log(e.target.id);
                snoEdit.value = e.target.id
            })
        })
        
        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log(e.target.parentNode.parentNode);
                sno = e.target.id.substr(1,);
                
                if(confirm("Delete this item!")){
                    console.log("yes");
                    window.location = `/carRental/admin/addCar.php?delete=${sno}`
                }
                else{
                    console.log("no");
                }
            })
        })
    </script>  
</body>
</html>