<?php
require_once "../config/db.php";
session_start();

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["message" => "Unauthorized"]);
    exit;
}

$method = $_SERVER["REQUEST_METHOD"];
$d = json_decode(file_get_contents("php://input"), true) ?? [];

try {
    if ($method == "GET") {

        if (isset($_GET["id"])) {
            $qry = $conn->prepare("SELECT u.id,u.name,u.email,s.usn,s.course,s.semester,s.phone FROM students s JOIN users u ON s.user_id=u.id WHERE s.user_id=?");
            $qry->execute([$_GET["id"]]);
            echo json_encode($qry->fetch(PDO::FETCH_ASSOC));
        } else {
            $qry = $conn->query("SELECT u.id,u.name,u.email,s.usn,s.course,s.semester,s.phone FROM students s JOIN users u ON s.user_id=u.id");
            echo json_encode($qry->fetchAll(PDO::FETCH_ASSOC));
        }
    }

    elseif ($method == "POST") {
        $conn->beginTransaction();

        $qry = $conn->prepare("INSERT INTO users(name,email,password) VALUES (?,?,?)");
        $qry->execute([$d["name"],$d["email"],password_hash($d["password"],PASSWORD_DEFAULT)]);

        $qry = $conn->prepare("INSERT INTO students(user_id,usn,course,semester,phone) VALUES (?,?,?,?,?)");
        $qry->execute([$conn->lastInsertId(),$d["usn"],$d["course"],$d["semester"],$d["phone"] ?: null]);

        $conn->commit();
        echo json_encode(["message"=>"Registration successful"]);
    }

    elseif ($method == "PUT") {
        $qry = $conn->prepare("UPDATE users SET name=?,email=? WHERE id=?");
        $qry->execute([$d["name"],$d["email"],$d["id"]]);

        $qry = $conn->prepare("UPDATE students SET usn=?,course=?,semester=?,phone=? WHERE user_id=?");
        $qry->execute([$d["usn"],$d["course"],$d["semester"],$d["phone"] ?: null,$d["id"]]);

        echo json_encode(["message"=>"Update successful"]);
    }

    elseif ($method == "DELETE") {
        $qry = $conn->prepare("DELETE FROM students WHERE id=?");
        $qry->execute([$d["id"]]);
        echo json_encode(["message"=>"Delete successful"]);
    }

    else {
        http_response_code(405);
        echo json_encode(["message"=>"Method not allowed"]);
    }
}
catch(Throwable $e) {
    if ($conn->inTransaction()) $conn->rollback();
    http_response_code(400);
    echo json_encode(["message"=>$e->getMessage()]);
}