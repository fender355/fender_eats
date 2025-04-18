<?php
session_start();
include('partials-front/menu.php');

if (empty($_GET['id']) || !isset($_SESSION['order_success'])) {
    header("Location: index.php");
    exit();
}

$order_id = (int)$_GET['id'];
unset($_SESSION['order_success']);

// Fetch order header
$header_sql = "SELECT * FROM tbl_order_header WHERE id = ?";
$header_stmt = $conn->prepare($header_sql);
$header_stmt->bind_param("i", $order_id);
$header_stmt->execute();
$order_header = $header_stmt->get_result()->fetch_assoc();

// Fetch order items
$items_sql = "SELECT oi.*, f.title as food_name 
              FROM tbl_order_items oi
              JOIN food_food f ON oi.food_id = f.id
              WHERE oi.order_id = ?";
$items_stmt = $conn->prepare($items_sql);
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$order_items = $items_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<style>
    .order-success-section {
        padding: 60px 0;
        background: #f8f9fa;
    }
    
    .order-success-container {
        max-width: 1000px;
        margin: 0 auto;
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .order-success-header {
        background: #28a745;
        color: white;
        padding: 30px;
        text-align: center;
    }
    
    .order-success-header h1 {
        margin: 0;
        font-size: 2.2rem;
    }
    
    .order-success-header .order-id {
        font-size: 1.5rem;
        margin: 10px 0;
        display: block;
    }
    
    .order-success-header .status-badge {
        display: inline-block;
        background: white;
        color: #28a745;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: bold;
        margin-top: 10px;
    }
    
    .order-details {
        display: flex;
        flex-wrap: wrap;
    }
    
    .order-summary, .customer-info {
        padding: 30px;
        box-sizing: border-box;
    }
    
    .order-summary {
        flex: 2;
        border-right: 1px solid #eee;
    }
    
    .customer-info {
        flex: 1;
        background: #f9f9f9;
    }
    
    .section-title {
        font-size: 1.3rem;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
        color: #333;
    }
    
    .order-items {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .order-item {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .order-item:last-child {
        border-bottom: none;
    }
    
    .order-total {
        font-size: 1.2rem;
        font-weight: bold;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #eee;
        display: flex;
        justify-content: space-between;
    }
    
    .customer-info p {
        margin: 10px 0;
        line-height: 1.6;
    }
    
    .customer-info strong {
        color: #555;
    }
    
    .action-buttons {
        padding: 0 30px 30px;
        display: flex;
        gap: 15px;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 25px;
        border-radius: 4px;
        font-size: 16px;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s;
        height: 48px; /* Fixed height */
        box-sizing: border-box; /* Include border in height calculation */
        border: 1px solid transparent; /* Transparent border by default */
    }

    .btn-primary {
        background: #007bff;
        color: white;
        border-color: #007bff; /* Matching border */
    }

    .btn-primary:hover {
        background: #0069d9;
        border-color: #0062cc;
    }

    .btn-outline {
        background: white;
        color: #007bff;
        border: 1px solid #007bff; /* Explicit border */
        position: relative;
        top: 8px; /* Adjust this value as needed */
    }

    .btn-outline:hover {
        background: #f8f9fa;
    }
    
    @media (max-width: 768px) {
        .order-details {
            flex-direction: column;
        }
        
        .order-summary {
            border-right: none;
            border-bottom: 1px solid #eee;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<section class="order-success-section">
    <div class="order-success-container">
        <div class="order-success-header">
            <h1>Order Placed Successfully!</h1>
            <span class="order-id">Order #<?= $order_id ?></span>
            <span class="status-badge"><?= htmlspecialchars($order_header['status']) ?></span>
        </div>
        
        <div class="order-details">
            <div class="order-summary">
                <h3 class="section-title">Order Summary</h3>
                <ul class="order-items">
                    <?php foreach($order_items as $item): ?>
                    <li class="order-item">
                        <span><?= htmlspecialchars($item['food_name']) ?> x<?= $item['quantity'] ?></span>
                        <span>RM <?= number_format($item['total'], 2) ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <div class="order-total">
                    <span>Total:</span>
                    <span>RM <?= number_format($order_header['total_price'], 2) ?></span>
                </div>
            </div>
            
            <div class="customer-info">
                <h3 class="section-title">Customer Information</h3>
                <p><strong>Name:</strong> <?= htmlspecialchars($order_header['user_name']) ?></p>
                <p><strong>Contact:</strong> <?= htmlspecialchars($order_header['user_contact']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($order_header['user_email']) ?></p>
                <p><strong>Delivery Address:</strong> <?= htmlspecialchars($order_header['user_address']) ?></p>
                <p><strong>Order Date:</strong> <?= date('F j, Y g:i a', strtotime($order_header['order_date'])) ?></p>
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="index.php" class="btn btn-primary">Back to Home</a>
            <a href="order-history.php" class="btn btn-outline">View Order History</a>
        </div>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>