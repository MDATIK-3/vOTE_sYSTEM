<?php
require 'db.php';

try {

    $stmt = $pdo->query('SELECT COUNT(*) FROM voterdistrict');
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        $pdo->exec('INSERT INTO voterdivision (Id, Name) VALUES (1, "Division 1"), (2, "Division 2"), (3, "Division 3")');
        $stmt = $pdo->prepare('INSERT INTO voterdistrict (Name, DivisionId) VALUES (?, ?), (?, ?), (?, ?)');
        $stmt->execute(['District A', 1, 'District B', 2, 'District C', 3]);
    }

    $stmt = $pdo->query('SELECT * FROM voterdistrict');
    $voterDistricts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vote'])) {
    $nid = $_POST['NID'];
    $voterRegion = $_POST['VoterRegion'];

    try {
        $stmt = $pdo->prepare('INSERT INTO voterlist (NID, VoterRegion) VALUES (?, ?)');
        $stmt->execute([$nid, $voterRegion]);
        echo "<script>alert('Vote recorded successfully.');</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error recording vote: " . $e->getMessage() . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote</title>
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
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="vote.php">Vote</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Vote</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="NID">National ID (NID)</label>
                <input type="text" class="form-control" id="NID" name="NID" required>
            </div>
            <div class="form-group">
                <label for="VoterRegion">Voter Region</label>
                <select class="form-control" id="VoterRegion" name="VoterRegion" required>
                    <?php if (!empty($voterDistricts)): ?>
                        <?php foreach ($voterDistricts as $district): ?>
                            <option value="<?php echo $district['Id']; ?>"><?php echo htmlspecialchars($district['Name']); ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No districts available</option>
                    <?php endif; ?>
                </select>
            </div>
            <button type="submit" name="vote" class="btn btn-primary">Vote</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
