<?php

require_once __DIR__ . "/../db.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("Invalid client ID.");
}

$get = mysqli_query($conn, "SELECT * FROM clients WHERE client_id = $id");

if (!$get) {
    die("Database query failed: " . mysqli_error($conn));
}

$client = mysqli_fetch_assoc($get);

if (!$client) {
    die("Client not found.");
}

$message = "";

if (isset($_POST['update'])) {

    $full_name = trim($_POST['full_name'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $phone = trim($_POST['phone'] ?? "");
    $address = trim($_POST['address'] ?? "");

    if ($full_name == "" || $email == "") {

        $message = "Name and Email are required!";

    } else {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE clients
             SET full_name = ?, email = ?, phone = ?, address = ?
             WHERE client_id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssssi",
            $full_name,
            $email,
            $phone,
            $address,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {

            header("Location: clients_list.php");
            exit;

        } else {

            $message = "Update failed: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Edit Client</title>
</head>

<body>

<?php include __DIR__ . "/../nav.php"; ?>

<h2>Edit Client</h2>

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
        value="<?php echo htmlspecialchars($client['full_name']); ?>"
        required
    >
    <br><br>

    <label>Email*</label><br>
    <input
        type="email"
        name="email"
        value="<?php echo htmlspecialchars($client['email']); ?>"
        required
    >
    <br><br>

    <label>Phone</label><br>
    <input
        type="text"
        name="phone"
        value="<?php echo htmlspecialchars($client['phone'] ?? ''); ?>"
    >
    <br><br>

    <label>Address</label><br>
    <input
        type="text"
        name="address"
        value="<?php echo htmlspecialchars($client['address'] ?? ''); ?>"
    >
    <br><br>

    <button type="submit" name="update">Update</button>

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
