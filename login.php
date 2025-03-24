<?php
session_start();  // Bắt đầu session

$conn = new mysqli("localhost", "root", "", "test1");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (isset($_POST['login'])) {
    $masv = $_POST['masv'];

    // Kiểm tra thông tin mã số sinh viên
    $sql = "SELECT * FROM SinhVien WHERE MaSV = '$masv'";
    $result = $conn->query($sql);

    if (!$result) {
        die("Lỗi truy vấn: " . $conn->error);
    }

    // Nếu tìm thấy sinh viên
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['sinhvien_id'] = $row['MaSV'];  // Lưu MaSV vào session
        $_SESSION['sinhvien_name'] = $row['HoTen'];
        header('Location: hocphan.php');
        exit();
    } else {
        echo "Mã số sinh viên không đúng.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập</title>
</head>
<body>
    <h2>Đăng Nhập</h2>
    <form method="post">
        <label for="masv">Mã số sinh viên:</label>
        <input type="text" name="masv" id="masv" required><br><br>
        <input type="submit" name="login" value="Đăng Nhập">
    </form>
</body>
</html>

<?php $conn->close(); ?>
