<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=task35', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $limit = 5; // Number of records per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Fetch paginated data
    $query = $pdo->prepare("SELECT * FROM person ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $query->bindValue(':limit', $limit, PDO::PARAM_INT);
    $query->bindValue(':offset', $offset, PDO::PARAM_INT);
    $query->execute();
    $data = $query->fetchAll(PDO::FETCH_ASSOC);

    // Count total records
    $totalQuery = $pdo->query("SELECT COUNT(*) FROM person");
    $totalRecords = $totalQuery->fetchColumn();
    $totalPages = ceil($totalRecords / $limit);

    echo json_encode(['data' => $data, 'totalPages' => $totalPages]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>