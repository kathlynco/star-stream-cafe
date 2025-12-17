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

// SQL with parameter
$sql = "SELECT ROLE, PASSWORD FROM EMPLOYEES WHERE USERNAME = ?";
$params = [$username];

$result = sqlsrv_query($conn, $sql, $params);

if ($result === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Check if user exists
if (sqlsrv_has_rows($result)) {
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $dbPassword = $row['PASSWORD'];
    $role = $row['ROLE'];

    // Plaintext password check (you can hash later)
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
