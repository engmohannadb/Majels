<?php
include("Configuration/DBInfoReader.php");

$type = isset($_GET['type']) ? $_GET['type'] : '';

if (!isset($Connection)) {
    die("Database connection variable \$Connection not found.");
}

// ---- Important: Force UTF-8 BOM for Arabic ----
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="open_data_'.$type.'.csv"');
echo "\xEF\xBB\xBF"; // UTF-8 BOM

$output = fopen('php://output', 'w');
mysqli_set_charset($Connection, "utf8mb4");


// ===============================================
// 1) Requests (without unit_id, user_id)
// ===============================================
if ($type == "requests") {

    // Column headers (Arabic-ready)
    fputcsv($output, [
        'request_title',
        'request_text',
        'request_date',
        'last_update',
        'status'
    ]);

    $sql = "SELECT request_title, request_text, request_date, last_update, status
            FROM requests";

    $rs = mysqli_query($Connection, $sql);

    while ($row = mysqli_fetch_assoc($rs)) {
        fputcsv($output, $row);
    }

}
// ===============================================
// 2) Units dataset (unchanged structure)
// ===============================================
elseif ($type == "units") {

    // تأكد من تشكيلة الحروف
    mysqli_set_charset($Connection, "utf8mb4");

    // نفحص أولًا أن الـ view أو الجدول موجود ويمكن قراءته
    $sql = "SELECT name, complex_type, unit_count, facilities, description, total_members, total_requests, pending_requests FROM units";
    $rs = mysqli_query($Connection, $sql);

    if (!$rs) {
        // عرض رسالة خطأ واضحة عند فشل الاستعلام (يساعد في التصحيح)
        header('Content-Type: text/plain; charset=utf-8');
        echo "SQL error: " . mysqli_error($Connection) . "\n";
        echo "Query: " . $sql . "\n";
        exit;
    }

    // استخرج أسماء الحقول مباشرة من نتيجة الاستعلام (ديناميكي)
    $meta = mysqli_fetch_fields($rs);
    $headers = [];
    foreach ($meta as $field) {
        $headers[] = $field->name;
    }

    // اكتب رؤوس الأعمدة في CSV
    fputcsv($output, $headers);

    // اكتب كل صف بعد التأكد من ترميز UTF-8
    while ($row = mysqli_fetch_assoc($rs)) {
        foreach ($row as $k => $v) {
            // اضبط التأكد من ترميز النصوص (إذا كانت NULL نتركها)
            if ($v !== null) {
                $row[$k] = mb_convert_encoding($v, 'UTF-8', 'UTF-8');
            } else {
                $row[$k] = '';
            }
        }
        fputcsv($output, $row);
    }

    mysqli_free_result($rs);

} else {
    echo "Invalid dataset type.";
}

fclose($output);
exit;
