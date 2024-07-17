
  <?php
  session_start();
  require_once('helpers.php');
  
  if (logged_in()) {
    header('Location: ../index.php');
    exit;    
  }
  
  $name = $_POST['username'];
  $password = $_POST['password'];
  $query = "SELECT * FROM users WHERE name=? AND password=?;";
  
  require_once('connectdb.php');
  $db = connectdb();

  $stmt = mysqli_prepare($db, $query);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ss", $name, $password);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
      $_SESSION['user_id'] = $user['id'];
    }

    mysqli_stmt_close($stmt);
  }

  if (!isset($_SESSION['user_id'])) {
    $_SESSION['login_error'] = true;
  }

  mysqli_close($db);

  header('Location: ../index.php');
  ?>