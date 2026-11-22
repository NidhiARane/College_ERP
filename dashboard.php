<?php
require_once "config/db.php";
session_start();
if(!isset($_SESSION["user_id"]))
{
    header("location: login.php");
    exit;
}
$qry = $conn->prepare("
    SELECT 
        users.id,
        users.name,
        users.email,
        students.usn,
        students.course,
        students.semester,
        students.phone
    FROM users
    INNER JOIN students ON users.id = students.user_id
    WHERE users.id = ?
");
$qry->execute([$_SESSION["user_id"]]);
$student = $qry->fetch(PDO::FETCH_ASSOC);
if(!$student)
    {
        die("student details not found");
    }

?>

 <!DOCTYPE html>
        <html>
            <head>
    <title>College ERP Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class = "bg-light">
        <nav class="navbar navbar-dark bg-primary px-3">
            <span class="navbar brand">College ERP</span>
            <a href="Logout.php">Logout</a>

        </nav> 
        <div class="container mt-5">
            <div class="card shadow p-4"><h3>Dashboard</h3>
        

        <hr>
        <h5>Student details
        </h5>
        <table class = "table table-bordered">
            <tr>
                <th>Name</th>
                <td><?php echo $student["name"] ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo $student["email"] ?></td>
            </tr>
            <tr>
                <th>USN</th>
                <td><?php echo $student["usn"] ?></td>
            </tr>
            <tr>
                <th>Course</th>
                <td><?php echo $student["course"] ?></td>
            </tr>
            <tr>
                <th>Semester</th>
                <td><?php echo $student["semester"] ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?php echo $student["phone"] ?? 'Not provided' ?></td>
            </tr>
        </table>

            <a href="students.php" class="btn btnprimary">View All Students</a>
        </div>
        </div>
        </body>
        </html>

