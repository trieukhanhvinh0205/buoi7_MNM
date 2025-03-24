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
</head>
<body>
    <h2>Danh sách Học Phần</h2>
    <form method="post">
        <table border="1">
            <tr>
                <th>Mã Học Phần</th>
                <th>Tên Học Phần</th>
                <th>Chọn</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['MaHP'] . "</td>";
                    echo "<td>" . $row['TenHP'] . "</td>";
                    echo "<td><input type='radio' name='hocphan_id' value='" . $row['MaHP'] . "'></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Không có học phần nào.</td></tr>";
            }
            ?>
        </table>
        <br>
        <input type="submit" name="register" value="Đăng ký">
    </form>
</body>
</html>

<?php $conn->close(); ?>
