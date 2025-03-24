<?php
session_start();  // Đảm bảo session được bắt đầu

// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "test1");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Biến chứa thông báo thành công và thông tin chi tiết đăng ký
$success_message = '';
$details_message = '';

// Lấy danh sách học phần từ cơ sở dữ liệu
$sql = "SELECT * FROM HocPhan";
$result = $conn->query($sql);

if (isset($_POST['register'])) {
    $hocphan_id = $_POST['hocphan_id'];
    if (isset($_SESSION['sinhvien_id'])) {
        $sinhvien_id = $_SESSION['sinhvien_id'];  // Đảm bảo sinh viên đã đăng nhập

        // Thêm vào bảng đăng ký học phần
        $sql_insert = "INSERT INTO DangKy (MaSV, NgayDK) VALUES ('$sinhvien_id', NOW())";
        if ($conn->query($sql_insert) === TRUE) {
            // Lấy MaDK vừa thêm
            $MaDK = $conn->insert_id;

            // Thêm vào bảng ChiTietDangKy
            $sql_insert_chitiet = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES ('$MaDK', '$hocphan_id')";
            if ($conn->query($sql_insert_chitiet) === TRUE) {
                // Hiển thị thông tin sinh viên và học phần đã đăng ký thành công
                $sql_sinhvien = "SELECT * FROM SinhVien WHERE MaSV='$sinhvien_id'";
                $sinhvien = $conn->query($sql_sinhvien)->fetch_assoc();

                $sql_hocphan = "SELECT * FROM HocPhan WHERE MaHP='$hocphan_id'";
                $hocphan = $conn->query($sql_hocphan)->fetch_assoc();

                // Gửi thông báo thành công
                $success_message = "Bạn đã đăng ký thành công học phần: " . htmlspecialchars($hocphan['TenHP']) . ".";

                // Tạo chi tiết thông tin đăng ký
                $details_message = "
                    <h3>Thông tin Đăng kí</h3>
                    <p><strong>Mã số sinh viên:</strong> " . htmlspecialchars($sinhvien['MaSV']) . "</p>
                    <p><strong>Họ Tên Sinh Viên:</strong> " . htmlspecialchars($sinhvien['HoTen']) . "</p>
                    <p><strong>Ngành học:</strong> " . htmlspecialchars($sinhvien['MaNganh']) . "</p>
                    <p><strong>Ngày Đăng Ký:</strong> " . date("Y-m-d H:i:s") . "</p>
                    <p><strong>Tên Học Phần:</strong> " . htmlspecialchars($hocphan['TenHP']) . "</p>";
            } else {
                echo "Lỗi khi thêm chi tiết đăng ký: " . $conn->error;
            }
        } else {
            echo "Lỗi khi thêm đăng ký: " . $conn->error;
        }
    } else {
        echo "Bạn cần đăng nhập để đăng ký học phần.";
    }
}

// Hàm lưu đăng ký dưới dạng tệp văn bản đơn giản
if (isset($_POST['save_registration'])) {
    $content = strip_tags($details_message);
    $filename = 'DangKy_' . $_SESSION['sinhvien_id'] . '_' . date("Ymd_His") . '.txt';
    file_put_contents($filename, $content);
    echo "<script>alert('Thông tin đăng ký đã được lưu thành công!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách học phần</title>
    <style>
        /* Reset CSS mặc định của trình duyệt */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
            color: #333;
        }

        .container {
            background-color: #fff;
            width: 70%;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        input[type="radio"] {
            transform: scale(1.2);
            margin-right: 10px;
        }

        .form-group {
            display: flex;
            justify-content: center;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .form-group {
            margin-top: 20px;
        }

        .success-message {
            color: #28a745;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }

        .details-message {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            .container {
                width: 90%;
            }

            th, td {
                font-size: 14px;
            }

            input[type="submit"] {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Danh sách Học Phần</h2>

        <!-- Hiển thị thông báo thành công nếu có -->
        <?php if (!empty($success_message)): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <!-- Hiển thị chi tiết thông tin đăng ký nếu có -->
        <?php if (!empty($details_message)): ?>
            <div class="details-message">
                <?php echo $details_message; ?>
            </div>

            <!-- Nút lưu đăng ký -->
            <form method="post">
                <div class="form-group">
                    <input type="submit" name="save_registration" value="Lưu Đăng Ký">
                </div>
            </form>
        <?php endif; ?>

        <!-- Form đăng ký học phần -->
        <form method="post">
            <table>
                <tr>
                    <th>Mã Học Phần</th>
                    <th>Tên Học Phần</th>
                    <th>Chọn</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['MaHP']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['TenHP']) . "</td>";
                        echo "<td><input type='radio' name='hocphan_id' value='" . htmlspecialchars($row['MaHP']) . "'></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Không có học phần nào.</td></tr>";
                }
                ?>
            </table>
            <div class="form-group">
                <input type="submit" name="register" value="Đăng Ký">
            </div>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>
