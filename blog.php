<?php
$conn = new mysqli("localhost", "db_user", "db_pass", "db_name");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$result = $conn->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Blog Posts</title>
<style>
body { font-family: Arial, sans-serif; max-width: 900px; margin: 50px auto; }
.blog-post { border: 1px solid #ccc; padding: 20px; margin-bottom: 20px; border-radius: 10px; background: #fff; box-shadow: 0px 2px 8px rgba(0,0,0,0.05); }
.blog-post img { max-width: 100%; height: auto; margin-bottom: 10px; border-radius: 5px; }
.blog-post h2 { margin-bottom: 10px; }
</style>
</head>
<body>
<h1>Blog Posts</h1>

<?php while($row = $result->fetch_assoc()): ?>
  <div class="blog-post">
    <h2><?= htmlspecialchars($row['title']) ?></h2>
    <img src="<?= htmlspecialchars($row['image']) ?>" alt="Blog Image">
    <p><?= nl2br(htmlspecialchars($row['explanation'])) ?></p>
  </div>
<?php endwhile; ?>

</body>
</html>
<?php $conn->close(); ?>
