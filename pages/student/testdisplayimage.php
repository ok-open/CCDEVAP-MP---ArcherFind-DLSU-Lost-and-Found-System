<?php
// Force PHP to display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include your database connection
require_once '../../db.php';

// Get the item_id from the URL, default to 1 if it's not set
$item_id = isset($_GET['item_id']) ? (int)$_GET['item_id'] : 1;

// Calculate the next item ID for the button
$next_item_id = $item_id + 1;

try {
    // 1. Fetch the item details
    $item_stmt = $conn->prepare("SELECT name, description FROM items WHERE item_id = :item_id");
    $item_stmt->execute(['item_id' => $item_id]);
    $item = $item_stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Fetch all images associated with this item
    $img_stmt = $conn->prepare("SELECT img_filepath FROM items_images WHERE item_id = :item_id");
    $img_stmt->execute(['item_id' => $item_id]);
    $images = $img_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Image Retrieval Test - Item #<?php echo $item_id; ?></title>
</head>
<body>

    <div style="background: #f0f0f0; padding: 15px; margin-bottom: 20px; border-bottom: 1px solid #ccc;">
        <p style="margin: 0 0 10px 0;">Currently viewing Item ID: <strong><?php echo $item_id; ?></strong></p>
        
        <a href="testdisplayimage.php?item_id=<?php echo $next_item_id; ?>">
            <button type="button" style="padding: 8px 16px; cursor: pointer;">Next Item (+1) &gt;</button>
        </a>
    </div>

    <?php if ($item): ?>
        <h1>Item: <?php echo htmlspecialchars($item['name']); ?></h1>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($item['description']); ?></p>
        
        <hr>

        <h3>Retrieved Images:</h3>
        <?php if (!empty($images)): ?>
            <?php foreach ($images as $img): ?>
                <div style="margin-bottom: 20px;">
                    <p>Filepath: <code><?php echo htmlspecialchars($img['img_filepath']); ?></code></p>
                    <img src="<?php echo htmlspecialchars($img['img_filepath']); ?>" alt="Item Image" style="max-width: 300px; height: auto; border: 1px solid #ccc;">
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: orange;">No images found in the database for this specific item.</p>
        <?php endif; ?>

    <?php else: ?>
        <div style="padding: 20px; background: #fff3cd; color: #856404; border: 1px solid #ffeeba;">
            <h3>Item ID <?php echo $item_id; ?> does not exist.</h3>
            <p>You may have skipped an ID in your database table auto-increments. Click "Next Item" again to keep hunting for the next valid ID.</p>
        </div>
    <?php endif; ?>

</body>
</html>