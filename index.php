<?php
// INSERT INTO `notes` (`sno`, `title`, `description`, `tstamp`) VALUES (NULL, 'Buy Books', 'Please buy Books from Store.', current_timestamp());
$insert = false;
$update = false;
$delete = false;
$blankf = false;
// Connecting to the Database
$servername = "localhost";
$username = "root";
$password = "";
$database = "notes";
// Create a connection
$conn = mysqli_connect($servername,$username,$password);
//Die if connection was not successful
if(!$conn){
    die("Sorry we failed to conect:" . mysqli_connect_error());
}
else{
  // echo"Success Connecting to the db";
}

// SQL query to create the database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS `$database`";

// Execute the query to create the database
if (mysqli_query($conn, $sql)) {
  // echo "Database '$database' created successfully or already exists.<br>";
  // Select the database
  if (!mysqli_select_db($conn, $database)) {
      die("Error selecting database: " . mysqli_error($conn));
  }
}
else {
  echo "Error creating database: " . mysqli_error($conn);
}

// SQL query to create the 'notes' table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS `notes` (
  `sno` INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `tstamp` DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL )";

// Execute the query
if (mysqli_query($conn, $sql)) {
  // echo "Table 'threads' created successfully or already exists.<br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

if(isset($_GET['delete'])){
  $sno = $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `notes` WHERE `sno` = $sno";
  $result = mysqli_query($conn,$sql);
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if(isset($_POST['snoEdit'])){
    // Update the Record
    $sno = $_POST["snoEdit"];
    $title = $_POST["titleEdit"];
    $description = $_POST["descriptionEdit"];
    // Sql Query to be executed
    $sql = "UPDATE `notes` SET `title` = '$title' , `description` = '$description' WHERE `notes`.`sno` = $sno";
    $result = mysqli_query($conn,$sql);
    if($result){
      $update = true;
    }
    else{
        echo "<br>We could not update the record Successfully";
    }
  }
  else {
    if(empty($_POST["title"]) || empty($_POST["description"])) {
      $blankf = true;      
    }
    else{
      $title = $_POST["title"];
      $description = $_POST["description"];
      // Sql Query to be executed
      $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title','$description')";
      $result = mysqli_query($conn,$sql);
      // Add a new values to the database
      if($result){
          $insert = true;
      }
      else{
          echo "The record was not inserted Successfully because of this error ----> ".mysqli_error($conn);
      }
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="//cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css">
  <title>MyNotes App</title>
</head>

<body>
<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel">Edit this Note</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="/Php Projects/CRUD Project App/index.php" method="post">
        <div class="modal-body">
          <input type="hidden" name="snoEdit" id="snoEdit">
          <div class="mb-3">
            <label for="title" class="form-label">Note Title</label>
            <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp" required>
          </div>
    
          <div class="mb-3">
            <label for="desc" class="form-label">Note Description</label>
            <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3" required></textarea>
          </div>
        </div>
        <div class="modal-footer d-block mr-auto">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

  <nav class="navbar navbar-expand-lg bg-dark border-bottom border-body" data-bs-theme="dark" id="home">
    <div class="container-fluid">
      <a class="navbar-brand" href="#home"><img src="img.png" height="48px" width="48px" alt="logo"> MyNotes App</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#home">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact Us</a>
          </li>
        </ul>
        <!-- <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form> -->
      </div>
    </div>
  </nav>
  
  <?php
  if($insert){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your note has been inserted successfully.
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
  }
  if($delete){
    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
    <strong>Deleted!</strong> Your note has been deleted successfully.
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
  }
  if($update){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your note has been updated successfully.
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
  }
  if($blankf){
    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
    <strong>Please!</strong> You have to fill the note title and description.
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
  }
  ?>

  <div class="container my-4">
    <h2>Add a Note</h2>
    <form action="/Php Projects/CRUD Project App/index.php" method="post">
      <div class="mb-3">
        <label for="title" class="form-label">Note Title</label>
        <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp" required>
      </div>

      <div class="mb-3">
        <label for="desc" class="form-label">Note Description</label>
        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Add Note</button>
    </form>
  </div>

  <div class="container my-4">
    <table class="table" id="myTable">
      <thead>
        <tr>
          <th scope="col">S.No</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * FROM `notes`";
        $result = mysqli_query($conn,$sql);
        $sno = 0;
        while ($row = mysqli_fetch_assoc($result)) {
          $sno = $sno + 1;
          echo "<tr>
          <th scope='row'>". $sno ."</th>
          <td>". $row['title'] ."</td>
          <td>". $row['description'] ."</td>
          <td><button type='button' class='edit btn btn-sm btn-primary' id=".$row['sno']." data-bs-toggle='modal' data-bs-target='#editModal'>Edit</button> <button type='button' class='delete btn btn-sm btn-primary' id=d".$row['sno']." data-bs-toggle='modal' data-bs-target='#editModal'>Delete</button></td>
        </tr>";
        }     
        ?>
      </tbody>
    </table>
  </div>
  <hr>

  <footer>
        <div class="foot-panel1">
            <a href="#home" style="scroll-behavior: smooth;color: white;text-decoration: none;font-size: 16px;background-color: #181414;display: flex;
                  justify-content: center;align-items: center;padding: 10px;">Back to Top</a>
        </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/2.0.3/js/dataTables.min.js"></script>
  <script>
    let table = new DataTable('#myTable');
  </script>
  <script>
    edits = document.getElementsByClassName("edit");
    Array.from(edits).forEach((element)=>{
      element.addEventListener("click",(e)=>{
        console.log("edit");
        tr = e.target.parentNode.parentNode;
        title = tr.getElementsByTagName("td")[0].innerText;
        description = tr.getElementsByTagName("td")[1].innerText;
        console.log(title,description);
        titleEdit.value = title;
        descriptionEdit.value = description;
        snoEdit.value = e.target.id;
        console.log(e.target.id);
      })
    })

    deletes = document.getElementsByClassName("delete");
    Array.from(deletes).forEach((element)=>{
      element.addEventListener("click",(e)=>{
        console.log("Delete");
        tr = e.target.parentNode.parentNode;
        sno = e.target.id.substr(1,);
        
        if(confirm("Are you sure you want to delete this note!")){
          console.log("Yes");
          window.location = `/Php Projects/CRUD Project App/index.php?delete=${sno}`;
        }
        else{
          $delete = false;
          window.location = `/Php Projects/CRUD Project App/index.php`;
          console.log("No");
        }
      })
    })
  </script>
</body>

</html>
