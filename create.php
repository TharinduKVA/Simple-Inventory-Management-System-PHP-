<?php include 'db.php'; ?>

<?php

$message = "";
$update_mode = false;
$id_to_update = "";
$item_name = "";
$quantity = "";
$price = "";
$supplier = "";
$expiry_date = "";

if (isset($_POST['submit'])) {
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $supplier = $_POST['supplier'];
    $expiry_date = $_POST['expiry_date'];

    $sql = "INSERT INTO inventory_items (item_name, quantity, price, supplier, expiry_date) 
            VALUES ('$item_name', '$quantity', '$price', '$supplier', '$expiry_date')";

    if ($conn->query($sql) === TRUE) {
        $message = "<p style='color:green;'>New item added successfully!</p>";
    } else {
        $message = "<p style='color:red;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}


if (isset($_GET['edit'])) {
    $id_to_update = $_GET['edit'];

    
    $sql = "SELECT * FROM inventory_items WHERE id = $id_to_update";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $update_row = $result->fetch_assoc();
        $item_name = $update_row['item_name'];
        $quantity = $update_row['quantity'];
        $price = $update_row['price'];
        $supplier = $update_row['supplier'];
        $expiry_date = $update_row['expiry_date'];
        $update_mode = true; 
    }
}


if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $supplier = $_POST['supplier'];
    $expiry_date = $_POST['expiry_date'];

    $sql = "UPDATE inventory_items SET 
            item_name = '$item_name', 
            quantity = '$quantity', 
            price = '$price', 
            supplier = '$supplier', 
            expiry_date = '$expiry_date' 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        $message = "<p style='color:green;'>Item updated successfully!</p>";
        $update_mode = false; 
    } else {
        $message = "<p style='color:red;'>Error updating record: " . $conn->error . "</p>";
    }
}


if (isset($_GET['delete'])) {
    $id_to_delete = $_GET['delete'];

    
    $sql = "DELETE FROM inventory_items WHERE id = $id_to_delete";
    if ($conn->query($sql) === TRUE) {
        $message = "<p style='color:green;'>Item deleted successfully!</p>";
    } else {
        $message = "<p style='color:red;'>Error deleting item: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory Management</title>
    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this item?")) {
                window.location.href = 'create.php?delete=' + id;
            }
        }
    </script>
</head>
<body>

<h2>Inventory Management System</h2>


<form method="POST" action="">
    <h3><?php echo $update_mode ? "Update Inventory Item" : "Add New Inventory Item"; ?></h3>
    <table> 
    <tr><td></td></tr><input type="hidden" name="id" value="<?php echo $id_to_update; ?>"> </tr></td>

    <tr><td><label>Item Name:</label></td>
    <td><input type="text" name="item_name" value="<?php echo $item_name; ?>" required></td></tr>

    <tr><td><label>Quantity:</label></td>
    <td><input type="number" name="quantity" value="<?php echo $quantity; ?>" required></td></tr>

    <tr><td><label>Price:</label></td>
    <td><input type="number" step="0.01" name="price" value="<?php echo $price; ?>" required></td></tr>

    <tr><td><label>Supplier:</label></td>
    <td><input type="text" name="supplier" value="<?php echo $supplier; ?>" required></td></tr>

    <tr><td><label>Expiry Date:</label></td>
    <td><input type="date" name="expiry_date" value="<?php echo $expiry_date; ?>" required></td></tr>

    <tr><td><input type="submit" name="<?php echo $update_mode ? 'update' : 'submit'; ?>" value="<?php echo $update_mode ? 'Update Item' : 'Add Item'; ?>"></td></tr>
    </table>
</form>


<?php echo $message; ?>


<h3>Inventory Items</h3>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Supplier</th>
            <th>Expiry Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM inventory_items";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['item_name'] . "</td>";
                echo "<td>" . $row['quantity'] . "</td>";
                echo "<td>" . $row['price'] . "</td>";
                echo "<td>" . $row['supplier'] . "</td>";
                echo "<td>" . $row['expiry_date'] . "</td>";
                echo "<td>
                        <a href='create.php?edit=" . $row['id'] . "'>Edit</a>
                        <button onclick='confirmDelete(" . $row['id'] . ")'>Delete</button>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No items found</td></tr>";
        }

        $conn->close();
        ?>
    </tbody>
</table>
<br>
<form action="index.php" method="get">
    <button type="submit">Back</button>
</form>
</body>
</html>
