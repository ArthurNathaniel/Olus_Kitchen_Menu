// Select elements
const menuItems = document.querySelectorAll('.menu-item');
const checkoutBtn = document.getElementById('view-checkout-btn'); // Corrected the id
const checkoutSection = document.getElementById('checkout');
const orderList = document.getElementById('order-list');
const totalElement = document.getElementById('total');
const paystackBtn = document.getElementById('paystack-btn');
const proceedBtn = document.getElementById('customer_details'); // Corrected the id

// Modal for customer details
const customerDetailsModal = document.getElementById('customer-details-modal');
const customerDetailsForm = document.getElementById('customer-details-form');
const pickupDelivery = document.getElementById('pickup-delivery');
const deliveryAddressField = document.getElementById('delivery-address-field');
const closeModalBtn = document.querySelector('.close-modal');

let orders = {};
let total = 0;

// Add items to the order
menuItems.forEach(item => {
    const addBtn = item.querySelector('.add-btn');
    const removeBtn = item.querySelector('.remove-btn');
    const itemName = item.dataset.name;
    const itemPrice = parseInt(item.dataset.price);

    addBtn.addEventListener('click', () => {
        if (!orders[itemName]) {
            orders[itemName] = { quantity: 0, price: itemPrice };
        }
        orders[itemName].quantity++;
        updateOrder();
    });

    removeBtn.addEventListener('click', () => {
        if (orders[itemName] && orders[itemName].quantity > 0) {
            orders[itemName].quantity--;
            if (orders[itemName].quantity === 0) {
                delete orders[itemName];
            }
        }
        updateOrder();
    });
});

// Update the order list and total
function updateOrder() {
    orderList.innerHTML = '';
    total = 0;
    let itemCount = 0;

    for (const [name, details] of Object.entries(orders)) {
        orderList.innerHTML += `
            <li>${name} GHS ${details.price} (x${details.quantity}) - GHS ${details.quantity * details.price}</li>
        `;
        total += details.quantity * details.price;
        itemCount += details.quantity;
    }

    totalElement.textContent = total;
    document.getElementById('item-count').textContent = itemCount;
}

// Show checkout section when 'View Checkout' button is clicked
checkoutBtn.addEventListener('click', () => {
    if (Object.keys(orders).length > 0) {
        checkoutSection.classList.remove('hidden'); // Show checkout section
    } else {
        alert('Please add items to your order!');
    }
});

// Show customer details modal when Proceed button is clicked
proceedBtn.addEventListener('click', () => {
    customerDetailsModal.classList.remove('hidden'); // Show modal
});

// Close modal when user clicks on the close button
closeModalBtn.addEventListener('click', () => {
    customerDetailsModal.classList.add('hidden'); // Hide modal
});

// Event listener to toggle delivery address field visibility
pickupDelivery.addEventListener("change", () => {
    if (pickupDelivery.value === "delivery") {
        deliveryAddressField.classList.add("visible");
    } else {
        deliveryAddressField.classList.remove("visible");
    }
});

// Handle customer details form submission and initiate Paystack payment
customerDetailsForm.addEventListener('submit', e => {
    e.preventDefault();

    const customerName = document.getElementById('customer-name').value;
    const customerPhone = document.getElementById('customer-phone').value;
    const customerEmail = document.getElementById('customer-email').value;
    const pickupOrDelivery = pickupDelivery.value;
    const deliveryAddress = pickupOrDelivery === 'delivery' ? document.getElementById('delivery-address').value : '';

    customerDetailsModal.classList.add('hidden'); // Hide modal after form submission

    const handler = PaystackPop.setup({
        key: 'pk_test_112a19f8ae988db1be016b0323b0e4fe95783fe8',
        email: customerEmail,
        amount: total * 100, // Amount in kobo
        currency: 'GHS',
        metadata: {
            custom_fields: [
                { display_name: "Customer Name", value: customerName },
                { display_name: "Phone Number", value: customerPhone },
                { display_name: "Pickup or Delivery", value: pickupOrDelivery },
                { display_name: "Delivery Address", value: deliveryAddress }
            ]
        },
        callback: response => {
            alert(`Payment Successful! Reference: ${response.reference}`);
            // Send order details to the server for processing
            sendOrderToServer({
                customerName,
                customerPhone,
                customerEmail,
                pickupOrDelivery,
                deliveryAddress,
                orders,
                total,
                reference: response.reference
            });
        },
        onClose: () => {
            alert('Transaction canceled.');
        }
    });

    handler.openIframe();
});

// Send order details to the server
function sendOrderToServer(orderData) {
    fetch('process_order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(orderData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Order processed successfully!');
        } else {
            alert('Failed to process order. Please contact support.');
        }
    })
    .catch(error => console.error('Error:', error));
}
