<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summed Results by LGA</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
    include("include/navbar.php")
    ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Summed Results by LGA</h1>
        
        <!-- LGA Select Form -->
        <form action="" method="GET" class="row g-3 mb-4">
            <div class="col-12 col-md-6 mx-auto">
                <label for="lga_id" class="form-label">Select Local Government</label>
                <select name="lga_id" id="lga_id" class="form-select" required>
                    <option value="" disabled selected>Select LGA</option>
                    <?php
                    // Database connection
                    include('db_connection.php');

                    // Fetch all LGAs
                    $lga_query = "SELECT lga_id, lga_name FROM lga";
                    $lga_result = $conn->query($lga_query);
                    if ($lga_result->num_rows > 0) {
                        while ($lga = $lga_result->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($lga['lga_id']) . "'>" . htmlspecialchars($lga['lga_name']) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary mt-3">Get Results</button>
                <a href="summed_results.php" class="btn btn-secondary mt-3">Reset</a>
            </div>
        </form>

        <!-- Results Display -->
        <?php
        if (isset($_GET['lga_id'])) {
            $lga_id = intval($_GET['lga_id']);
            
            // Query to sum results for the selected LGA
            $query = "
                SELECT SUM(pr.party_score) AS total_score, p.polling_unit_name
                FROM polling_unit AS p
                JOIN announced_pu_results AS pr ON p.polling_unit_id = pr.polling_unit_uniqueid
                WHERE p.lga_id = $lga_id
                GROUP BY p.polling_unit_name
            ";

            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                echo '<div class="table-responsive">';
                echo '<table class="table table-bordered table-striped">';
                echo '<thead class="table-dark"><tr><th>#</th><th>Polling Unit</th><th>Total Score</th></tr></thead>';
                echo '<tbody>';

                $count = 1;
                $grand_total = 0;

                while ($row = $result->fetch_assoc()) {
                    $grand_total += $row['total_score'];
                    echo "<tr>";
                    echo "<td>" . $count++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['polling_unit_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['total_score']) . "</td>";
                    echo "</tr>";
                }

                echo '<tr class="table-info">';
                echo '<td colspan="2" class="text-end"><strong>Grand Total</strong></td>';
                echo '<td><strong>' . htmlspecialchars($grand_total) . '</strong></td>';
                echo '</tr>';

                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            } else {
                echo '<div class="alert alert-warning text-center">No results found for the selected LGA.</div>';
            }
        }

        $conn->close();
        ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
