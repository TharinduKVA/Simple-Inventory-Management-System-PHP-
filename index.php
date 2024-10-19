<?php include 'db.php'; ?>

<?php

$search_query = "";
if (isset($_POST['search'])) {
    $search_query = $_POST['search_query'];
}


$expiry_threshold = 7; 

?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory Management System</title>
    <style>
        .expired { color: red; }
        .about-to-expire { color: orange; }
        .good {color: blue;}
    </style>
</head>
<body>

<h2>Inventory Management System</h2>


<form method="POST" action="">
    <label>Search by Item Name or Supplier:</label>
    <input type="text" name="search_query" value="<?php echo $search_query; ?>" placeholder="Search...">
    <input type="submit" name="search" value="Search">
</form>


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
            <th>Expiry Notification</th>
        </tr>
    </thead>
    <tbody>
        <?php
        
        if ($search_query) {
            $sql = "SELECT * FROM inventory_items 
                    WHERE item_name LIKE '%$search_query%' 
                    OR supplier LIKE '%$search_query%'";
        } else {
            $sql = "SELECT * FROM inventory_items";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $expiry_date = $row['expiry_date'];
                $current_date = date('Y-m-d');
                $days_until_expiry = (strtotime($expiry_date) - strtotime($current_date)) / (60 * 60 * 24); 

               
                if ($days_until_expiry <= 0) {
                    $expiry_class = 'expired';
                    $expiry_notification = 'Expired';
                } elseif ($days_until_expiry <= $expiry_threshold) {
                    $expiry_class = 'about-to-expire';
                    $expiry_notification = 'Expiring soon';
                } else {
                    $expiry_class = 'good';
                    $expiry_notification = 'Good to use';
                }

                echo "<tr class='$expiry_class'>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['item_name'] . "</td>";
                echo "<td>" . $row['quantity'] . "</td>";
                echo "<td>" . $row['price'] . "</td>";
                echo "<td>" . $row['supplier'] . "</td>";
                echo "<td>" . $row['expiry_date'] . "</td>";
                echo "<td>$expiry_notification</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No items found</td></tr>";
        }

        $conn->close();
        ?>
    </tbody>
</table>
<br>
<form action="create.php" method="get">
    <button type="submit">Add New Items</button>
</form>

</body>
</html>
