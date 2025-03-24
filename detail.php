<?php
include 'database.php';
$MaSV = $_GET['MaSV'];
$result = $conn->query("SELECT sv.*, nh.TenNganh FROM SinhVien sv 
                        LEFT JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh 
                        WHERE sv.MaSV='$MaSV'");
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin chi tiết</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        h2 {
            color: #333;
        }
        .info {
            margin-bottom: 10px;
        }
        .info strong {
            display: inline-block;
            width: 100px;
            color: #555;
        }
        img {
            display: block;
            width: 150px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .back-link {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #007bff;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Thông tin chi tiết</h2>
        <div class="info"><strong>MSSV:</strong> <?= $row['MaSV'] ?></div>
        <div class="info"><strong>Họ tên:</strong> <?= $row['HoTen'] ?></div>
        <div class="info"><strong>Giới tính:</strong> <?= $row['GioiTinh'] ?></div>
        <div class="info"><strong>Ngày sinh:</strong> <?= date("d/m/Y", strtotime($row['NgaySinh'])) ?></div>
        <div class="info"><strong>Ngành:</strong> <?= $row['TenNganh'] ?></div>
        <img src="<?= $row['Hinh'] ?>" alt="Hình sinh viên">
        <a class="back-link" href="index.php">&#8592; Quay lại</a>
    </div>
</body>
</html>
