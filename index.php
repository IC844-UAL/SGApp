<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Customer Catalog</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
</head>
<body>
  <div class = "container">
    <div class="jumbotron">
      <h1 class="display-4">Customer Catalog</h1>
      <p class="lead">Customer Catalog Sample Application</p>
      <hr class="my-4">
      <p>PHP sample application connected to a MySQL database to list a customer catalog</p>
    </div>
    <?php
    // Get database connection parameters from environment variables (set by app.yaml)
    $host = getenv('MYSQL_HOST');
    $user = getenv('MYSQL_USER');
    $pass = getenv('MYSQL_PASSWORD');
    $db = "sg_db";
    
    // Display environment variables for verification
    echo "<div class='alert alert-info mb-4' role='alert'>";
    echo "<h5 class='alert-heading'>Environment Variables (from app.yaml):</h5>";
    echo "<ul class='mb-0'>";
    echo "<li><strong>MYSQL_HOST:</strong> " . ($host ? '<span class="text-success">' . htmlspecialchars($host) . '</span>' : '<span class="text-danger">Not set</span>') . "</li>";
    echo "<li><strong>MYSQL_USER:</strong> " . ($user ? '<span class="text-success">' . htmlspecialchars($user) . '</span>' : '<span class="text-danger">Not set</span>') . "</li>";
    echo "<li><strong>MYSQL_PASSWORD:</strong> " . ($pass ? '<span class="text-success">Set (' . strlen($pass) . ' characters)</span>' : '<span class="text-danger">Not set</span>') . "</li>";
    echo "<li><strong>Database:</strong> " . htmlspecialchars($db) . "</li>";
    echo "</ul>";
    echo "</div>";
    ?>
    <table class="table table-striped table-responsive">
      <thead>
        <tr>
          <th>Name</th>
          <th>Credit Rating</th>
          <th>Address</th>
          <th>City</th>
          <th>State</th>
          <th>Country</th>
          <th>Zip</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Check if environment variables are set
        if (empty($host) || empty($user) || empty($pass)) {
            echo "</tbody></table></div>";
            echo "<div class='container mt-4'>";
            echo "<div class='alert alert-warning' role='alert'>";
            echo "<h4 class='alert-heading'>Missing Environment Variables!</h4>";
            echo "<p>Please ensure the following environment variables are set in app.yaml:</p>";
            echo "<ul>";
            if (empty($host)) echo "<li><strong>MYSQL_HOST</strong> is not set</li>";
            if (empty($user)) echo "<li><strong>MYSQL_USER</strong> is not set</li>";
            if (empty($pass)) echo "<li><strong>MYSQL_PASSWORD</strong> is not set</li>";
            echo "</ul>";
            echo "<p class='mb-0'>When deployed to App Engine, these are set from app.yaml automatically.</p>";
            echo "</div>";
            echo "</div>";
        } else {
            // Attempt database connection using environment variables from app.yaml
            $conexion = @mysqli_connect($host, $user, $pass, $db);

            // Check connection and display error if failed
            if (!$conexion) {
            $error_message = mysqli_connect_error();
            $error_code = mysqli_connect_errno();
            echo "</tbody></table></div>";
            echo "<div class='container mt-4'>";
            echo "<div class='alert alert-danger' role='alert'>";
            echo "<h4 class='alert-heading'>Database Connection Error!</h4>";
            echo "<p><strong>Error Code:</strong> " . htmlspecialchars($error_code) . "</p>";
            echo "<p><strong>Error Message:</strong> " . htmlspecialchars($error_message) . "</p>";
            echo "<hr>";
            echo "<p class='mb-0'><strong>Connection Details:</strong></p>";
            echo "<ul class='mb-0'>";
            echo "<li><strong>Host:</strong> " . htmlspecialchars($host) . "</li>";
            echo "<li><strong>User:</strong> " . htmlspecialchars($user) . "</li>";
            echo "<li><strong>Database:</strong> " . htmlspecialchars($db) . "</li>";
            echo "<li><strong>Password:</strong> " . (empty($pass) ? '<span class="text-warning">Not set</span>' : '<span class="text-success">Set</span>') . "</li>";
            echo "</ul>";
            echo "</div>";
            echo "</div>";
        } else {
            // Connection successful, execute query
            $cadenaSQL = "select * from s_customer";
            $resultado = mysqli_query($conexion, $cadenaSQL);

            // Check if query failed
            if (!$resultado) {
                echo "</tbody></table></div>";
                echo "<div class='container mt-4'>";
                echo "<div class='alert alert-warning' role='alert'>";
                echo "<h4 class='alert-heading'>Query Error!</h4>";
                echo "<p><strong>Error:</strong> " . htmlspecialchars(mysqli_error($conexion)) . "</p>";
                echo "<p><strong>Query:</strong> " . htmlspecialchars($cadenaSQL) . "</p>";
                echo "</div>";
                echo "</div>";
            } else {
                // Check if there are any rows
                if (mysqli_num_rows($resultado) == 0) {
                    echo "<tr><td colspan='7' class='text-center'>No customers found in the database.</td></tr>";
                } else {
                    // Display rows
                    while ($fila = mysqli_fetch_object($resultado)) {
                        echo "<tr><td> " . htmlspecialchars($fila->name) . 
                        "</td><td>" . htmlspecialchars($fila->credit_rating) .
                        "</td><td>" . htmlspecialchars($fila->address) .
                        "</td><td>" . htmlspecialchars($fila->city) .
                        "</td><td>" . htmlspecialchars($fila->state) .
                        "</td><td>" . htmlspecialchars($fila->country) .
                        "</td><td>" . htmlspecialchars($fila->zip_code) .
                        "</td></tr>";
                    }
                }
                mysqli_close($conexion);
            }
        }
        }
        ?>
     </tbody>
   </table>
 </div>
 <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
</body>
</html>
