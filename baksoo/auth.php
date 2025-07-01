<?php
session_start();
require 'config.php';

function register($data) {
    global $conn;
    $username = mysqli_real_escape_string($conn, $data['username']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
    if (!mysqli_query($conn, $query)) {
        echo "Error SQL: " . mysqli_error($conn);
        return false;
    }
    return true;
}


function login($data) {
    global $conn;
    $username = mysqli_real_escape_string($conn, $data['username']);
    
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        if(password_verify($data['password'], $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ];
            return true;
        }
    }
    return false;
}
?>