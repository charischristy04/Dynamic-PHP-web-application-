<?php
include 'db_config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $course = trim($_POST['course']);
    $enrollment_date = $_POST['enrollment_date'];
    
    // Validation
    if (empty($name) || empty($email) || empty($phone) || empty($course) || empty($enrollment_date)) {
        $error = 'All fields are required!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format!';
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error = 'Phone number must be 10 digits!';
    } else {
        // Insert using prepared statement
        $stmt = $conn->prepare("INSERT INTO students (name, email, phone, course, enrollment_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $course, $enrollment_date);
        
        if ($stmt->execute()) {
            header("Location: index.php?msg=added");
            exit();
        } else {
            $error = 'Error: ' . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Add New Student</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" required>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone" pattern="[0-9]{10}" required>
            </div>
            
            <div class="form-group">
                <label>Course:</label>
                <select name="course" required>
                    <option value="">Select Course</option>
                    <option value="Computer Science">Computer Science</option>
                    <option value="Information Technology">Information Technology</option>
                    <option value="Electronics">Electronics</option>
                    <option value="Mechanical">Mechanical</option>
                    <option value="Civil">Civil</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Enrollment Date:</label>
                <input type="date" name="enrollment_date" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Student</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>
