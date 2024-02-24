<?php
@include 'conn.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve faculty details from the form
    $faculty_id = $_POST['faculty_id'];
    $faculty_name = $_POST['faculty_name'];
    $faculty_email = $_POST['faculty_email'];

    // Check if faculty already exists with the given email
    $checkFacultyQuery = "SELECT COUNT(*) AS count FROM faculty WHERE faculty_email = '$faculty_email'";
    $checkFacultyResult = mysqli_query($conn, $checkFacultyQuery);
    $row = mysqli_fetch_assoc($checkFacultyResult);
    $count = $row['count'];

    // If faculty already exists, display an error message and skip insertion
    if ($count > 0) {
        echo '<script>alert("Faculty already exists. Skipping insertion.")</script>';
    } else {
        // Insert faculty data into the faculty table
        $insertFacultyQuery = "INSERT INTO faculty (faculty_id, faculty_name, faculty_email) VALUES ('$faculty_id', '$faculty_name', '$faculty_email')";
        mysqli_query($conn, $insertFacultyQuery);

        echo '<script>alert("Faculty inserted successfully.")</script>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <title>Add Faculty</title>
    
<style>
        body{
            background:white
        }
        .center
        {
            align-content: center;
            align-items: center;
            justify-content: center;
            display:flex;
            width: 100%;
            height: 100%;
            margin-top: 15%;
            background-color: blanchedalmond;
            width: 50%;
            border: 15px solid black;
            padding: 100px;
           margin: auto;
        }
        h2{
            text-decoration: solid;
            align-items: center;
            justify-content: center;
            display: flex;
            margin-top: 40px;
            margin-bottom: 100px;

           
        }
        body {font-family: Arial, Helvetica, sans-serif;}
        * {box-sizing: border-box;}

/* Full-width input fields */
input[type=text], input[type=submit],input[type=email] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

 /*label {
    width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
} */

/* Add a background color when the inputs get focus */
input[type=text]:focus, input[type=email]:focus ,input[type=submit]:focus{
  background-color: #ddd;
  outline: none;
}

/* Set a style for all buttons */
button {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

button:hover {
  opacity:1;
}

h3{
    justify-content: center;
    display: flex;

}
</style>
</head>
<body>
    <div class="center">
        <form method="POST" action="">
            <h3><b>Faculty Details</b></h3>
            <div id="marks-container">
                <div id="marks-template">
                    <label for="faculty_id">Faculty ID:</label>
                    <input type="text" name="faculty_id" id="faculty_id" required><br>

                    <label for="faculty_name">Faculty Name:</label>
                    <input type="text" name="faculty_name" id="faculty_name" required><br>

                    <label for="faculty_email">Email:</label>
                    <input type="email" name="faculty_email" id="faculty_email" required><br>
                </div>
            </div>

            <input type="submit" value="Submit">
            <a href="admindashboard.php"><button type="button">Back</button></a>
        </form>
    </div>
</body>
</html>