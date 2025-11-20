<?php
session_start();
include 'config.php';

// Only admin access (assume user id = 1)
if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1){
    header("Location: login.php");
    exit();
}

// Add product
if(isset($_POST['add_product'])){
    $name = $conn->real_escape_string($_POST['name']);
    $price = $conn->real_escape_string($_POST['price']);
    $age_range = $conn->real_escape_string($_POST['age_range']);
    $whatsapp_link = $conn->real_escape_string($_POST['whatsapp_link']);

    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $file_name = time().'_'.$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/'.$file_name);
    } else {
        $file_name = '';
    }

    $conn->query("INSERT INTO products (name, price, age_range, whatsapp_link, image) VALUES ('$name','$price','$age_range','$whatsapp_link','$file_name')");
}

// Delete product
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM products WHERE id=$id");
}

// Edit product
if(isset($_POST['edit_product'])){
    $id = (int)$_POST['id'];
    $name = $conn->real_escape_string($_POST['name']);
    $price = $conn->real_escape_string($_POST['price']);
    $age_range = $conn->real_escape_string($_POST['age_range']);
    $whatsapp_link = $conn->real_escape_string($_POST['whatsapp_link']);

    // Check if new image uploaded
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $file_name = time().'_'.$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/'.$file_name);
        $conn->query("UPDATE products SET name='$name', price='$price', age_range='$age_range', whatsapp_link='$whatsapp_link', image='$file_name' WHERE id=$id");
    } else {
        $conn->query("UPDATE products SET name='$name', price='$price', age_range='$age_range', whatsapp_link='$whatsapp_link' WHERE id=$id");
    }
}

$products_result = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - ShopC</title>
<style>
body{font-family:Arial;background:#f0f0f0;color:#333;padding:20px;}
h1{text-align:center;}
form{background:#fff;padding:20px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.2);max-width:400px;margin:20px auto;}
form input, form button{width:100%;padding:10px;margin:10px 0;border-radius:10px;border:1px solid #ccc;}
form button{background:#4c75af;color:white;border:none;cursor:pointer;}
form button:hover{background:#345c8c;}
table{width:100%;margin-top:20px;border-collapse:collapse;}
table, th, td{border:1px solid #ccc;}
th, td{padding:10px;text-align:center;}
a.delete{color:red;text-decoration:none;}
a.delete:hover{text-decoration:underline;}
.logout-btn{background:#ff4444;color:white;padding:8px 15px;border:none;border-radius:10px;cursor:pointer;}
.logout-btn:hover{background:#cc0000;}
.edit-btn{background:#ffaa00;color:white;padding:5px 10px;border:none;border-radius:5px;cursor:pointer;}
.edit-btn:hover{background:#cc8800;}
.header{display:flex;justify-content:space-between;align-items:center;}
</style>
</head>
<body>

<div class="header">
<h1>🛒 ShopC Admin Panel</h1>
<form action="logout.php" method="post">
    <button class="logout-btn">Logout</button>
</form>
</div>

<h2>Add New Product</h2>
<form action="" method="post" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Product Name" required>
    <input type="text" name="price" placeholder="Price" required>
    <input type="text" name="age_range" placeholder="Age Range" required>
    <input type="text" name="whatsapp_link" placeholder="WhatsApp Link" required>
    <input type="file" name="image" accept="image/*" required>
    <button type="submit" name="add_product">Add Product</button>
</form>

<h2>Existing Products</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Age</th>
        <th>Image</th>
        <th>WhatsApp</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $products_result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo $row['price']; ?></td>
        <td><?php echo $row['age_range']; ?></td>
        <td><img src="uploads/<?php echo $row['image']; ?>" width="50"></td>
        <td><a href="<?php echo $row['whatsapp_link']; ?>" target="_blank">Order</a></td>
        <td>
            <!-- Edit form -->
            <form action="" method="post" enctype="multipart/form-data" style="display:inline-block;">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                <input type="text" name="price" value="<?php echo $row['price']; ?>" required>
                <input type="text" name="age_range" value="<?php echo $row['age_range']; ?>" required>
                <input type="text" name="whatsapp_link" value="<?php echo $row['whatsapp_link']; ?>" required>
                <input type="file" name="image">
                <button type="submit" name="edit_product" class="edit-btn">Edit</button>
            </form>
            <a href="?delete=<?php echo $row['id']; ?>" class="delete">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
