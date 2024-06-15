<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO address (Street, City, State, ZipCode) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_POST['Street'], $_POST['City'], $_POST['State'], $_POST['ZipCode']]);
            $addressId = $pdo->lastInsertId();

            $stmt = $pdo->prepare("INSERT INTO birthcertificate (Name, DateOfBirth, Gender, AddressId) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_POST['Name'], $_POST['DateOfBirth'], $_POST['Gender'], $addressId]);
            $birthIdNo = $pdo->lastInsertId();

            $age = date_diff(date_create($_POST['DateOfBirth']), date_create('now'))->y;
            $stmt = $pdo->prepare("INSERT INTO nationalid (IssueDate, BirthIdNo, Age, BloodGroup) VALUES (?, ?, ?, ?)");
            $stmt->execute([date('Y-m-d'), $birthIdNo, $age, $_POST['BloodGroup']]);

            $pdo->commit();
            echo "<script>alert('User registered successfully!');</script>";
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            animation: fadeIn 2s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .navbar, .container {
            animation: slideIn 1s ease-in-out;
        }
        @keyframes slideIn {
            from { transform: translateY(-20px); }
            to { transform: translateY(0); }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <a class="navbar-brand" href="index.php">Project</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="vote.php">Vote</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="find_user.php">Find User</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Register</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="Name">Name</label>
                <input type="text" class="form-control" id="Name" name="Name" required>
            </div>
            <div class="form-group">
                <label for="DateOfBirth">Date of Birth</label>
                <input type="date" class="form-control" id="DateOfBirth" name="DateOfBirth" required>
            </div>
            <div class="form-group">
                <label for="Gender">Gender</label>
                <input type="text" class="form-control" id="Gender" name="Gender" required>
            </div>
            <div class="form-group">
                <label for="Street">Street</label>
                <input type="text" class="form-control" id="Street" name="Street" required>
            </div>
            <div class="form-group">
                <label for="City">City</label>
                <input type="text" class="form-control" id="City" name="City" required>
            </div>
            <div class="form-group">
                <label for="State">State</label>
                <input type="text" class="form-control" id="State" name="State" required>
            </div>
            <div class="form-group">
                <label for="ZipCode">Zip Code</label>
                <input type="text" class="form-control" id="ZipCode" name="ZipCode" required>
            </div>
            <div class="form-group">
                <label for="BloodGroup">Blood Group</label>
                <input type="text" class="form-control" id="BloodGroup" name="BloodGroup" required>
            </div>
            <button type="submit" name="register" class="btn btn-primary">Register</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
