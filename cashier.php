<?php
$serverName = "Lyn\SQLEXPRESS01";
$connectionOptions = [
    "Database" => "DLSU",
    "Uid" => "",
    "PWD" => ""
];

$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Get POST data
$username = $_POST['username'];
$password = $_POST['password'];

// Check user
$sql = "SELECT ROLE, PASSWORD FROM EMPLOYEES WHERE USERNAME = ?";
$params = [$username];
$stmt = sqlsrv_query($conn, $sql, $params);

if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $role = $row['ROLE'];
    $dbPassword = $row['PASSWORD'];

    if ($password === $dbPassword) { // plaintext comparison
        // Redirect based on role and pass username in URL
        if ($role === 'ADMIN') {
            header("Location: admin.html?username=" . urlencode($username));
        } elseif ($role === 'CASHIER') {
            header("Location: cashier.html?username=" . urlencode($username));
        } else {
            echo "Unknown role!";
        }
        exit();
    } else {
        echo "Incorrect password!";
    }
} else {
    echo "Username not found!";
}
?>
