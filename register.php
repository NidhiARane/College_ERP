<?php
require_once "config/db.php";
 
$msg = "";

//submit button clicked

if($_SERVER["REQUEST_METHOD"]==="POST")
    {
        $name = trim($_POST["name"] );
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"] );
        $usn = strtoupper(trim($_POST["usn"]  ));
        $course = trim($_POST["course"]  );
        $semester = (int)$_POST["semester"]  ;
        $phone = trim($_POST["phone"]  );

        if(!$name || !$usn || strlen($password) < 8  || 
        !filter_var($email,FILTER_VALIDATE_EMAIL) || $semester < 1 || $semester > 4
        || !in_array($course,["MCA","MBA"]))
        {
            $msg = "invalid registeration details";
        }
        else{
            try{
            $conn->beginTransaction();
            $qry = $conn->prepare("INSERT INTO users(name,email,password) VALUES (?,?,?)");
            $qry->execute([$name,$email,password_hash($password, PASSWORD_DEFAULT)]);
            $qry = $conn->prepare("INSERT INTO students(user_id,usn,course,semester,phone) VALUES (?,?,?,?,?)");
            $qry->execute([$conn->lastInsertId(),$usn,$course,$semester,$phone ?: null]);
            $conn->commit();
            $msg = "registeration successfull";
            }
            catch(PDOException $e)
            {
                if($conn->inTransaction()) $conn->rollback();
                $msg = "email or usn already present";
            }
        
        }
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
        <div class = "container mt-5">
           <div class = "card mx-auto shadow p-4" style = "max-width: 400px;" >
                <h3 class ="text-center text-primary " >College ERP Registration</h3>
                <p class = "text-center text-danger" ><?php echo $msg ?></p>


                <form method="POST" id="register form">
                    <input type="text" class="form-control mb-3" placeholder="name" name="name" required>
                    <input type="email" class="form-control mb-3" placeholder="email" name="email" required>
                    <input type="password" class="form-control mb-3" placeholder="password" id="pass" name="password" required>
                  <input type="password" class="form-control mb-3" placeholder=" confirm password" id="confirm" name="confirm_password" required>

                    <input type="text" class="form-control mb-3" placeholder="usn" name="usn" required>
                    <input type="tel" class="form-control mb-3" placeholder="phone" id="phone" name="phone" required>

                    <select name="course" class="form-select mb-3" required>
                        <option value="">Select Course</option>
                        <option value="MCA">MCA</option>
                        <option value="MBA">MBA</option>
                    </select>

                    <select name="semester" class="form-select mb-3" required>
                        <option value="">Select Semester</option>
                        <?php
                        for($i = 1; $i <= 4; $i++) {
                            echo "<option value=\"$i\">$i</option>";
                        }
                        ?>
                    </select>

                    <button type="submit" class="btn btn-primary w-100">Register</button>

                </form>
           </div>
            <p class = "text-center mt-3" >Do have an account? <a href="login.php">Login here</a></p>

        </div>

        <script>
            document.getElementById("register form").onsubmit = function(e)
            {
                if(document.getElementById("pass").value !== document.getElementById("confirm").value)
                {
                    e.preventDefault();
                    alert("Passwords do not match!");
                }
                const phoneInput = document.getElementById("phone").value;
                const phonePattern = /^\d{10}$/; // Regular expression for 10 digits
                if (!phonePattern.test(phoneInput)) {
                    e.preventDefault();
                    alert("Phone number must be exactly 10 digits.");
                }

            }
            
        </script>
            

        

    </body>
    </html>


        </html>









    