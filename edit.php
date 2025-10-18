<?php
include 'db_config.php';

$error = '';
$id = $_GET['id'] ?? 0;

// Fetch student data
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if (!$student) {
    header("Location: index.php");
    exit();
}

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
        // Update using prepared statement
        $stmt = $conn->prepare("UPDATE students SET name=?, email=?, phone=?, course=?, enrollment_date=? WHERE id=?");
        $stmt->bind_param("sssssi", $name, $email, $phone, $course, $enrollment_date, $id);
        
        if ($stmt->execute()) {
            header("Location: index.php?msg=updated");
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
    <title>Edit Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Student</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>" pattern="[0-9]{10}" required>
            </div>
            
            <div class="form-group">
                <label>Course:</label>
                <select name="course" required>
                    <option value="">Select Course</option>
                    <option value="Computer Science" <?php echo ($student['course'] == 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                    <option value="Information Technology" <?php echo ($student['course'] == 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
                    <option value="Electronics" <?php echo ($student['course'] == 'Electronics') ? 'selected' : ''; ?>>Electronics</option>
                    <option value="Mechanical" <?php echo ($student['course'] == 'Mechanical') ? 'selected' : ''; ?>>Mechanical</option>
                    <option value="Civil" <?php echo ($student['course'] == 'Civil') ? 'selected' : ''; ?>>Civil</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Enrollment Date:</label>
                <input type="date" name="enrollment_date" value="<?php echo $student['enrollment_date']; ?>" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Student</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>
