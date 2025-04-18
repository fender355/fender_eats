<?php 
session_start();
include('partials-front/menu.php');
?>

<style>
    /* Main Container Styling */
    .cart-container {
        width: 90%;
        max-width: 1200px;
        margin: 30px auto;
        padding: 20px;
        box-sizing: border-box;
    }
    
    /* Table Styling */
    .cart-table {
        width: 100%;
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 16px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        table-layout: fixed; /* Ensures equal column width */
    }
    
    .cart-table thead tr {
        background-color: #ff6b6b;
        color: white;
        text-align: center;
    }
    
    .cart-table th,
    .cart-table td {
        padding: 15px;
        text-align: center;
        border: 1px solid #ddd;
    }
    
    .cart-table tbody tr {
        border-bottom: 1px solid #ddd;
    }
    
    .cart-table tbody tr:nth-of-type(even) {
        background-color: #f9f9f9;
    }
    
    .cart-table tbody tr:hover {
        background-color: #f1f1f1;
    }
    
    .cart-table tfoot {
        font-weight: bold;
        background-color: #f8f8f8;
    }
    
    /* Quantity Form Styling */
    .quantity-form {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .quantity-input {
        width: 50px;
        padding: 8px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-right: 5px;
    }
    
    /* Button Styling */
    .btn-update {
        background: #4CAF50;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s;
    }
    
    .btn-update:hover {
        background: #45a049;
    }
    
    .btn-remove {
        display: inline-block;
        background: #e74c3c;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 4px;
        text-decoration: none;
        transition: background 0.3s;
    }
    
    .btn-remove:hover {
        background: #c0392b;
    }
    
    /* Cart Actions Styling */
    .cart-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
    }
    
    .btn-continue, 
    .btn-checkout {
        padding: 12px 25px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-continue {
        background: #f8f8f8;
        color: #333;
        border: 1px solid #ddd;
    }
    
    .btn-continue:hover {
        background: #e9e9e9;
    }
    
    .btn-checkout {
        background: #ff6b6b;
        color: white;
    }
    
    .btn-checkout:hover {
        background: #ff5252;
    }
    
    /* Empty Cart Styling */
    .empty-cart {
        text-align: center;
        padding: 50px 0;
        background: #f9f9f9;
        border-radius: 8px;
        margin-top: 30px;
    }
    
    .empty-cart p {
        font-size: 18px;
        margin-bottom: 20px;
        color: #666;
    }
    
    .btn-primary {
        display: inline-block;
        padding: 12px 25px;
        background: #ff6b6b;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        transition: background 0.3s;
    }
    
    .btn-primary:hover {
        background: #ff5252;
    }
</style>

<section class="food-menu">
    <div class="cart-container">
        <h2 class="text-center">Your Cart</h2>

        <?php
        if(isset($_SESSION['cart_message'])) {
            echo '<div class="alert">'.$_SESSION['cart_message'].'</div>';
            unset($_SESSION['cart_message']);
        }
        ?>

        <?php if(!empty($_SESSION['cart'])): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th style="width: 30%;">Item</th>
                        <th style="width: 15%;">Price</th>
                        <th style="width: 20%;">Quantity</th>
                        <th style="width: 15%;">Total</th>
                        <th style="width: 20%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach($_SESSION['cart'] as $key => $item):
                        $itemTotal = $item['price'] * $item['quantity'];
                        $total += $itemTotal;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($item['title']) ?></td>
                        <td>RM <?= number_format($item['price'], 2) ?></td>
                        <td>
                            <form method="POST" action="update_cart_item.php" class="quantity-form">
                                <input type="hidden" name="item_key" value="<?= $key ?>">
                                <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="quantity-input">
                                <button type="submit" class="btn-update">Update</button>
                            </form>
                        </td>
                        <td>RM <?= number_format($itemTotal, 2) ?></td>
                        <td>
                            <a href="remove_from_cart.php?key=<?= $key ?>" class="btn-remove">Remove</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;">Grand Total:</td>
                        <td colspan="2">RM <?= number_format($total, 2) ?></td>
                    </tr>
                </tfoot>
            </table>
            
            <div class="cart-actions">
                <a href="<?= SITEURL ?>" class="btn-continue">Continue Shopping</a>
                <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <p>Your cart is empty</p>
                <a href="<?= SITEURL ?>" class="btn-primary">Browse Foods</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>