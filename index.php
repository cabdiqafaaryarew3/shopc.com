<?php
session_start();
include 'config.php'; // Ku xir database-kaaga

// Hubi haddii user-ku logged in yahay
if (!isset($_SESSION['user_id'])) {
    header("Location: sign.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$sql_user = "SELECT name FROM users WHERE id = $user_id";
$result_user = $conn->query($sql_user);
$user = $result_user->fetch_assoc();

// Fetch products
$sql_products = "SELECT * FROM products ORDER BY id DESC";
$result_products = $conn->query($sql_products);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ShopC - Home</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #387, #345);
    margin:0; padding:0;
    color:white;
    text-align:center;
}
header {
    padding:20px;
}
header h1 {
    font-size:2.5em;
}
.user-name {
    font-size:1.2em;
    margin-top:5px;
    color:#ffcc00;
}
.logout-btn {
    background:#ff4444; color:white;
    padding:10px 20px;
    border:none; border-radius:10px;
    cursor:pointer; margin-top:10px;
}
.logout-btn:hover { background:#cc0000; }
.srn input {
    padding:12px;
    width:90%;
    border-radius:10pc;
    border:none;
    outline:none;
    font-size:16px;
    margin-bottom:20px;
}
section {
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    gap:20px;
    margin:20px;
}
.product-card {
    background:white;
    color:black;
    width:220px;
    text-align:center;
    border-radius:19px;
    padding:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.2);
    transition: transform 0.3s;
}
.product-card:hover { transform:scale(1.05); }
.product-card img { width:130px; border-radius:10px; }
.stars { color:gold; font-size:18px; margin:5px 0; }
.price { font-weight:bold; }
.btns { display:flex; flex-direction:column; align-items:center; gap:8px; margin-top:10px; }
.download-btn, .whatsapp-btn {
    padding:10px; border-radius:10pc; width:150px; border:none;
    color:white; cursor:pointer; display:flex; align-items:center; justify-content:center;
    gap:8px; text-decoration:none; font-size:14px;
}
.download-btn { background:#000; }
.download-btn:hover { background:#333; }
.whatsapp-btn { background:#25D366; }
.whatsapp-btn:hover { background:#1DA851; }
footer { text-align:center; margin-top:36px; }
</style>
</head>
<body>

<header>
    <h1>🛍️ ShopC</h1>
    <div class="user-name">👋 <?php echo htmlspecialchars($user['name']); ?></div>
    <form action="logout.php" method="post">
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</header>

<div class="srn">
    <input type="text" id="search" placeholder="Search products by name or price...">
</div>

<section id="product-list">
<?php
if ($result_products->num_rows > 0) {
    while($row = $result_products->fetch_assoc()) {
        echo '<div class="product-card">';
        echo '<img src="'.htmlspecialchars($row['image']).'" alt="">';
        echo '<h4 class="name">'.htmlspecialchars($row['name']).'</h4>';
        echo '<div class="stars">★★★★★</div>';
        echo '<p class="price">Price = '.htmlspecialchars($row['price']).' birr</p>';
        echo '<div class="btns">';
        echo '<a href="'.htmlspecialchars($row['image']).'" download class="download-btn"><i class="fa fa-download"></i> Download</a>';
        echo '<a href="https://wa.me/+251903253273?text=I%20want%20to%20order%20'.urlencode($row['name']).'" target="_blank" class="whatsapp-btn"><i class="fa-brands fa-whatsapp"></i> Order Delivery</a>';
        echo '</div></div>';
    }
} else {
    echo '<p>No products available.</p>';
}
?>
</section>

<script>
const searchInput = document.getElementById('search');
const products = document.querySelectorAll('.product-card');

searchInput.addEventListener('input', () => {
    const query = searchInput.value.toLowerCase();
    products.forEach(product => {
        const name = product.querySelector('.name').textContent.toLowerCase();
        const price = product.querySelector('.price').textContent.toLowerCase();
        if(name.includes(query) || price.includes(query)){
            product.style.display='block';
        } else {
            product.style.display='none';
        }
    });
});
</script>

<footer>
&copy; 2025 ShopC | All Rights Reserved
</footer>
</body>
</html>
