<?php
include 'database.php';

$MaSV = $_GET['MaSV'];
$result = $conn->query("SELECT * FROM SinhVien WHERE MaSV='$MaSV'");
$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->query("DELETE FROM SinhVien WHERE MaSV='$MaSV'");
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xóa Thông Tin Sinh Viên</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background: #fff;
        }
        .navbar {
            background: #222;
            padding: 15px;
            display: flex;
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
            width: 60%;
            margin: auto;
            padding: 30px;
            background: white;
            margin-top: 30px;
            text-align: left;
        }
        h2 {
            margin-bottom: 15px;
            font-size: 24px;
        }
        p {
            margin-bottom: 20px;
            font-size: 18px;
        }
        table {
            width: 100%;
        }
        td {
            padding: 5px;
            vertical-align: top;
        }
        .info-title {
            font-weight: bold;
            width: 100px;
        }
        .image-preview {
            max-width: 150px;
            border-radius: 5px;
        }
        .buttons {
            margin-top: 15px;
        }
        .btn-delete {
            background: red;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-delete:hover {
            background: darkred;
        }
        .back-link {
            margin-left: 10px;
            text-decoration: none;
            color: blue;
        }
        .back-link:hover {
            text-decoration: underline;
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
        <h2>XÓA THÔNG TIN</h2>
        <p>Are you sure you want to delete this?</p>
        
        <table>
            <tr>
                <td class="info-title">Họ Tên:</td>
                <td><?= $row['HoTen'] ?></td>
            </tr>
            <tr>
                <td class="info-title">Giới Tính:</td>
                <td><?= $row['GioiTinh'] ?></td>
            </tr>
            <tr>
                <td class="info-title">Ngày Sinh:</td>
                <td><?= date('d/m/Y', strtotime($row['NgaySinh'])) ?></td>
            </tr>
            <tr>
                <td class="info-title">Hình:</td>
                <td>
                    <?php if ($row['Hinh']) : ?>
                        <img src="<?= $row['Hinh'] ?>" class="image-preview">
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td class="info-title">Mã Ngành:</td>
                <td><?= $row['MaNganh'] ?></td>
            </tr>
        </table>

        <form method="post" class="buttons">
            <button type="submit" class="btn-delete">Delete</button>
            <a href="index.php" class="back-link">| Back to List</a>
        </form>
    </div>

</body>
</html>
