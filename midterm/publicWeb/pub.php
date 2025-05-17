<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "DzoanXuanThanh";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID lớn nhất từ bảng professors
$sql_max_id = "SELECT MAX(id) AS max_id FROM professors";
$result_max_id = $conn->query($sql_max_id);

if ($result_max_id === false) {
    die("Lỗi truy vấn: " . $conn->error);
}

$row_max_id = $result_max_id->fetch_assoc();
$professor_id = $row_max_id['max_id'];

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
$sql_publications = "SELECT title, authors, journal, link FROM publications";
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
$sql_projects = "SELECT title, description, link, button_text FROM projects";
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

// Đóng statement ban đầu
$stmt->close();

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Profile Giảng Viên - <?php echo htmlspecialchars($professor['full_name']); ?></title>
</head>
<body>
    <header>
        <div class="container">
            <h1><?php echo htmlspecialchars($professor['full_name']); ?></h1>
            <p><?php echo htmlspecialchars($professor['position']); ?> | <?php echo htmlspecialchars($professor['university']); ?></p>
        </div>
    </header>

    <main class="container">
        <div class="profile-grid">
            <div class="profile-sidebar">
                <img src="/api/placeholder/400/400" alt="<?php echo htmlspecialchars($professor['full_name']); ?>" class="profile-image">
                <h2>Thông tin cá nhân</h2>
                <p><?php echo htmlspecialchars($professor['degree']); ?></p>
                <p>Chuyên ngành: <?php echo htmlspecialchars($professor['specialization']); ?></p>
                
                <div class="contact-info">
                    <h3>Thông tin liên hệ</h3>
                    <div class="contact-item">
                        <span class="contact-icon">✉️</span>
                        <span><?php echo htmlspecialchars($professor['email']); ?></span>
                    </div>
                    <div class="contact-item">
                        <span class="contact-icon">📞</span>
                        <span><?php echo htmlspecialchars($professor['phone']); ?></span>
                    </div>
                    <div class="contact-item">
                        <span class="contact-icon">🏢</span>
                        <span><?php echo htmlspecialchars($professor['office']); ?></span>
                    </div>
                    <div class="contact-item">
                        <span class="contact-icon">🔗</span>
                        <span><a href="<?php echo htmlspecialchars($professor['scholar_link']); ?>" target="_blank">Google Scholar</a></span>
                    </div>
                    <div class="contact-item">
                        <span class="contact-icon">🔗</span>
                        <span><a href="<?php echo htmlspecialchars($professor['research_gate_link']); ?>" target="_blank">ResearchGate</a></span>
                    </div>
                </div>
            </div>
            
            <div class="profile-main">
                <section class="profile-section">
                    <h2 class="section-title">Giới thiệu</h2>
                    <?php foreach ($professor['bio'] as $paragraph) : ?>
                        <p><?php echo nl2br(htmlspecialchars($paragraph)); ?></p>
                    <?php endforeach; ?>
                </section>
                
                <section class="profile-section">
                    <h2 class="section-title">Các bài báo khoa học</h2>
                    <div class="publications-list">
                        <?php foreach ($professor['publications'] as $publication) : ?>
                        <div class="publication">
                            <div class="publication-title"><?php echo htmlspecialchars($publication['title']); ?></div>
                            <div class="publication-authors"><?php echo htmlspecialchars($publication['authors']); ?></div>
                            <div class="publication-journal"><?php echo htmlspecialchars($publication['journal']); ?></div>
                            <a href="<?php echo htmlspecialchars($publication['link']); ?>" class="download-btn">Xem bài báo</a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                
                <section class="profile-section">
                    <h2 class="section-title">Dự án nghiên cứu</h2>
                    <div class="projects-grid">
                        <?php foreach ($professor['projects'] as $project) : ?>
                        <div class="project-card">
                            <div class="project-title"><?php echo htmlspecialchars($project['title']); ?></div>
                            <p><?php echo htmlspecialchars($project['description']); ?></p>
                            <a href="<?php echo htmlspecialchars($project['link']); ?>" class="download-btn"><?php echo htmlspecialchars($project['button_text']); ?></a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                
                <section class="profile-section">
                    <h2 class="section-title">Lịch dạy và giờ làm việc</h2>
                    <div class="schedule-container">
                        <table class="schedule-table">
                            <thead>
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Thứ 2</th>
                                    <th>Thứ 3</th>
                                    <th>Thứ 4</th>
                                    <th>Thứ 5</th>
                                    <th>Thứ 6</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($professor['schedule'] as $row) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['time']); ?></td>
                                    <td<?php echo !empty($row['monday']) ? (isset($row['monday']['office']) ? ' class="office-hours"' : ' class="class-session"') : ''; ?>>
                                        <?php
                                        if (!empty($row['monday'])) {
                                            if (isset($row['monday']['office'])) {
                                                echo "Giờ làm việc<br>" . htmlspecialchars($row['monday']['room']);
                                            } else {
                                                echo htmlspecialchars($row['monday']['class']) . "<br>" . htmlspecialchars($row['monday']['room']);
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td<?php echo !empty($row['tuesday']) ? (isset($row['tuesday']['office']) ? ' class="office-hours"' : ' class="class-session"') : ''; ?>>
                                        <?php
                                        if (!empty($row['tuesday'])) {
                                            if (isset($row['tuesday']['office'])) {
                                                echo "Giờ làm việc<br>" . htmlspecialchars($row['tuesday']['room']);
                                            } else {
                                                echo htmlspecialchars($row['tuesday']['class']) . "<br>" . htmlspecialchars($row['tuesday']['room']);
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td<?php echo !empty($row['wednesday']) ? (isset($row['wednesday']['office']) ? ' class="office-hours"' : ' class="class-session"') : ''; ?>>
                                        <?php
                                        if (!empty($row['wednesday'])) {
                                            if (isset($row['wednesday']['office'])) {
                                                echo "Giờ làm việc<br>" . htmlspecialchars($row['wednesday']['room']);
                                            } else {
                                                echo htmlspecialchars($row['wednesday']['class']) . "<br>" . htmlspecialchars($row['wednesday']['room']);
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td<?php echo !empty($row['thursday']) ? (isset($row['thursday']['office']) ? ' class="office-hours"' : ' class="class-session"') : ''; ?>>
                                        <?php
                                        if (!empty($row['thursday'])) {
                                            if (isset($row['thursday']['office'])) {
                                                echo "Giờ làm việc<br>" . htmlspecialchars($row['thursday']['room']);
                                            } else {
                                                echo htmlspecialchars($row['thursday']['class']) . "<br>" . htmlspecialchars($row['thursday']['room']);
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td<?php echo !empty($row['friday']) ? (isset($row['friday']['office']) ? ' class="office-hours"' : ' class="class-session"') : ''; ?>>
                                        <?php
                                        if (!empty($row['friday'])) {
                                            if (isset($row['friday']['office'])) {
                                                echo "Giờ làm việc<br>" . htmlspecialchars($row['friday']['room']);
                                            } else {
                                                echo htmlspecialchars($row['friday']['class']) . "<br>" . htmlspecialchars($row['friday']['room']);
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo htmlspecialchars($professor['footer_year']); ?> <?php echo htmlspecialchars($professor['full_name']); ?> - Khoa Công nghệ Thông tin, <?php echo htmlspecialchars($professor['university']); ?></p>
            <p>Cập nhật lần cuối: <?php echo htmlspecialchars($professor['last_updated']); ?></p>
        </div>
    </footer>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>