<?php
session_start();

$mysqli = new mysqli("localhost:3306", "root", "password", "db");

if ($mysqli->connect_errno) {
  printf("Connect failed: %s\n", $mysqli->connect_error);
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $stmt = $mysqli->prepare("SELECT id FROM users WHERE name = ? AND password = ?;");
  $name = $_POST['name'];
  $password = hash("sha256", $_POST['password']);
  $stmt->bind_param("ss", $name, $password);
  $stmt->execute();
  $stmt->bind_result($user);
  $stmt->fetch();
  if ($user) {
    $_SESSION['user'] = $user;
  } else {
    $error = 'Incorrect username or password';
  }
  $stmt->close();
}?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Retro Relics - Dashboard</title>
  <link rel="stylesheet" href="/styles.css" />
</head>
<body>
  <header>
    <a href="/"><img src="/mediafiles/logo.png" alt="Retro Relics Logo"></a>
    <?php if (isset($_SESSION["user"])) { ?><a href="/uitloggen" class="btn">Logout</a><?php } ?>
  </header>
  <main>
    <?php if (!isset($_SESSION["user"])) { ?>
      <h1>Login</h1>
      <form action="" method="POST">
        <label>
          <span>Username</span>
          <input type="text" name="name" />
        </label>
        <label>
          <span>Password</span>
          <input type="password" name="password" />
        </label>
        <input class="btn" type="submit" value="Log in" />
        <div class="error"><?= $error ?></div>
      </form>
    <?php } else { ?>
      <h1>Dashboard</h1>
      <h2>KPIs</h2>
      <section>
        <?php
          $stmt = $mysqli->prepare("SELECT COUNT(id) FROM bids;");
          $stmt->execute();
          $stmt->bind_result($count);
          $stmt->fetch();
          $class = $count > 600 ? "kpi-success" : "kpi-fail";
          echo "<article class=\"kpi $class\"><h3>Total bids</h3><p>$count</p><p>Target: 600</p></article>";
          $stmt->close();

          $stmt = $mysqli->prepare("SELECT SUM(max) FROM (SELECT MAX(amount) as max FROM bids GROUP BY product_id) s;");
          $stmt->execute();
          $stmt->bind_result($amount);
          $stmt->fetch();
          $class = $amount > 120000 ? "kpi-success" : "kpi-fail";
          echo "<article class=\"kpi $class\"><h3>Total income</h3><p>£$amount.-</p><p>Target: £120'000.-</p></article>";
          $stmt->close();

          $stmt = $mysqli->prepare("SELECT c.title, SUM(max) FROM (SELECT MAX(amount) as max, product_id FROM bids GROUP BY product_id) s JOIN products p ON p.id = s.product_id JOIN categories c ON c.id = p.category_id GROUP BY c.id ORDER BY SUM(max) DESC LIMIT 1;");
          $stmt->execute();
          $stmt->bind_result($title, $amount);
          $stmt->fetch();
          $class = $title == "Vehicle" || $title == "Jewelry" ? "kpi-success" : "kpi-fail";
          echo "<article class=\"kpi $class\"><h3>Best category</h3><p>$title (£$amount.-)</p><p>Target: Vehicle or Jewelry</p></article>";
          $stmt->close();

          $stmt = $mysqli->prepare("SELECT COUNT(DISTINCT b.product_id) / COUNT(DISTINCT p.id) FROM products p LEFT JOIN bids b ON p.id = b.product_id");
          $stmt->execute();
          $stmt->bind_result($percentage);
          $stmt->fetch();
          $class = $percentage > 0.93 ? "kpi-success" : "kpi-fail";
          $percentage = round($percentage * 100);
          echo "<article class=\"kpi $class\"><h3>Products with at least one bid</h3><p>$percentage%</p><p>Target: 93%</p></article>";
          $stmt->close();

          $stmt = $mysqli->prepare("SELECT AVG(max / p.minimal_price) FROM (SELECT MAX(amount) as max, product_id FROM bids GROUP BY product_id) s JOIN products p ON p.id = s.product_id;");
          $stmt->execute();
          $stmt->bind_result($profit);
          $stmt->fetch();
          $class = $profit > 2.5 ? "kpi-success" : "kpi-fail";
          $profit = round($profit * 100);
          echo "<article class=\"kpi $class\"><h3>Average profit margin</h3><p>$profit%</p><p>Target: 250%</p></article>";
          $stmt->close();
        ?>
      </section>
      <h2>Top 5 products</h2>
      <section>
        <?php
          $stmt = $mysqli->prepare("SELECT title, COUNT(b.id), image FROM products p LEFT JOIN bids b ON b.product_id = p.id GROUP BY p.id ORDER BY COUNT(b.id) DESC LIMIT 5;");
          $stmt->execute();
          $stmt->bind_result($title, $count, $image);
          while ($stmt->fetch()) {
            echo "<article><img src=\"/mediafiles/images/$image.jpg\" alt=\"$title\" /><h3>$title</h3><p>$count Bids</p></article>";
          }
        ?>
      </section>
      <h2>Products without bids</h2>
      <section>
        <?php
          $stmt = $mysqli->prepare("SELECT title, image FROM products p LEFT JOIN bids b ON b.product_id = p.id WHERE b.id IS NULL;");
          $stmt->execute();
          $stmt->bind_result($title, $image);
          while ($stmt->fetch()) {
            echo "<article><img src=\"/mediafiles/images/$image.jpg\" alt=\"$title\" /><h3>$title</h3></article>";
          }
        ?>
      </section>
      <h2>Income per category</h2>
      <canvas id="chart"></canvas>
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <?php
        $incomes = [];
        $categories = [];
        $stmt = $mysqli->prepare("SELECT c.title, SUM(max) FROM (SELECT MAX(amount) as max, product_id FROM bids GROUP BY product_id) s JOIN products p ON p.id = s.product_id JOIN categories c ON c.id = p.category_id GROUP BY c.id ORDER BY c.id;");
        $stmt->execute();
        $stmt->bind_result($title, $income);
        while ($stmt->fetch()) {
          $incomes[] = $income;
          $categories[] = $title;
        }
      ?>
      <script>
        const ctx = document.getElementById('chart');

        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: ['<?= implode("', '", $categories) ?>'],
            datasets: [{
              label: 'Income in £',
              data: [<?= implode(", ", $incomes) ?>],
              borderWidth: 1,
              backgroundColor: 'rgba(202, 74, 206, 0.4)'
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      </script>
      <h2>Important Feedback</h2>
      <section>
        <?php
          $stmt = $mysqli->prepare("SELECT rating, description FROM feedback ORDER BY rating, LENGTH(description) DESC LIMIT 15;");
          $stmt->execute();
          $stmt->bind_result($rating, $message);
          while ($stmt->fetch()) {
            echo "<article><h3>$rating/5</h3>$message</article>";
          }
        ?>
      </section>
    </main>
    <?php } ?>
</body>
</html>