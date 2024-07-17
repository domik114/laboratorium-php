<?php
function show_table($blend_name)
{
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  require_once('helpers.php');
  $logged_in = logged_in();
  $admin = is_admin();
  ?>
  <table class="table border">
    <tr>
      <th>ID</th>
      <th>BlendName</th>
      <th>Origin</th>
      <th>Variety</th>
      <th>Notes</th>
      <th>Intensifier</th>
      <th>Price</th>
      <?php
      if ($logged_in) {
        echo ("<th>Shop</th>");
      }
      if ($admin) {
        echo ("<th>Delete</th>");
      }
      ?>
    </tr>
    <?php
    require_once('connectdb.php');
    // vulnerability to sql injection
    $query = "SELECT * FROM coffee WHERE blend_name LIKE ?;";
    ?>
    <br>
    <div class="card">
      <div class="card-body">
        Query: <code>
          <?php
          echo ($query);
          ?>
        </code>
      </div>
    </div>
    <br>
    <h3>Coffee</h3>
    <?php
    // mysqli_multi_query is required to demonstrate all possible
    // sql-injection variants. With mysqli_query only the first
    // query statement is performed to prevent sql injection...
    try {
      $db = connectdb();
      $stmt = mysqli_prepare($db, $query);
      if ($stmt) {
        $blend_name = '%' . $blend_name . '%';
        mysqli_stmt_bind_param($stmt, "s", $blend_name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result) {
          while ($coffee = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo ('<tr>');
            foreach ($coffee as $attr) {
              echo ('<td>' . $attr . '</td>');
            }
            if ($logged_in) {
              $id = $coffee['id'];
              echo ("
                <td>
                  <form action=\"lib/add_to_cart.php\" method=\"post\">
                    <button name=\"item\" value=\"$id\" type=\"submit\" class=\"btn px-1 py-0\">
                      🛒
                    </button>
                  </form>
                </td>
              ");
            }
            if ($admin) {
              $id = $coffee['id'];
              echo ("
                <th>
                  <form class=\"form-inline mr-3\" action=\"lib/delete_item.php\" method=\"post\">
                    <button name=\"id\" value=\"$id\" type=\"submit\" class=\"btn btn-danger px-1 py-0\">
                      &times;
                    </button>
                  </form>
                </th>
              ");
            }
            echo ('</tr>');
          }
        } else {
          try {
            echo ('ERROR: ' . mysqli_error($db));
          } catch (Error $e) {
            echo('ERROR: ' . $e);
          } 
        }
        mysqli_stmt_close($stmt);
      }
    } catch (Error $e) {
      $result = false;
      echo ('ERROR: ' . $e);
    }
    try {
      mysqli_close($db);
      
    } catch (Error $e) {
      echo('ERROR: ' . $e);
    }
    ?>
  </table>
<?php
}
?>