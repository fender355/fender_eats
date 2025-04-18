<?php
session_start();
include('partials-front/menu.php');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user'];

$sql = "SELECT * FROM tbl_order_header ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

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
    
    .order-summary {
        padding: 20px;
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
    
    .order-actions {
        padding: 0 20px 15px;
        text-align: right;
    }
    
    .btn-view {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
    }
    
    .btn-view:hover {
        text-decoration: underline;
    }
    
    .no-orders {
        text-align: center;
        padding: 40px;
        color: #666;
    }
</style>

<section class="order-history-section">
    <div class="container">
        <h2 class="order-history-title">Your Order History</h2>
        
        <?php if (empty($orders)): ?>
            <div class="no-orders">
                <p>You haven't placed any orders yet.</p>
                <a href="foods.php" class="btn btn-primary">Browse Foods</a>
            </div>
        <?php else: ?>
            <?php foreach($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <span class="order-id">Order #<?= $order['id'] ?></span>
                            <span class="order-date"><?= date('F j, Y', strtotime($order['order_date'])) ?></span>
                        </div>
                        <span class="order-status status-<?= strtolower($order['status']) ?>">
                            <?= htmlspecialchars($order['status']) ?>
                        </span>
                    </div>
                    
                    <div class="order-summary">
                        <ul class="order-items">
                            <?php 
                            // Fetch items for this order
                            $items_sql = "SELECT oi.*, f.title as food_name 
                                        FROM tbl_order_items oi
                                        JOIN food_food f ON oi.food_id = f.id
                                        WHERE oi.order_id = ?";
                            $items_stmt = $conn->prepare($items_sql);
                            $items_stmt->bind_param("i", $order['id']);
                            $items_stmt->execute();
                            $items = $items_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                            
                            foreach($items as $item):
                            ?>
                            <li class="order-item">
                                <span><?= htmlspecialchars($item['food_name']) ?> x<?= $item['quantity'] ?></span>
                                <span>RM <?= number_format($item['total'], 2) ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="order-total">
                            Total: RM <?= number_format($order['total_price'], 2) ?>
                        </div>
                    </div>
                    
                    <div class="order-actions">
                        <a href="order-details.php?id=<?= $order['id'] ?>" class="btn-view">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>