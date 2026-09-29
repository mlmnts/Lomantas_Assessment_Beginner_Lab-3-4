<?php

require_once __DIR__ . "/../db.php";

$message = "";

if (isset($_POST['save'])) {

    $full_name = trim($_POST['full_name'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $phone = trim($_POST['phone'] ?? "");
    $address = trim($_POST['address'] ?? "");

    if ($full_name == "" || $email == "") {

        $message = "Name and Email are required!";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";

    } else {

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO clients
            (full_name, email, phone, address)
            VALUES (?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssss",
            $full_name,
            $email,
            $phone,
            $address
        );

        if (mysqli_stmt_execute($stmt)) {

            header("Location: clients_list.php");
            exit;

        } else {

            $message = "Error adding client: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    }
}

?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Add Client</title>
</head>

<body>

<?php include __DIR__ . "/../nav.php"; ?>

<h2>Add Client</h2>

<?php if ($message != ""): ?>
    <p style="color:red;">
        <?php echo htmlspecialchars($message); ?>
    </p>
<?php endif; ?>

<form method="post">

    <label>Full Name*</label><br>
    <input
        type="text"
        name="full_name"
        required
    >
    <br><br>

    <label>Email*</label><br>
    <input
        type="email"
        name="email"
        required
    >
    <br><br>

    <label>Phone</label><br>
    <input
        type="text"
        name="phone"
    >
    <br><br>

    <label>Address</label><br>
    <input
        type="text"
        name="address"
    >
    <br><br>

    <button type="submit" name="save">
        Save
    </button>

</form>

</body>
</html>

<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "assessment_db";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>
