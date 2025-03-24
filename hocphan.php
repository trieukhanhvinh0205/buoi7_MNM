<?php
session_start();  // Đảm bảo session được bắt đầu

// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "test1");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy danh sách học phần từ cơ sở dữ liệu
$sql = "SELECT * FROM HocPhan";
$result = $conn->query($sql);

if (isset($_POST['register'])) {
    $hocphan_id = $_POST['hocphan_id'];
    if (isset($_SESSION['sinhvien_id'])) {
        $sinhvien_id = $_SESSION['sinhvien_id'];  // Đảm bảo sinh viên đã đăng nhập

        // Thêm vào bảng đăng ký học phần (sử dụng đúng tên cột là MaSV thay vì sinhvien_id)
        $sql_insert = "INSERT INTO DangKy (MaSV, NgayDK) VALUES ('$sinhvien_id', NOW())";
        if ($conn->query($sql_insert) === TRUE) {
            // Lấy MaDK vừa thêm
            $MaDK = $conn->insert_id;

            // Thêm vào bảng ChiTietDangKy
            $sql_insert_chitiet = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES ('$MaDK', '$hocphan_id')";
            if ($conn->query($sql_insert_chitiet) === TRUE) {
                echo "Đăng ký học phần thành công!";
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Danh sách học phần</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 60%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        td {
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        input[type="radio"] {
            cursor: pointer;
        }
        input[type="submit"] {
            margin-top: 20px;
            display: block;
            width: 150px;
            padding: 10px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Danh sách Học Phần</h2>
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
        <input type="submit" name="register" value="Đăng ký">
    </form>
</body>
</html>

<?php $conn->close(); ?>
