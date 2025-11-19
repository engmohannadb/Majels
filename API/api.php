<?php
// إعداد الرأس (Header) للإشارة إلى أن الاستجابة ستكون بصيغة JSON
header('Content-Type: application/json');

// تضمين ملف اتصال PDO
// (يجب أن يحتوي هذا الملف على $pdo = new PDO(...) ويفضل أن يكون في try/catch)
include("config.php"); 

// =========================================================================
// متغيرات النظام
// =========================================================================

// ✅ التوثيق: يجب استبدال هذا بمعرف المستخدم المسجل دخوله فعليًا (من جلسة أو JWT)
// هذا المعرف يستخدم في عمليات الإنشاء (created_by) والتحقق من الملكية (للتحديث/الحذف).
$current_user_id = 1; 

// =========================================================================
// معالجة المدخلات
// =========================================================================

// 1. قراءة البيانات الأولية (Raw Data) لمحاولات JSON
$input = json_decode(file_get_contents("php://input"), true);

// 2. إذا فشل فك تشفير JSON أو كان فارغًا، نعود إلى بيانات POST العادية
if (empty($input)) {
    $input = $_POST;
}

// تحديد نقطة النهاية (Endpoint) وطريقة HTTP
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';
$method = $_SERVER['REQUEST_METHOD'];

// =========================================================================
// التوجيه (Router) الرئيسي
// =========================================================================

if ($endpoint === 'login') {
    if ($method === 'POST') {
        login($input, $pdo);
    } else {
        sendError("Method Not Allowed for login", 405);
    }
} elseif ($endpoint === 'events') {
    handleEvents($method, $input, $pdo, $current_user_id);
} elseif ($endpoint === 'units') {
    handleUnits($method, $input, $pdo, $current_user_id);
} elseif ($endpoint === 'requests') {
    handleRequests($method, $input, $pdo, $current_user_id);
} else {
    sendError("Invalid endpoint: " . $endpoint, 404);
}

// =========================================================================
// الدوال المساعدة
// =========================================================================

/**
 * إرسال رسالة خطأ موحدة مع رمز حالة HTTP
 */
function sendError($message, $code = 400) {
    http_response_code($code);
    echo json_encode(["status" => "error", "message" => $message]);
    exit;
}

/**
 * دالة تسجيل الدخول (من التحديث السابق)
 */
function login($data, $pdo) {
    if (!isset($data['Email']) || !isset($data['Password'])) {
        sendError("Email and Password are required");
    }

    $email = $data['Email'];
    $password = md5($data['Password']); 

    $stmt = $pdo->prepare("SELECT ID, Email, Name FROM users WHERE Email = ? AND Password = ?");
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Login successful",
            "user" => $user
        ]);
    } else {
        sendError("Invalid email or password", 401);
    }
}

// =========================================================================
// معالج جدول EVENTS (CRUD مكتمل)
// =========================================================================

/**
 * موجه لعمليات CRUD لجدول events
 */
function handleEvents($method, $data, $pdo, $user_id) {
    // معرف الحدث (قد يكون موجودًا في GET URL للإشارة إلى حدث معين)
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    switch ($method) {
        case 'GET':
            getEvents($pdo, $id);
            break;
        case 'POST':
            createEvent($data, $pdo, $user_id);
            break;
        case 'PUT':
        case 'PATCH':
            if ($id > 0) {
                updateEvent($data, $pdo, $user_id, $id);
            } else {
                sendError("Event ID is required for updating", 400);
            }
            break;
        case 'DELETE':
            if ($id > 0) {
                deleteEvent($pdo, $user_id, $id);
            } else {
                sendError("Event ID is required for deletion", 400);
            }
            break;
        default:
            sendError("Method Not Allowed", 405);
    }
}

/**
 * دالة قراءة الأحداث
 */
function getEvents($pdo, $id) {
    if ($id > 0) {
        $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            sendError("Event not found", 404);
        }
    } else {
        $stmt = $pdo->prepare("SELECT * FROM events ORDER BY event_date DESC");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    http_response_code(200);
    echo json_encode(["status" => "success", "data" => $result]);
}

/**
 * دالة إنشاء حدث جديد
 */
function createEvent($data, $pdo, $user_id) {
    if (empty($data['unit_id']) || empty($data['title']) || empty($data['event_date'])) {
        sendError("Unit ID, Title, and Event Date are required.");
    }

    $query = "INSERT INTO events (unit_id, title, description, event_date, created_by) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([
            $data['unit_id'],
            $data['title'],
            $data['description'] ?? null,
            $data['event_date'],
            $user_id
        ]);

        http_response_code(201); // Created
        echo json_encode([
            "status" => "success",
            "message" => "Event created successfully.",
            "id" => $pdo->lastInsertId()
        ]);
    } catch (PDOException $e) {
        sendError("Database error: " . $e->getMessage(), 500);
    }
}

/**
 * دالة تحديث حدث موجود
 */
function updateEvent($data, $pdo, $user_id, $event_id) {
    // ⚠️ التحقق من الملكية
    $auth_stmt = $pdo->prepare("SELECT created_by FROM events WHERE id = ?");
    $auth_stmt->execute([$event_id]);
    $event_owner = $auth_stmt->fetchColumn();

    if (!$event_owner) {
        sendError("Event not found.", 404);
    }
    if ($event_owner != $user_id) {
        sendError("Unauthorized: You do not own this event.", 403);
    }

    $allowed_fields = ['unit_id', 'title', 'description', 'event_date'];
    $set_parts = [];
    $execute_data = [];

    foreach ($allowed_fields as $field) {
        if (isset($data[$field])) {
            $set_parts[] = "`{$field}` = ?";
            $execute_data[] = $data[$field];
        }
    }

    if (empty($set_parts)) {
        sendError("No valid fields provided for update.", 400);
    }

    $query = "UPDATE events SET " . implode(', ', $set_parts) . " WHERE id = ?";
    $execute_data[] = $event_id;

    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute($execute_data);
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Event updated successfully.",
            "changes" => $stmt->rowCount()
        ]);
    } catch (PDOException $e) {
        sendError("Database error: " . $e->getMessage(), 500);
    }
}

/**
 * دالة حذف حدث
 */
function deleteEvent($pdo, $user_id, $event_id) {
    // ⚠️ التحقق من الملكية
    $auth_stmt = $pdo->prepare("SELECT created_by FROM events WHERE id = ?");
    $auth_stmt->execute([$event_id]);
    $event_owner = $auth_stmt->fetchColumn();

    if (!$event_owner) {
        sendError("Event not found.", 404);
    }
    if ($event_owner != $user_id) {
        sendError("Unauthorized: You do not own this event.", 403);
    }

    $query = "DELETE FROM events WHERE id = ?";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([$event_id]);
        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Event deleted successfully."]);
        } else {
            sendError("Event not found or already deleted.", 404);
        }
    } catch (PDOException $e) {
        sendError("Database error: " . $e->getMessage(), 500);
    }
}

// =========================================================================
// معالج جدول UNITS (CRUD مكتمل)
// =========================================================================

/**
 * دالة قراءة الوحدات
 */
function getUnits($pdo, $id) {
    $query = $id > 0 ? "SELECT * FROM units WHERE id = ?" : "SELECT * FROM units";
    $stmt = $pdo->prepare($query);
    if ($id > 0) {
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result && $id > 0) sendError("Unit not found", 404);
    } else {
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    http_response_code(200);
    echo json_encode(["status" => "success", "data" => $result]);
}

/**
 * دالة إنشاء وحدة جديدة
 */
function createUnit($data, $pdo) {
    // تم تعديل هذا الجزء ليناسب الأعمدة الجديدة في جدول UNITS
    if (empty($data['name']) || empty($data['address'])) {
        sendError("Unit Name and Address are required.");
    }

    $query = "INSERT INTO units (name, complex_type, image_path, unit_count, facilities, latitude, longitude, description, address, owner_id) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([
            $data['name'],
            $data['complex_type'] ?? null,
            $data['image_path'] ?? null,
            $data['unit_count'] ?? null,
            $data['facilities'] ?? null,
            $data['latitude'] ?? null,
            $data['longitude'] ?? null,
            $data['description'] ?? null,
            $data['address'],
            $data['owner_id'] ?? null // يمكن تعيينه بناءً على المستخدم الحالي لاحقًا
        ]);

        http_response_code(201);
        echo json_encode([
            "status" => "success",
            "message" => "Unit created successfully.",
            "id" => $pdo->lastInsertId()
        ]);
    } catch (PDOException $e) {
        sendError("Database error: " . $e->getMessage(), 500);
    }
}

/**
 * دالة تحديث وحدة موجودة
 */
function updateUnit($data, $pdo, $unit_id) {
    $allowed_fields = ['name', 'complex_type', 'image_path', 'unit_count', 'facilities', 'latitude', 'longitude', 'description', 'address', 'owner_id'];
    $set_parts = [];
    $execute_data = [];

    foreach ($allowed_fields as $field) {
        if (isset($data[$field])) {
            $set_parts[] = "`{$field}` = ?";
            $execute_data[] = $data[$field];
        }
    }

    if (empty($set_parts)) {
        sendError("No valid fields provided for update.", 400);
    }

    $query = "UPDATE units SET " . implode(', ', $set_parts) . " WHERE id = ?";
    $execute_data[] = $unit_id;

    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute($execute_data);
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Unit updated successfully.",
            "changes" => $stmt->rowCount()
        ]);
    } catch (PDOException $e) {
        sendError("Database error: " . $e->getMessage(), 500);
    }
}

/**
 * دالة حذف وحدة
 */
function deleteUnit($pdo, $unit_id) {
    // يمكن إضافة تحقق من الملكية هنا إذا كان هناك مالك لـ Unit
    
    $query = "DELETE FROM units WHERE id = ?";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([$unit_id]);
        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Unit deleted successfully."]);
        } else {
            sendError("Unit not found or already deleted.", 404);
        }
    } catch (PDOException $e) {
        sendError("Database error: " . $e->getMessage(), 500);
    }
}

/**
 * موجه لعمليات CRUD لجدول units
 */
function handleUnits($method, $data, $pdo, $user_id) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    // التحقق من التفويض (نفترض أن المستخدم يجب أن يكون مسجلاً للدخول لإجراء تعديلات)
    if (in_array($method, ['POST', 'PUT', 'DELETE', 'PATCH']) && $user_id === 0) {
        sendError("Authorization required for unit modifications.", 403);
    }

    switch ($method) {
        case 'GET':
            getUnits($pdo, $id);
            break;
        case 'POST':
            createUnit($data, $pdo);
            break;
        case 'PUT':
        case 'PATCH':
            if ($id > 0) {
                updateUnit($data, $pdo, $id);
            } else {
                sendError("Unit ID is required for updating", 400);
            }
            break;
        case 'DELETE':
            if ($id > 0) {
                deleteUnit($pdo, $id);
            } else {
                sendError("Unit ID is required for deletion", 400);
            }
            break;
        default:
            sendError("Method Not Allowed", 405);
    }
}

// =========================================================================
// معالج جدول REQUESTS (CRUD مكتمل)
// =========================================================================

/**
 * دالة قراءة الطلبات
 */
function getRequests($pdo, $id) {
    if ($id > 0) {
        $stmt = $pdo->prepare("SELECT * FROM requests WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            sendError("Request not found", 404);
        }
    } else {
        // نستخدم created_at للترتيب
        $stmt = $pdo->prepare("SELECT * FROM requests ORDER BY request_date DESC");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    http_response_code(200);
    echo json_encode(["status" => "success", "data" => $result]);
}

/**
 * دالة إنشاء طلب جديد
 */
function createRequest($data, $pdo, $user_id) {
    // تم تعديل هذا الجزء ليناسب الأعمدة في جدول REQUESTS
    if (empty($data['unit_id']) || empty($data['request_title']) || empty($data['request_text'])) {
        sendError("Unit ID, Request Title, and Request Text are required.");
    }
    
    // يتم تعيين status كـ 'open' افتراضيًا
    $status = 'open';

    $query = "INSERT INTO requests (unit_id, user_id, request_title, request_text, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([
            $data['unit_id'],
            $user_id, // تم تعيين المستخدم الحالي كـ user_id
            $data['request_title'],
            $data['request_text'],
            $status 
        ]);

        http_response_code(201); // Created
        echo json_encode([
            "status" => "success",
            "message" => "Request created successfully.",
            "id" => $pdo->lastInsertId()
        ]);
    } catch (PDOException $e) {
        sendError("Database error: " . $e->getMessage(), 500);
    }
}

/**
 * دالة تحديث طلب موجود
 */
function updateRequest($data, $pdo, $user_id, $request_id) {
    // ⚠️ التحقق من الملكية: يجب أن يكون المستخدم الحالي هو من أنشأ الطلب (user_id)
    $auth_stmt = $pdo->prepare("SELECT user_id FROM requests WHERE id = ?");
    $auth_stmt->execute([$request_id]);
    $request_owner = $auth_stmt->fetchColumn();

    if (!$request_owner) {
        sendError("Request not found.", 404);
    }
    // يمكن تعديل هذا الشرط للسماح للمدراء أيضًا بالتحديث
    if ($request_owner != $user_id) {
        sendError("Unauthorized: You do not own this request.", 403);
    }

    $allowed_fields = ['unit_id', 'request_title', 'request_text', 'status'];
    $set_parts = [];
    $execute_data = [];

    foreach ($allowed_fields as $field) {
        if (isset($data[$field])) {
            $set_parts[] = "`{$field}` = ?";
            $execute_data[] = $data[$field];
        }
    }
    
    // إضافة تحديث لعمود last_update
    $set_parts[] = "`last_update` = NOW()";

    if (empty($set_parts)) {
        sendError("No valid fields provided for update.", 400);
    }

    $query = "UPDATE requests SET " . implode(', ', $set_parts) . " WHERE id = ?";
    $execute_data[] = $request_id;

    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute($execute_data);
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Request updated successfully.",
            "changes" => $stmt->rowCount()
        ]);
    } catch (PDOException $e) {
        sendError("Database error: " . $e->getMessage(), 500);
    }
}

/**
 * دالة حذف طلب
 */
function deleteRequest($pdo, $user_id, $request_id) {
    // ⚠️ التحقق من الملكية
    $auth_stmt = $pdo->prepare("SELECT user_id FROM requests WHERE id = ?");
    $auth_stmt->execute([$request_id]);
    $request_owner = $auth_stmt->fetchColumn();

    if (!$request_owner) {
        sendError("Request not found.", 404);
    }
    if ($request_owner != $user_id) {
        sendError("Unauthorized: You do not own this request.", 403);
    }

    $query = "DELETE FROM requests WHERE id = ?";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([$request_id]);
        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Request deleted successfully."]);
        } else {
            sendError("Request not found or already deleted.", 404);
        }
    } catch (PDOException $e) {
        sendError("Database error: " . $e->getMessage(), 500);
    }
}

/**
 * موجه لعمليات CRUD لجدول requests
 */
function handleRequests($method, $data, $pdo, $user_id) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    // التحقق من التفويض (يجب أن يكون المستخدم مسجلاً للدخول لإنشاء أو تعديل الطلبات)
    if (in_array($method, ['POST', 'PUT', 'DELETE', 'PATCH']) && $user_id === 0) {
        sendError("Authorization required for request modifications.", 403);
    }

    switch ($method) {
        case 'GET':
            getRequests($pdo, $id);
            break;
        case 'POST':
            createRequest($data, $pdo, $user_id);
            break;
        case 'PUT':
        case 'PATCH':
            if ($id > 0) {
                updateRequest($data, $pdo, $user_id, $id);
            } else {
                sendError("Request ID is required for updating", 400);
            }
            break;
        case 'DELETE':
            if ($id > 0) {
                deleteRequest($pdo, $user_id, $id);
            } else {
                sendError("Request ID is required for deletion", 400);
            }
            break;
        default:
            sendError("Method Not Allowed", 405);
    }
}

// =========================================================================
?>