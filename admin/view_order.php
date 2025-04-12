<?php include('partials/menu.php');

$order_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if(!$order_id) {
    header("Location: order-manage.php");
    exit();
}

$sql = "SELECT h.*, 
               GROUP_CONCAT(i.food_id) AS food_ids,
               GROUP_CONCAT(f.title) AS food_titles,
               GROUP_CONCAT(i.quantity) AS quantities,
               GROUP_CONCAT(i.price) AS prices
        FROM tbl_order_header h
        JOIN tbl_order_items i ON h.id = i.order_id
        JOIN food_food f ON i.food_id = f.id
        WHERE h.id = ?
        GROUP BY h.id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

?>

<div class="main-content">
    <div class="wrapper">
        <br>
        <h1 class="text-margin">Order Details (#<?= $order_id ?>)</h1>

        <br>
        
        <div class="text-margin">
            <div class="order-info">
                <h3>Customer Information</h3>
                <p>Name: <?= htmlspecialchars($order['user_name']) ?></p>
                <p>Email: <?= htmlspecialchars($order['user_email']) ?></p>
                <p>Phone: <?= htmlspecialchars($order['user_contact']) ?></p>
                <p>Address: <?= htmlspecialchars($order['user_address']) ?></p>
            </div>
            <br>
            <div class="order-items">
                <h3>Order Items</h3>
                <table class="tbl-items">
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                    <?php
                    $foods = explode(',', $order['food_titles']);
                    $prices = explode(',', $order['prices']);
                    $quantities = explode(',', $order['quantities']);
                    
                    foreach($foods as $index => $food): 
                        $total = $prices[$index] * $quantities[$index];
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($food) ?></td>
                        <td>RM <?= number_format($prices[$index], 2) ?></td>
                        <td><?= $quantities[$index] ?></td>
                        <td>RM <?= number_format($total, 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                
                <div class="order-total">
                    <p>Grand Total: RM <?= number_format($order['total_price'], 2) ?></p>
                </div>
                <br><br>
            </div>
            <a href="order-manage.php" class="btn-secondary">← Back to Orders</a><br><br>
        </div>
        
        
    </div>
</div>

<?php include('partials/footer.php'); ?>