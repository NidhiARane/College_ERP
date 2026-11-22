<?php
require_once "config/db.php";
session_start();
if(isset($_SESSION["user_id"]))
{
    header("location: dashboard.php");
    exit;
}

$msg = "";
if($_SERVER["REQUEST_METHOD"] === "POST")
    {
        $email = trim($_POST["email"]);
        $password =trim($_POST["password"]);
        
        $qry = $conn ->prepare("SELECT * FROM users WHERE email = ?");
        $qry -> execute([$email]);
        $user = $qry->fetch(PDO::FETCH_ASSOC);
        if($user && password_verify($password,$user["password"]))
        {
            $_SESSION["user_id"] = $user["id"];
            setcookie("id", $user["id"],time()+3600,"/");
            header("location: dashboard.php");
            exit;
        }
        else{
            $msg = "invalid login details";
        }
    }

    ?>

    <!DOCTYPE html>
        <html>
            <head>
    <title>College ERP Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class = "bg-light">
        <div class = "container mt-5">
           <div class = "card mx-auto shadow p-4" style = "max-width: 400px;" >
                <h3 class ="text-center text-primary " >College ERP Login</h3>
                <p class = "text-center text-danger" ><?php echo $msg ?></p>


                <form method="POST" id="login form">
                    <input type="email" class="form-control mb-3" placeholder="email" name="email" required>
                    <input type="password" class="form-control mb-3" placeholder="password" name="password" required>
                    <button type="submit" class = "btn btn-primary w-100">Login</button>
                </form>
                <p class = "text-center mt-3" >Don't have an account? <a href="register.php">Register here</a></p>

            </div>
        </div>
    </body>
        </html>
                
        




