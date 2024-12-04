<?php
// Include database connection
include('db.php');

// Fetch categories from the database
$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $food_name = $_POST['food_name'];
    $food_price = $_POST['food_price'];
    $category = $_POST['category']; // This will now be the category ID
    $image = $_FILES['food_image']['name'];
    $target_dir = "images/";
    $target_file = $target_dir . basename($image);

    // Upload the image
    if (move_uploaded_file($_FILES['food_image']['tmp_name'], $target_file)) {
        // Insert food data into the database
        $sql = "INSERT INTO menu_items (food_name, food_price, category_id, food_image) 
                VALUES ('$food_name', '$food_price', '$category', '$target_file')";

        if ($conn->query($sql) === TRUE) {
            echo "New food item added successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Food Item</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Add New Food Item</h1>
        <form action="add_food.php" method="POST" enctype="multipart/form-data">
            <div>
                <label for="food_name">Food Name</label>
                <input type="text" id="food_name" name="food_name" required>
            </div>
            <div>
                <label for="food_price">Food Price (GHS)</label>
                <input type="number" id="food_price" name="food_price" required>
            </div>
            <div>
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="">Select a Category</option>
                    <?php
                    // Fetch categories and display them in the dropdown
                    while ($row = $result_categories->fetch_assoc()) {
                        echo '<option value="' . $row['id'] . '">' . $row['category_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="food_image">Food Image</label>
                <input type="file" id="food_image" name="food_image" required>
            </div>
            <button type="submit">Add Food</button>
        </form>
    </div>
</body>
</html>
