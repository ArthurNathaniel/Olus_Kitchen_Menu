<?php
include('db.php');

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Extract data
$customerName = $data['customerName'];
$customerEmail = $data['customerEmail'];
$customerPhone = $data['customerPhone'];
$pickupOrDelivery = $data['pickupOrDelivery'];
$deliveryAddress = $data['deliveryAddress'];
$orders = json_encode($data['orders']);
$total = $data['total'];

// Insert customer details into the database
$sql = "INSERT INTO customer_orders (name, email, phone, pickup_delivery, delivery_address, orders, total_amount) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssssssd', $customerName, $customerEmail, $customerPhone, $pickupOrDelivery, $deliveryAddress, $orders, $total);

$response = ['success' => false];
if ($stmt->execute()) {
    $response['success'] = true;
}
$stmt->close();
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
