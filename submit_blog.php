<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
// Database configuration
$servername = "localhost";
$username = "db_user";
$password = "db_pass";
$dbname = "db_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else {
    echo "Connected successfully";
}

// Validate POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars(trim($_POST['title']));
    $explanation = htmlspecialchars(trim($_POST['explanation']));

    // Check image
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
        $fileTmp = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];

        if(!in_array($fileExt, $allowed)){
            echo "Only JPG, JPEG, PNG & GIF files are allowed.";
            exit;
        }

        // Rename and move file
        $newFileName = 'uploads/' . time() . '_' . preg_replace('/[^a-zA-Z0-9_.]/', '', $fileName);
        if(!is_dir('uploads')) { mkdir('uploads', 0755, true); }

        if(move_uploaded_file($fileTmp, $newFileName)){
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO blog_posts (title, explanation, image) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $title, $explanation, $newFileName);
            if($stmt->execute()){
                echo "Your blog post has been submitted successfully!";
            } else {
                echo "Database error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "Please select an image to upload.";
    }
}

$conn->close();
?>
CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    explanation TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
