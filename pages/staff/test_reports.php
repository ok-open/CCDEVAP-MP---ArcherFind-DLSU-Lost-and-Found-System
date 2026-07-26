<?php
// ==========================================
// 1. DATABASE CONNECTION
// ==========================================
$host = 'localhost';
$db   = 'archerfinddb'; // Replace with your actual DB name
$user = 'root';               // Replace with your DB username
$pass = '';                   // Replace with your DB password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     die("Database connection failed: " . $e->getMessage());
}

// ==========================================
// 2. QUERY TO FETCH DATA WITH GROUPED IMAGES
// ==========================================
$query = "
    SELECT 
        r.report_id,
        r.type,
        r.student_id,
        r.item_id,
        r.item_name,
        r.item_description,
        r.status,
        r.when_found,
        r.when_lost,
        GROUP_CONCAT(ri.img_filepath SEPARATOR ',') AS images
    FROM reports r
    LEFT JOIN reports_images ri ON r.report_id = ri.report_id
    WHERE r.deleted = '0'
    GROUP BY r.report_id
    ORDER BY r.report_id ASC
";

$stmt = $pdo->query($query);
$reports = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report & Image Verification Grid</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f4f6f9; margin: 20px; color: #333; }
        h1 { color: #2c3e50; text-align: center; }
        .grid { display: flex; flex-direction: column; gap: 15px; max-width: 1100px; margin: 0 auto; }
        .card { background: white; border: 1px solid #e1e4e8; border-radius: 8px; padding: 20px; display: flex; gap: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .info { flex: 1; min-width: 300px; }
        .gallery { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; background: #fafafa; border-radius: 6px; padding: 10px; min-width: 250px; }
        .img-container { text-align: center; }
        .thumb { width: 100px; height: 100px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; background: #fff; }
        .img-label { font-size: 10px; color: #777; display: block; margin-top: 4px; word-break: break-all; max-width: 100px; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .claim { background: #e3f2fd; color: #0d47a1; }
        .loss { background: #ffebee; color: #b71c1c; }
        .surrender { background: #e8f5e9; color: #1b5e20; }
        table { font-size: 13px; margin-top: 8px; border-collapse: collapse; width: 100%; }
        td { padding: 4px 0; vertical-align: top; }
        td strong { color: #555; }
    </style>
</head>
<body>

    <h1>Report & Image Verification Grid</h1>

    <div class="grid">
        <?php foreach ($reports as $report): ?>
            <?php 
                // Determine badge color class based on report type
                $badgeClass = 'claim';
                if ($report['type'] === 'Loss Report') $badgeClass = 'loss';
                if ($report['type'] === 'Surrender Form') $badgeClass = 'surrender';
            ?>
            <div class="card">
                <div class="info">
                    <span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($report['type']); ?></span>
                    <strong>(ID: <?php echo $report['report_id']; ?>)</strong>
                    
                    <table>
                        <tr><td style="width: 110px;"><strong>Student ID:</strong></td><td><?php echo htmlspecialchars($report['student_id']); ?></td></tr>
                        <tr><td><strong>Item ID Reference:</strong></td><td><?php echo $report['item_id'] ? $report['item_id'] : '<em>None (0/*)</em>'; ?></td></tr>
                        <tr><td><strong>Item Name:</strong></td><td><?php echo htmlspecialchars($report['item_name']); ?></td></tr>
                        <tr><td><strong>Description:</strong></td><td><?php echo htmlspecialchars($report['item_description']); ?></td></tr>
                        <tr><td><strong>Timestamp Context:</strong></td><td>
                            <?php 
                                if ($report['when_found']) echo "Found: " . $report['when_found'];
                                elseif ($report['when_lost']) echo "Lost: " . $report['when_lost'];
                                else echo "N/A";
                            ?>
                        </td></tr>
                    </table>
                </div>

                <div class="gallery">
                    <?php if (!empty($report['images'])): ?>
                        <?php $imgArray = explode(',', $report['images']); ?>
                        <?php foreach ($imgArray as $path): ?>
                            <div class="img-container">
                                <img src="<?php echo htmlspecialchars($path); ?>" class="thumb" alt="Report Image" onerror="this.src='https://placehold.co/100?text=Missing';">
                                <span class="img-label"><?php echo basename($path); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span style="color: #999; font-style: italic; font-size: 13px; margin: auto;">No images linked.</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>