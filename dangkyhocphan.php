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

        // Kiểm tra xem sinh viên đã có MaDK chưa
        $sql_check = "SELECT MaDK FROM DangKy WHERE MaSV = '$sinhvien_id'";
        $result_check = $conn->query($sql_check);
        
        if ($result_check->num_rows == 0) {
            // Nếu chưa có đăng ký, thêm mới vào bảng DangKy
            $sql_insert = "INSERT INTO DangKy (MaSV, NgayDK) VALUES ('$sinhvien_id', NOW())";
            if ($conn->query($sql_insert) === TRUE) {
                $MaDK = $conn->insert_id;
            } else {
                echo "Lỗi khi thêm đăng ký: " . $conn->error;
            }
        } else {
            $MaDK = $result_check->fetch_assoc()['MaDK'];
        }

        // Thêm vào bảng ChiTietDangKy nếu chưa đăng ký học phần này
        $sql_check_hocphan = "SELECT * FROM ChiTietDangKy WHERE MaDK = '$MaDK' AND MaHP = '$hocphan_id'";
        $result_hocphan = $conn->query($sql_check_hocphan);
        
        if ($result_hocphan->num_rows == 0) {
            $sql_insert_chitiet = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES ('$MaDK', '$hocphan_id')";
            if ($conn->query($sql_insert_chitiet) === TRUE) {
                echo "Đăng ký học phần thành công!";
            } else {
                echo "Lỗi khi thêm chi tiết đăng ký: " . $conn->error;
            }
        } else {
            echo "Học phần này đã được đăng ký.";
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
                <th>Số Tín Chỉ</th>
                <th>Chọn</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['MaHP'] . "</td>";
                    echo "<td>" . $row['TenHP'] . "</td>";
                    echo "<td>" . $row['SoTinChi'] . "</td>";
                    echo "<td><input type='radio' name='hocphan_id' value='" . $row['MaHP'] . "'></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Không có học phần nào.</td></tr>";
            }
            ?>
        </table>
        <br>
        <input type="submit" name="register" value="Đăng ký">
    </form>
</body>
</html>

<?php $conn->close(); ?>
