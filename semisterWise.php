<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to calculate total marks for a student in a specific semester
function calculateTotalMarks($prn, $semester, $conn)
{
    $totalMarksQuery = "SELECT SUM(marks_obtained) AS total_marks
                       FROM marks
                       WHERE prn = $prn AND semester = $semester";

    $totalMarksResult = mysqli_query($conn, $totalMarksQuery);
    $totalMarksData = mysqli_fetch_assoc($totalMarksResult);

    return isset($totalMarksData['total_marks']) ? $totalMarksData['total_marks'] : 'N/A';
}
?>
<?php

// Include the database connection
@include 'conn.php';



// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['semester'])) {
    $semester = $_POST['semester'];

    // Calculate average total marks for the selected semester
    $averageMarksQuery = "SELECT AVG(marks_obtained) AS average_marks FROM marks WHERE semester = $semester";
    $averageMarksResult = mysqli_query($conn, $averageMarksQuery);
    $averageMarksData = mysqli_fetch_assoc($averageMarksResult);

    // Get top 5 students for the selected semester based on total marks
    $topStudentsQuery = "SELECT s.prn, s.student_name, m.marks_obtained
    FROM students s
    INNER JOIN marks m ON s.prn = m.prn
    WHERE m.semester = $semester
    ORDER BY m.marks_obtained DESC
    LIMIT 5;";
    $topStudentsResult = mysqli_query($conn, $topStudentsQuery);
    $topStudentsData = array();
    while ($row = mysqli_fetch_assoc($topStudentsResult)) {
        $topStudentsData[] = $row;
    }

    // Get the count of students who failed in the selected semester
    $failedStudentQuery = "SELECT COUNT(*) AS count FROM marks WHERE semester = $semester AND marks_obtained < 40";
    $failedStudentResult = mysqli_query($conn, $failedStudentQuery);
    $failedStudentCount = mysqli_fetch_assoc($failedStudentResult)['count'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Semester-wise Analysis</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!------======CSS=======------->
    <link rel="stylesheet" href="css/style.css">

    <!-----====== Iconscout CSS=====-->
    
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<style>
        .container {
            max-width: 850px;
            margin: 0 auto;
            text-align: center;
            padding: 75px 0px 50px 250px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            border: 3px solid #4b230f;
        }

        table th {
            background-color: #4a746d;
            font-weight: bold;
        }
        body {
            background: #ceebed;
            padding: auto;

        h2 {
            color: #FFFFFF;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #FFFFFF;
            padding: 8px;
        }
        th {
            background-color: #808080;
            color: #FFFFFF;
        }
        #chart_div{
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 40%;
        }
        .fchart{
            width: 500px;
            padding: 0px 0px 0px 15px;
        }
        #chart_div {
        display: block;
        margin: 0 auto;
        width: 80%; /* Adjust the width as needed */
        max-width: 500px; /* Set a maximum width for responsiveness */
    }
       /*Persentage Start*/
.flex-wrapper {
    
    flex-flow: row nowrap;
    display: flex; 
    justify-content: center;/* Add this line */
    }





    .single-chart {
    width: 33%;
    justify-content: space-around ;
    text-align: center; /* Add this line */
    }

    .circular-chart {
    display: block;
    margin: 10px auto;
    max-width: 80%;
    max-height: 250px;
    }

    .circle-bg {
    fill: none;
    stroke: #eee;
    stroke-width: 3.8;
    }

    .circle {
    fill: none;
    stroke-width: 2.8;
    stroke-linecap: round;
    animation: progress 1s ease-out forwards;
    }

    @keyframes progress {
    0% {
        stroke-dasharray: 0 100;
    }
    }

    .circular-chart.orange .circle {
    stroke: #ff9f00;
    }

    .circular-chart.green .circle {
    stroke: #4CC790;
    }

    .circular-chart.blue .circle {
    stroke: #3c9ee5;
    }

    .percentage {
    fill: #666;
    font-family: sans-serif;
    font-size: 0.5em;
    text-anchor: middle;
    }

/*persentage end*/


    </style>

</head>
<body>
    <nav>
        <div class="logo-name">
            <div class="logo-image">
                <img src="logo.png"  alt="">
            </div>

             <span class="logo name">RESULT ANALYSIS</span>
            
        </div>

        <div class="menu-items">
            <ul class="nav-links">
                <li>
                    <a href="index.php">
                        <i class="uil uil-home"></i>
                      <span class="link-name">Home</span>
                    </a>

                </li>
                <li>
                    <a href="adminlogin/index.php">
                        <i class="uil uil-sign-in-alt"></i>
                      <span class="link-name">Admin Page</span>
                    </a>

                </li>
                <li>
                    <a href="studentwise.php">
                      <i class="uil uil-estate"></i>
                      <span class="link-name">Student-Wise</span>
                    </a>

                </li>
                <li>
                    <a href="subjectwise.php">
                        <i class="uil uil-align-center-alt"></i>
                      <span class="link-name">Subject-wise</span>
                    </a>

                </li>
                <li>
                    <a href="facultywise.php">
                        <i class="uil uil-chart-line"></i>
                      <span class="link-name">Faculty-wise</span>
                    </a>

                </li>
                
            </ul>
            <ul class="logout-mod">
                <li>
                    <a href="index.php">
                        <i class="uil uil-signout"></i>
                      <span class="link-name">logout</span>
                    </a>

                </li>
                <li class="mode">
                    <a href="#">
                        <i class="uil uil-moon"></i>
                      <span class="link-name">Dark-mode</span>
                    </a>

                <div class="mode-toggle">
                    <span class="switch"></span>
                </div>

                </li>


            </ul>


        </div>

    </nav>
    <div class="container mt-5">
        <form method="POST" action="">
            <h2 class="mb-4">Semester-wise Analysis</h1>
            <div class="form-group">
                
                <!-- Select Semester Dropdown -->
<div class="form-group">
    <label for="semester">Select Semester:</label>
    <select class="form-control" name="semester" id="semester" required>
        <option value="">Select Semester</option>
        <?php
        // Fetch available semesters from the 'marks' table
        $semesterQuery = "SELECT DISTINCT semester FROM marks";
        $semesterResult = mysqli_query($conn, $semesterQuery);
        while ($row = mysqli_fetch_assoc($semesterResult)) {
            $selected = ($semester == $row['semester']) ? 'selected' : '';
            echo '<option value="' . $row['semester'] . '" ' . $selected . '>Semester ' . $row['semester'] . '</option>';
        }
        ?>
    </select>
</div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <?php if (isset($semester)) { ?>
            <div class="mt-5">
                <h4>Average Total Marks for Semester <?php echo $semester; ?>:</h4>
                <h5><?php echo $averageMarksData['average_marks']; ?></h5>
            </div>
<!--
            <div class="mt-5">
                Create a canvas element to display the chart -->
            <!--     <canvas id="chart"></canvas>
            </div>-->
            <div class="flex-wrapper">
                <div class="single-chart">
                    <svg viewBox="0 0 36 36" class="circular-chart orange">
                        <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                        <path class="circle" stroke-dasharray="<?php echo $averageMarksData ? number_format($averageMarksData['average_marks'], 2) : '0.00' ?>, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                        <text x="18" y="20.35" class="percentage"><?php echo $averageMarksData ? number_format($averageMarksData['average_marks'], 2) . '%' : '0.00%' ?></text>
                    </svg>
                </div>
            </div>
            <div class="mt-5">
    <h4>Top 5 Students in Semester <?php echo $semester; ?>:</h4>
    <?php if (!empty($topStudentsData)) { ?>
        <table class="table">
            <thead>
                <tr>
                    <th>PRN</th>
                    <th>Student Name</th>
                    <th>Total Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $addedStudents = [];
                $countTopStudents = 0;
                foreach ($topStudentsData as $student) {
                    if ($countTopStudents >= 5) {
                        break;
                    }

                    // Skip if the student is already added
                    if (in_array($student['prn'], $addedStudents)) {
                        continue;
                    }

                    // Skip if total marks are 'N/A'
                    $totalMarks = calculateTotalMarks($student['prn'], $semester, $conn);
                    if ($totalMarks === 'N/A') {
                        continue;
                    }

                    $addedStudents[] = $student['prn'];
                    $countTopStudents++;
                ?>
                    <tr>
                        <td><?php echo $student['prn']; ?></td>
                        <td><?php echo $student['student_name']; ?></td>
                        <td><?php echo $totalMarks; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Pie Chart -->
        <div style="width: 50%; margin: auto;">
            <canvas id="pieChart"></canvas>
        </div>

    <?php } else { ?>
        <p>No data available for the selected semester.</p>
    <?php } ?>
</div>






            <div class="mt-5">
                <h4>Total Number of Students Failed in Semester <?php echo $semester; ?>:</h4>
                <h5><?php echo $failedStudentCount; ?></h5>
            </div>

            <script>
                // JavaScript to create the chart
                var ctx = document.getElementById('chart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Semester <?php echo $semester; ?>'],
                        datasets: [{
                            label: 'Average Total Marks',
                            data: [<?php echo $averageMarksData['average_marks']; ?>],
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
        <?php } ?>
    </div>
</body>
</html>


<!-- JavaScript for creating the Pie Chart -->
<script>
    // Get the total marks and student names for the top 5 students
    var topStudentMarks = [];
    var topStudentNames = [];
    <?php foreach ($topStudentsData as $student) { ?>
        topStudentMarks.push(<?php echo calculateTotalMarks($student['prn'], $semester, $conn); ?>);
        topStudentNames.push('<?php echo $student['student_name']; ?>');
    <?php } ?>

    // Create the Pie Chart
    var pieChartCanvas = document.getElementById('pieChart').getContext('2d');
    var pieChart = new Chart(pieChartCanvas, {
        type: 'pie',
        data: {
            labels: topStudentNames,
            datasets: [{
                data: topStudentMarks,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            }
        }
    });
</script>

