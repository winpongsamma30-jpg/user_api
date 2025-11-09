<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");
include __DIR__ . "/db.php";


$method = $_SERVER['REQUEST_METHOD'];

// Ambil input JSON (untuk POST & PUT)
$input = json_decode(file_get_contents("php://input"), true);

switch ($method) {

    // ✅ GET (Tampilkan semua user / 1 user)
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $result = $conn->query("SELECT * FROM users WHERE id=$id");
            echo json_encode($result->fetch_assoc());
        } else {
            $result = $conn->query("SELECT * FROM users");
            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            echo json_encode($users);
        }
        break;

    // ✅ POST (Tambah user baru)
    case 'POST':
        $username = $input['username'];
        $email = $input['email'];
        $password = $input['password'];

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            echo json_encode(["message" => "User created successfully"]);
        } else {
            echo json_encode(["error" => $stmt->error]);
        }
        break;

    // ✅ PUT (Update user)
    case 'PUT':
        if (!isset($_GET['id'])) {
            echo json_encode(["error" => "ID required"]);
            break;
        }
        $id = $_GET['id'];
        $username = $input['username'];
        $email = $input['email'];
        $password = $input['password'];

        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=? WHERE id=?");
        $stmt->bind_param("sssi", $username, $email, $password, $id);
        if ($stmt->execute()) {
            echo json_encode(["message" => "User updated successfully"]);
        } else {
            echo json_encode(["error" => $stmt->error]);
        }
        break;

    // ✅ DELETE (Hapus user)
    case 'DELETE':
        if (!isset($_GET['id'])) {
            echo json_encode(["error" => "ID required"]);
            break;
        }
        $id = $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(["message" => "User deleted successfully"]);
        } else {
            echo json_encode(["error" => $stmt->error]);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
}
?>