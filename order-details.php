<?php
session_start();
include('partials-front/menu.php');

// Only check if order ID exists
if (empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$order_id = (int)$_GET['id'];

// Fetch order header (NO user_id check)
$header_sql = "SELECT * FROM tbl_order_header WHERE id = ?";
$header_stmt = $conn->prepare($header_sql);
$header_stmt->bind_param("i", $order_id);
$header_stmt->execute();
$order_header = $header_stmt->get_result()->fetch_assoc();

// Redirect if order doesn't exist
if (!$order_header) {
    header("Location: order-history.php");
    exit();
}

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

<!-- Updated styling to accommodate customer information -->
<style>
    .order-history-section {
        padding: 60px 0;
    }
    
    .order-history-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .order-history-title {
        text-align: center;
        margin-bottom: 40px;
        color: #333;
    }
    
    .order-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        overflow: hidden;
    }
    
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
    }
    
    .order-id {
        font-weight: bold;
        color: #007bff;
    }
    
    .order-date {
        color: #666;
    }
    
    .order-status {
        padding: 5px 10px;
        border-radius: 4px;
        font-weight: bold;
        font-size: 0.9rem;
    }
    
    .status-ordered {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-processing {
        background: #cce5ff;
        color: #004085;
    }
    
    .status-delivered {
        background: #d4edda;
        color: #155724;
    }
    
    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }
    
    .order-details-container {
        display: flex;
        flex-wrap: wrap;
    }
    
    .order-summary, .customer-info {
        padding: 20px;
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
        font-size: 1.2rem;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
        color: #333;
    }
    
    .order-items {
        list-style: none;
        padding: 0;
        margin: 0 0 15px 0;
    }
    
    .order-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    
    .order-total {
        font-weight: bold;
        text-align: right;
        padding-top: 10px;
        border-top: 1px solid #eee;
    }
    
    .customer-info p {
        margin: 10px 0;
        line-height: 1.6;
    }
    
    .customer-info strong {
        color: #555;
    }
    
    .action-buttons {
        text-align: center;
        margin-top: 20px;
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
    }

    .btn-primary {
        background: #007bff;
        color: white;
        border: 1px solid #007bff;
    }

    .btn-primary:hover {
        background: #0069d9;
        border-color: #0062cc;
    }
    
    @media (max-width: 768px) {
        .order-details-container {
            flex-direction: column;
        }
        
        .order-summary {
            border-right: none;
            border-bottom: 1px solid #eee;
        }
    }
</style>

<section class="order-history-section">
    <div class="order-history-container">
        <h2 class="order-history-title">Order Details #<?= $order_header['id'] ?></h2>
        
        <div class="order-card">
            <div class="order-header">
                <div>
                    <span class="order-id">Order #<?= $order_header['id'] ?></span>
                    <span class="order-date"><?= date('F j, Y', strtotime($order_header['order_date'])) ?></span>
                </div>
                <span class="order-status status-<?= strtolower($order_header['status']) ?>">
                    <?= htmlspecialchars($order_header['status']) ?>
                </span>
            </div>
            
            <div class="order-details-container">
                <div class="order-summary">
                    <h3 class="section-title">Items Ordered</h3>
                    <ul class="order-items">
                        <?php foreach($order_items as $item): ?>
                        <li class="order-item">
                            <span><?= htmlspecialchars($item['food_name']) ?> x<?= $item['quantity'] ?></span>
                            <span>RM <?= number_format($item['total'], 2) ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="order-total">
                        Total: RM <?= number_format($order_header['total_price'], 2) ?>
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
        </div>
        
        <div class="action-buttons">
            <a href="order-history.php" class="btn btn-primary">Back to Order History</a>
        </div>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>