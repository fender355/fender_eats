<?php
session_start();
include('partials-front/menu.php');

// Validate cart contents before showing checkout page
if (!empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) {
        return isset($item['id']) && is_numeric($item['id']) && $item['id'] > 0;
    });
    
    if (empty($_SESSION['cart'])) {
        $_SESSION['cart_message'] = "Your cart contains invalid items and was cleared!";
        header("Location: cart.php");
        exit();
    }
}

if(empty($_SESSION['cart'])) {
    $_SESSION['cart_message'] = "Your cart is empty!";
    header("Location: cart.php");
    exit();
}

// Get user data if logged in
$userData = [];
if(isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $sql = "SELECT user_name, user_email, user_mobile FROM user_acc WHERE user_acc_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
}

// Validate mobile number if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['contact'])) {
        $contact = $_POST['contact'];
        if (!preg_match('/^[0-9\-]+$/', $contact)) {
            $_SESSION['checkout_error'] = "Mobile number can only contain numbers and hyphens.";
            header("Location: checkout.php");
            exit();
        }
    }
}
?>

<style>
    /* Checkout Section Styling */
    .checkout-section {
        padding: 40px 0;
    }
    
    .checkout-wrapper {
        display: flex;
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    /* Order Summary Styling */
    .order-summary {
        flex: 1;
        background: #f9f9f9;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
    
    .order-summary h3 {
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
        color: #333;
    }
    
    .order-summary ul {
        list-style: none;
        padding: 0;
        margin: 0 0 20px 0;
    }
    
    .order-summary li {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #eee;
    }
    
    .order-summary .total {
        display: flex;
        justify-content: space-between;
        font-weight: bold;
        font-size: 18px;
        padding: 15px 0;
        border-top: 2px solid #ddd;
    }
    
    /* Checkout Form Styling */
    .checkout-form {
        flex: 1;
        background: #fff;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
    
    .checkout-form h3 {
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
        color: #333;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #555;
    }
    
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
        box-sizing: border-box;
    }
    
    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }
    
    .btn-submit {
        background: #ff6b6b;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
        width: 100%;
    }
    
    .btn-submit:hover {
        background: #ff5252;
    }
    
    .error-message {
        color: #dc3545;
        margin-top: 5px;
        font-size: 14px;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .checkout-wrapper {
            flex-direction: column;
        }
        
        .order-summary,
        .checkout-form {
            width: 100%;
        }
    }
</style>

<section class="checkout-section">
    <div class="container">
        <h2 class="text-center">Checkout</h2>
        
        <?php if(isset($_SESSION['checkout_error'])): ?>
            <div class="alert alert-danger text-center">
                <?= $_SESSION['checkout_error']; ?>
                <?php unset($_SESSION['checkout_error']); ?>
            </div>
        <?php endif; ?>
        
        <div class="checkout-wrapper">
            <div class="order-summary">
                <h3>Order Summary</h3>
                <ul>
                    <?php 
                    $total = 0;
                    foreach($_SESSION['cart'] as $item):
                        $itemTotal = $item['price'] * $item['quantity'];
                        $total += $itemTotal;
                    ?>
                    <li>
                        <span><?= htmlspecialchars($item['title']) ?> x<?= $item['quantity'] ?></span>
                        <span>RM <?= number_format($itemTotal, 2) ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <div class="total">
                    <span>Total:</span>
                    <span>RM <?= number_format($total, 2) ?></span>
                </div>
            </div>

            <form class="checkout-form" action="place_order.php" method="POST" onsubmit="return validateMobileNumber()">
                <h3>Delivery Information</h3>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" required 
                           value="<?= isset($userData['user_name']) ? htmlspecialchars($userData['user_name']) : '' ?>">
                </div>
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="tel" name="contact" id="contact" required
                           value="<?= isset($userData['user_mobile']) ? htmlspecialchars($userData['user_mobile']) : '' ?>"
                           pattern="[0-9\-]+" title="Only numbers and hyphens are allowed">
                    <div id="contactError" class="error-message"></div>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required
                           value="<?= isset($userData['user_email']) ? htmlspecialchars($userData['user_email']) : '' ?>">
                </div>
                <div class="form-group">
                    <label>Delivery Address</label>
                    <textarea name="address" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn-submit">Place Order</button>
            </form>
        </div>
    </div>
</section>

<script>
    function validateMobileNumber() {
        const contactInput = document.getElementById('contact');
        const contactError = document.getElementById('contactError');
        const contactValue = contactInput.value;
        
        // Regular expression to allow only numbers and hyphens
        const regex = /^[0-9\-]+$/;
        
        if (!regex.test(contactValue)) {
            contactError.textContent = "Mobile number can only contain numbers and hyphens.";
            return false;
        }
        
        contactError.textContent = "";
        return true;
    }
    
    // Add event listener for real-time validation
    document.getElementById('contact').addEventListener('input', function() {
        validateMobileNumber();
    });
</script>

<?php include('partials-front/footer.php'); ?>