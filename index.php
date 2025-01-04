<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polling Unit Results</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
     @media (max-width: 768px) {
             body  {
                font-size: 10px;
            }
        }
</style>
</head>
<body>
    <?php
    include("include/navbar.php")
    ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Polling Unit Results</h1>
        
        <!-- Search Form -->
        <form action="" method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="polling_unit_id" class="form-label">Polling Unit ID</label>
                <input type="number" class="form-control" id="polling_unit_id" name="polling_unit_id" placeholder="Enter ID">
            </div>
            <div class="col-md-4">
                <label for="polling_unit_name" class="form-label">Polling Unit Name</label>
                <input type="text" class="form-control" id="polling_unit_name" name="polling_unit_name" placeholder="Enter Name">
            </div>
            <div class="col-md-4">
                <label for="ward_id" class="form-label">Ward ID</label>
                <input type="number" class="form-control" id="ward_id" name="ward_id" placeholder="Enter Ward ID">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary mt-3">Search</button>
                <a href="index.php" class="btn btn-secondary mt-3">Reset</a>
            </div>
        </form>
        
        <!-- Results Table -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Polling Unit ID</th>
                        <th>Polling Unit Name</th>
                        <th>Description</th>
                        <th>Ward ID</th>
                        <th>LGA ID</th>
                        <th>Coordinates</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Database connection
                    include('db_connection.php');
                    
                    // Default query
                    $sql = "SELECT * FROM polling_unit WHERE 1=1";

                    // Add filters if available
                    if (!empty($_GET['polling_unit_id'])) {
                        $polling_unit_id = intval($_GET['polling_unit_id']);
                        $sql .= " AND polling_unit_id = $polling_unit_id";
                    }
                    if (!empty($_GET['polling_unit_name'])) {
                        $polling_unit_name = $conn->real_escape_string($_GET['polling_unit_name']);
                        $sql .= " AND polling_unit_name LIKE '%$polling_unit_name%'";
                    }
                    if (!empty($_GET['ward_id'])) {
                        $ward_id = intval($_GET['ward_id']);
                        $sql .= " AND ward_id = $ward_id";
                    }

                    // Execute query
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $count = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $count++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['polling_unit_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['polling_unit_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['polling_unit_description']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['ward_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['lga_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['lat']) . ", " . htmlspecialchars($row['long']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No results found</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
