<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" type="text/css" href="../styles/admin.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="./styles/sidebar.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - professors</title>
</head>

<body>

    <!--sidebar on top of everything using bootstrap and grid-->
    <div class="row">
        <div class="col-2">
            <div class="sidebar">
                <!--button to hide sidebar-->
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">β</a>
                <a href="./adminPanel.php">Inici</a>
                <a href="./adminCourses.php">Cursos π«</a>
                <a href="./adminTeachers.php">Professors π¨βπ</a>
                <a href="../logout.php">Tancar sessiΓ³ β</a>
            </div>
            <!--button that calls openNav()-->
            <button class="openbtn" onclick="openNav()">β°</button>

        </div>
        <div class="col-10">

        </div>
    </div>



  <?php
  // Initialize the session
  session_start();

  // Check if the user is logged in, if not then redirect him to login page
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin") {
    header("location: adminLogin.php");
    exit;
  }
  ?>
  <h1>Editar profesor</h1>

  <div style="display: flex; justify-content: center; margin-bottom: 20px; align-items:center;">

    <?php
    require_once "../config.php";

    $param_upload_image = $upload_image = $location = "";

    $con = mysqli_connect("localhost", "root", "");

    if (!$con) {

      die('Could not connect.');
    }



    mysqli_select_db($con, "courses");


    if (isset($_GET['dni'])) {

      //select from teacher where dni coincides with the one in the url

      $query = 'SELECT * FROM teacher WHERE dni = "' . $_GET['dni'] . '" LIMIT 1';

      $result = mysqli_query($con, $query);

      if ($result == null) {

        echo "No s'ha trobat el professor amb el DNI: " . $_GET['dni'];

        exit;
      } else {

        $teacher = mysqli_fetch_assoc($result);

        //create a form with inputs stacked one on top of another

        //each input should have the value of the teacher's data

    ?>
        <form style='display: flex; flex-direction: column; align-items: center; justify-content: center;' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?dni=' . $teacher['dni'] ?>' method='post' enctype="multipart/form-data">
          <?php
          echo "<input type='hidden' name='dni' value='" . $teacher['dni'] . "' />";

          echo "<label for='name'>Nombre</label>";

          echo "<input type='text' name='name' value='" . $teacher['name'] . "' />";

          echo "<label for='surname'>Apellidos</label>";

          echo "<input type='text' name='surname' value='" . $teacher['surname'] . "' />";

          echo "<label for='title'>TΓ­tulo</label>";

          echo "<input type='text' name='title' value='" . $teacher['title'] . "' />";

          echo "<label for='description'>DescripciΓ³n</label>";

          echo "<input type='text' name='description' value='" . $teacher['description'] . "' />";

          echo "<label for='image'>Imagen</label>";

          //create a file input and display stored image on '../profilepics/$teacher['image']'

          ?><input type="file" name="uploadfile" value="<?php echo $teacher['image'] ?>" />
          <?php
          echo "<img style='width:60px;height:60px;' src='../profilepics/" . $teacher['image'] . "' />";


          echo '<input type="submit" name="submitbtn" class="btn btn-primary" value="Editar">';

          echo "</form>";
          ?>
  </div>
<?php
      }
    } else {

      echo "No s'ha trobat el professor amb el DNI: " . $_GET['dni'];
    }
?>
<div style="display: flex; justify-content: center; margin-bottom: 20px; align-items:center;">
  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //update teacher with the data from the form


    //check if uploadfile is set


      //if it is, upload the file to the server and update the teacher's image

      $filename = $_FILES["uploadfile"]["name"];

      //check if filename is empty

      if ($filename != "") {



        $tempname = $_FILES["uploadfile"]["tmp_name"];

        $folder = "../profilepics/" . time() . $filename;

        move_uploaded_file($tempname, $folder);

        $query = 'UPDATE teacher SET name = "' . $_POST['name'] . '", surname = "' . $_POST['surname'] . '", title = "' . $_POST['title'] . '", description = "' . $_POST['description'] . '", image = "' . time() . $filename . '" WHERE dni = "' . $_POST['dni'] . '"';

        $result = mysqli_query($con, $query);

        if ($result == null) {

          echo "No se ha podido editar el profesor con el DNI: " . $_POST['dni'];

          exit;
        } else {

          echo "Se ha editado el profesor con el DNI: " . $_POST['dni'];
        }
      }
     else {

      //if it isn't, update the teacher's data without changing the image

      $query = 'UPDATE teacher SET name = "' . $_POST['name'] . '", surname = "' . $_POST['surname'] . '", title = "' . $_POST['title'] . '", description = "' . $_POST['description'] . '" WHERE dni = "' . $_POST['dni'] . '"';

      $result = mysqli_query($con, $query);

      if ($result == null) {

        echo "No se ha podido editar el profesor con el DNI: " . $_POST['dni'];

        exit;
      } else {

        echo "Se ha editado el profesor con el DNI: " . $_POST['dni'];
      }
    }
    header("Refresh:2");
  }



  mysqli_close($con);

  ?>
</div>
<a href="adminTeachers.php" class="btn btn-primary"><- Volver</a>

<script>
        closeNav();

        function openNav() {
            document.getElementsByClassName("sidebar")[0].style.width = "250px";
        }

        function closeNav() {
            document.getElementsByClassName("sidebar")[0].style.width = "0";
        }
    </script>
</body>

</html>