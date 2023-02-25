<?php 
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    include 'partials/_dbconnect.php';
    $sno = $_POST["snoEdit"];
    $days = $_POST["numDays"];
    $rent = $_POST["totalRent"];
    $email = $_SESSION['email'];
    $sql = "INSERT INTO `bookings` (`cid`, `days`, `price`, `bookedby`) VALUES ('$sno', '$days', '$rent', '$email')";
    $result = mysqli_query($conn, $sql);
    
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

<!-- Book Modal -->
<div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="bookModalLabel">Book Car</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="/carRental/index.php" method="POST">
          <input type="hidden" name="snoEdit" id="snoEdit"> 
          <div class="mb-3">
              <label for="vm" class="form-label">Vehicle model</label>
              <input class="btn btn-sm" name="vehmodelEdit" id="vehmodelEdit" disabled>
            </div>
            <div class="mb-3">
                <label for="vn" class="form-label">Vehicle number</label>
                <input class="btn btn-sm" name="vehnumberEdit" id="vehnumberEdit" disabled>
            </div>
            <div class="mb-3">
                <label for="sc" class="form-label">Seating capacity</label>
                <input class="btn btn-sm" name="seatingEdit" id="seatingEdit" disabled>
            </div>
            <div class="mb-3">
                <label for="rpd" class="form-label">Rent per day</label>
                <input class="btn btn-sm" name="rentEdit" id="rentEdit">
            </div>
            <select class="form-select mb-4" aria-label="Default select example" id="numDays" name="numDays">
                <option selected>Select number of days</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <div class="mb-3">
                <label for="totalRent" class="form-label">Total Rent</label>
                <input class="btn btn-sm" name="totalRent" id="totalRent">
            </div>
        <button type="submit" class="btn btn-primary">Rent Car</button>
        </form>

        <script>
            const rentEdit = document.getElementById('rentEdit');
            const numDaysSelect = document.getElementById('numDays');
            const totalRentInput = document.getElementById('totalRent');

            // Calculate total rent when number of days is selected
            numDaysSelect.addEventListener('change', () => {
                const rentPerDay = parseFloat(rentEdit.value);
                const numDays = parseInt(numDaysSelect.value);
                const totalRent = rentPerDay * numDays;
                totalRentInput.value = totalRent.toFixed(2);
            });
        </script>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php require 'partials/_nav.php' ?>

<div class="container">

    <table class="table">
    <thead>
        <tr>
        <th scope="col">S.No</th>
        <th scope="col">Vehicle model</th>
        <th scope="col">Vehicle number</th>
        <th scope="col">Seating capacity</th> 
        <th scope="col">Rent</th> 

        <?php
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
            ?>
            <th scope="col">Book</th>
            <?php
        }
        
        ?>
    </tr>
</thead>
<tbody>
    <?php
        include 'partials/_dbconnect.php';
        $sql = "SELECT * from `cars`";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            $cid = $row['cid'];
            $booked = false;

            $booking_sql = "SELECT * FROM `bookings` WHERE `cid` = '$cid'";
            $booking_result = mysqli_query($conn, $booking_sql);

            if(mysqli_num_rows($booking_result) > 0){
                $booked = true;
            }
            
            echo "<tr>
            <th scope='row'>".$row['cid']."</th>
            <td>".$row['vehmodel']."</td>
            <td>".$row['vehnumber']."</td>
            <td>".$row['seating']."</td>
            <td>".$row['rent']."</td>";

            if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
                if($booked){
                    echo "<td>Booked</td>";
                } else {
                    echo "<td><button class='book btn btn-sm btn-primary' id=".$row['cid']." data-bs-toggle='modal' data-bs-target='#bookModal'>Book</button></td>";
                }
            }

           
            echo "</tr>";
        }


    ?>
    </tbody>
    </table>
</div>
    


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <script>
        books = document.getElementsByClassName('book');
        Array.from(books).forEach((element) => {
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
        
    </script> 
  </body>
</html>