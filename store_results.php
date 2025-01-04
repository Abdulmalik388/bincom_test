<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Results for New Polling Unit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Add new party row dynamically
        function addPartyRow() {
            const tableBody = document.getElementById("party-scores");
            const rowCount = tableBody.rows.length;
            const row = tableBody.insertRow(rowCount);
            row.innerHTML = `
                <td>${rowCount + 1}</td>
                <td><input type="text" name="party_names[]" class="form-control" required placeholder="Party Name"></td>
                <td><input type="number" name="party_scores[]" class="form-control" required placeholder="Party Score"></td>
                <td><button type="button" class="btn btn-danger" onclick="deletePartyRow(this)">Delete</button></td>
            `;
        }

        // Delete a party row
        function deletePartyRow(button) {
            const row = button.closest("tr");
            row.remove();
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Store Results for New Polling Unit</h1>

        <!-- Form -->
        <form action="" method="POST" class="mt-4">
            <!-- Polling Unit Details -->
            <div class="mb-3">
                <label for="polling_unit_id" class="form-label">Polling Unit ID</label>
                <input type="number" name="polling_unit_id" id="polling_unit_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="polling_unit_name" class="form-label">Polling Unit Name</label>
                <input type="text" name="polling_unit_name" id="polling_unit_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="polling_unit_description" class="form-label">Polling Unit Description</label>
                <textarea name="polling_unit_description" id="polling_unit_description" class="form-control" rows="3"></textarea>
            </div>

            <!-- Party Scores -->
            <h3 class="mt-4">Party Scores</h3>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Party Name</th>
                        <th>Party Score</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="party-scores">
                    <tr>
                        <td>1</td>
                        <td><input type="text" name="party_names[]" class="form-control" required placeholder="Party Name"></td>
                        <td><input type="number" name="party_scores[]" class="form-control" required placeholder="Party Score"></td>
                        <td><button type="button" class="btn btn-danger" onclick="deletePartyRow(this)">Delete</button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-secondary mb-3" onclick="addPartyRow()">Add Party</button>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit Results</button>
            </div>
        </form>

        <?php
      
      
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          // Database connection
          include('db_connection.php');
      
          // Get polling unit details
          $polling_unit_id = intval($_POST['polling_unit_id']);
          $polling_unit_name = $conn->real_escape_string($_POST['polling_unit_name']);
          $polling_unit_description = $conn->real_escape_string($_POST['polling_unit_description']);
          $party_names = $_POST['party_names'];
          $party_scores = $_POST['party_scores'];
      
          // Insert into database
          $success = true;
      
          // Loop through each party's name and scores
          for ($i = 0; $i < count($party_names); $i++) {
              $party_name = $conn->real_escape_string($party_names[$i]);
              $party_scores_value = intval($party_scores[$i]);
      
              // Insert each row into the database
              $insert_query = "
                  INSERT INTO polling_unit (polling_unit_id, polling_unit_name, polling_unit_description, party_name, party_scores)
                  VALUES ('$polling_unit_id', '$polling_unit_name', '$polling_unit_description', '$party_name', '$party_scores_value')
              ";
      
              if (!$conn->query($insert_query)) {
                  $success = false;
                  break;
              }
          }
      
          // Show success or error message
          if ($success) {
              echo '<div class="alert alert-success text-center">Results successfully stored!</div>';
          } else {
              echo '<div class="alert alert-danger text-center">Error storing results: ' . $conn->error . '</div>';
          }
      
          $conn->close();
      }
      ?>
      
   
      
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
