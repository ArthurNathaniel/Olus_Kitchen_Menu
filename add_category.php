<?php
// Include database connection
include('db.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $category_name = $_POST['category_name'];

    // Insert category data into the database
    $sql = "INSERT INTO categories (category_name) VALUES ('$category_name')";

    if ($conn->query($sql) === TRUE) {
        echo "New category added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Add New Category</h1>
        <form action="add_category.php" method="POST">
            <div>
                <label for="category_name">Category Name</label>
                <input type="text" id="category_name" name="category_name" required>
            </div>
            <button type="submit">Add Category</button>
        </form>
    </div>
</body>
</html>
