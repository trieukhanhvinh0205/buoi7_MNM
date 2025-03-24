<?php
include 'database.php';

$MaSV = $_GET['MaSV'];
$result = $conn->query("SELECT * FROM SinhVien WHERE MaSV='$MaSV'");
$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $HoTen = $_POST['HoTen'];
    $GioiTinh = $_POST['GioiTinh'];
    $NgaySinh = $_POST['NgaySinh'];
    $MaNganh = $_POST['MaNganh'];

    $Hinh = $_FILES['Hinh']['name'];
    if ($Hinh) {
        move_uploaded_file($_FILES['Hinh']['tmp_name'], "Content/images" . $Hinh);
        $HinhPath = "Content/images" . $Hinh;
    } else {
        $HinhPath = $row['Hinh'];
    }

    $sql = "UPDATE SinhVien SET HoTen='$HoTen', GioiTinh='$GioiTinh', NgaySinh='$NgaySinh', Hinh='$HinhPath', MaNganh='$MaNganh' WHERE MaSV='$MaSV'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hiệu chỉnh sinh viên</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background: #f8f9fa;
        }
        .navbar {
            background: #222;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }
        .container {
            width: 50%;
            margin: auto;
            padding: 20px;
            background: white;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="file"] {
            padding: 5px;
            background: #d4edda;
            border: 1px solid #28a745;
            cursor: pointer;
        }
        .btn {
            background: blue;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background: darkblue;
        }
        .back-link {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            color: blue;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .image-preview {
            margin-top: 10px;
            max-width: 200px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <div><strong>Test1</strong></div>
        <div>
            <a href="#">Sinh Viên</a>
            <a href="#">Học Phần</a>
            <a href="#"><strong>Đăng Kí ( )</strong></a>
            <a href="#">Đăng Nhập</a>
        </div>
    </div>

    <div class="container">
        <h2>Hiệu chỉnh thông tin sinh viên</h2>
        <form method="post" enctype="multipart/form-data">
            <label>Họ Tên</label>
            <input type="text" name="HoTen" value="<?= $row['HoTen'] ?>" required>

            <label>Giới Tính</label>
            <select name="GioiTinh">
                <option value="Nam" <?= $row['GioiTinh'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                <option value="Nữ" <?= $row['GioiTinh'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
            </select>

            <label>Ngày Sinh</label>
            <input type="date" name="NgaySinh" value="<?= date('Y-m-d', strtotime($row['NgaySinh'])) ?>" required>

            <label>Hình</label>
            <input type="file" name="Hinh">
            <br>
            <?php if ($row['Hinh']) : ?>
                <img src="<?= $row['Hinh'] ?>" class="image-preview">
            <?php endif; ?>

            <label>Mã Ngành</label>
            <input type="text" name="MaNganh" value="<?= $row['MaNganh'] ?>" required>

            <button type="submit" class="btn">Save</button>
        </form>

        <a href="index.php" class="back-link">Back to List</a>
    </div>

</body>
</html>
