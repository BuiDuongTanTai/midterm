<?php
// Lấy ID lớn nhất từ bảng professors
$sql_max_id = "SELECT MAX(id) AS max_id FROM professors";
$result_max_id = $conn->query($sql_max_id);
if ($result_max_id) {
    $row = $result_max_id->fetch_assoc(); // Lấy hàng kết quả
    $_SESSION['professor_id'] = $row['max_id']; // Lưu ID lớn nhất vào session
} else {
    die("Lỗi truy vấn: " . $conn->error); // Xử lý lỗi nếu truy vấn không thành công
}

$professor_id = $_SESSION['professor_id'];

if ($professor_id === null) {
    die("Không có dữ liệu.");
}

// Truy vấn SQL để lấy thông tin của giảng viên
$sql = "SELECT full_name, position, university, degree, specialization, email, phone, office, scholar_link, research_gate_link, last_updated, footer_year FROM professors WHERE id = ?";

// Chuẩn bị statement
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// Liên kết tham số
$stmt->bind_param("i", $professor_id); // "i" cho integer (ID)

// Thực thi truy vấn
$stmt->execute();

// Lấy kết quả
$result = $stmt->get_result();

// Kiểm tra xem có giảng viên nào được tìm thấy không
if ($result->num_rows === 0) {
    die("Không tìm thấy bản ghi nào");
}

// Lấy thông tin của giảng viên
$professor = $result->fetch_assoc();

// Lấy tiểu sử của giảng viên
$sql_bio = "SELECT content FROM bios";
$stmt_bio = $conn->prepare($sql_bio);
if ($stmt_bio === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt_bio->execute();
$result_bio = $stmt_bio->get_result();
$professor['bio'] = [];
while ($row_bio = $result_bio->fetch_assoc()) {
    $professor['bio'][] = $row_bio['content'];
}
$stmt_bio->close();

// Lấy các bài báo khoa học của giảng viên

// Phnâ trang
$sql_publications = "SELECT id, title, authors, journal, link FROM publications";
$stmt_publications = $conn->prepare($sql_publications);
if ($stmt_publications === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt_publications->execute();
$result_publications = $stmt_publications->get_result();
$professor['publications'] = [];
while ($row_publication = $result_publications->fetch_assoc()) {
    $professor['publications'][] = $row_publication;
}
$stmt_publications->close();

// Lấy các dự án nghiên cứu của giảng viên
$sql_projects = "SELECT id, title, description, link, button_text FROM projects";
$stmt_projects = $conn->prepare($sql_projects);
if ($stmt_projects === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt_projects->execute();
$result_projects = $stmt_projects->get_result();
$professor['projects'] = [];
while ($row_project = $result_projects->fetch_assoc()) {
    $professor['projects'][] = $row_project;
}
$stmt_projects->close();

// Lấy lịch dạy của giảng viên
$sql_schedule = "SELECT time_slot, weekday, class_code, room, is_office_hour FROM schedules";
$stmt_schedule = $conn->prepare($sql_schedule);
if ($stmt_schedule === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt_schedule->execute();
$result_schedule = $stmt_schedule->get_result();
$professor['schedule'] = [];

while ($row_schedule = $result_schedule->fetch_assoc()) {
    // Chuyển đổi dữ liệu lịch dạy thành định dạng bạn mong muốn
    $time_slot = $row_schedule['time_slot'];
    $weekday = $row_schedule['weekday'];
    $class_code = $row_schedule['class_code'];
    $room = $row_schedule['room'];
    $is_office_hour = $row_schedule['is_office_hour'];

    // Tìm hoặc tạo một mục cho time_slot này
    $time_slot_index = -1;
    for ($i = 0; $i < count($professor['schedule']); $i++) {
        if ($professor['schedule'][$i]['time'] == $time_slot) {
            $time_slot_index = $i;
            break;
        }
    }

    if ($time_slot_index == -1) {
        $professor['schedule'][] = [
            'time' => $time_slot,
            'monday' => null,
            'tuesday' => null,
            'wednesday' => null,
            'thursday' => null,
            'friday' => null,
        ];
        $time_slot_index = count($professor['schedule']) - 1;
    }

    // Đặt thông tin vào đúng ngày trong tuần
    $day_mapping = [
        'monday' => 'monday',
        'tuesday' => 'tuesday',
        'wednesday' => 'wednesday',
        'thursday' => 'thursday',
        'friday' => 'friday',
    ];

    if (isset($day_mapping[$weekday])) {
        $day = $day_mapping[$weekday];
        $professor['schedule'][$time_slot_index][$day] = [
            'class' => $class_code,
            'room' => $room,
            'office' => $is_office_hour ? true : null, // Thêm 'office' nếu là giờ làm việc
        ];
    }
}
$stmt_schedule->close();
// Đóng kết nối
$conn->close();
?>