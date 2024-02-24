<?php
@include 'conn.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $subject_id = $_POST['subject_id'];
    $semester = $_POST['semester'];
    $subject_name = $_POST['subject_name'];
    $faculty_id = $_POST['faculty_id'];

    // Insert or update subject data in the subjects table
    $insertSubjectQuery = "INSERT INTO subjects (subject_id, semester, subject_name, faculty_id) 
                           VALUES ('$subject_id', '$semester', '$subject_name', '$faculty_id')
                           ON DUPLICATE KEY UPDATE semester='$semester', subject_name='$subject_name', faculty_id='$faculty_id'";
    mysqli_query($conn, $insertSubjectQuery);

    echo '<script>alert("Subject assigned successfully.")</script>';
}

// Retrieve faculty data for the dropdown menu
$getFacultyQuery = "SELECT faculty_id, faculty_name FROM faculty";
$getFacultyResult = mysqli_query($conn, $getFacultyQuery);
$facultyOptions = "";
while ($row = mysqli_fetch_assoc($getFacultyResult)) {
    $faculty_id = $row['faculty_id'];
    $faculty_name = $row['faculty_name'];
    $facultyOptions .= "<option value='$faculty_id'>$faculty_name</option>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
        }

        h2 {
            text-align: center;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #04AA6D;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        button[type="button"] {
            background-color: #f44336;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 10px;
        }

        button[type="button"]:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <h2>Assign Subject to Faculty</h2>

    <form method="POST">
        <label for="subject_id">Subject ID:</label>
        <input type="text" name="subject_id" id="subject_id" required><br>

        <label for="semester">Semester:</label>
        <input type="text" name="semester" id="semester" required><br>

        <label for="subject_name">Subject Name:</label>
        <input type="text" name="subject_name" id="subject_name" required><br>

        <label for="faculty_id">Select Faculty:</label>
        <select name="faculty_id" id="faculty_id" required>
            <?php echo $facultyOptions; ?>
        </select><br>

        <input type="submit" value="Assign Subject">
        <a href="admindashboard.php"><button type="button">Back</button></a>
    </form>
</body>
</html>
