<?php
require 'db.php';

$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['search'])) {
        $name = $_POST['Name'];
        $gender = $_POST['Gender'];
        $dob = $_POST['DateOfBirth'];

        try {
            $stmt = $pdo->prepare("SELECT bc.Name, bc.DateOfBirth, bc.Gender, ad.Street, ad.City, ad.State, ad.ZipCode, nid.BloodGroup 
                                   FROM birthcertificate bc 
                                   JOIN nationalid nid ON bc.BirthIdNo = nid.BirthIdNo 
                                   JOIN address ad ON bc.AddressId = ad.AddressId
                                   WHERE bc.Name = ? OR bc.Gender = ? OR bc.DateOfBirth = ?");
            $stmt->execute([$name, $gender, $dob]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<script>alert('Database error: " . $e->getMessage() . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
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
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="vote.php">Vote</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="find_user.php">Find User</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Find User</h1>
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
            <button type="submit" name="search" class="btn btn-primary">Search</button>
        </form>

        <?php if (!empty($results)): ?>
            <h2 class="mt-5">Search Results</h2>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date of Birth</th>
                            <th>Gender</th>
                            <th>Address</th>
                            <th>Zip Code</th>
                            <th>Blood Group</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $result): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($result['Name']); ?></td>
                                <td><?php echo htmlspecialchars($result['DateOfBirth']); ?></td>
                                <td><?php echo htmlspecialchars($result['Gender']); ?></td>
                                <td><?php echo htmlspecialchars($result['Street']) . ', ' . htmlspecialchars($result['City']) . ', ' . htmlspecialchars($result['State']); ?></td>
                                <td><?php echo htmlspecialchars($result['ZipCode']); ?></td>
                                <td><?php echo htmlspecialchars($result['BloodGroup']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
