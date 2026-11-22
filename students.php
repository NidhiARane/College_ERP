<?php
require_once "config/db.php";
session_start();
if(!isset($_SESSION["user_id"]))
{
    header("location: login.php");
    exit;
}


?>

 <!DOCTYPE html>
        <html>
            <head>
    <title>Student Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class = "bg-light">
        <nav class="navbar navbar-dark bg-primary px-3">
            <span class="navbar brand">College ERP</span>
            <a href="Logout.php">Logout</a>
            <a href="Dashboard.php">Dashboard</a>
        </nav> 
        <div class="container mt-5"><h3>Student Management</h3>
            <div class="row mb-3 g-2">
    <div class="col-md-6">
        <input type="text" id="search" class="form-control"
        placeholder="Search by name, email, USN, course, semester">
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary w-100" id="searchBtn">Search</button>
    </div>
</div>
                <div class="table-responsive">
                    <table class="table" id="studentTable">
                        <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>USN</th>
        <th>Course</th>
        <th>Semester</th>
        <th>Phone</th>
        <th>Actions</th>
    </tr>
</thead>
                        <tbody id="studentTableBody">
                            <tr>
                                <td colspan="8" class="text-center">Loading...</td>
                            </tr>
                            <!-- Student data will be populated here -->
                        </tbody>
                    </table>
                    
                </div>
                <p class="text-center text-muted"></p>
            </div>
        </div>
        <script src="assets/app.js"></script>
    </body>
    </html>
            
                        
                    
