<?php
include 'database.php';

$sql = "SELECT sv.*, nh.TenNganh FROM SinhVien sv 
        LEFT JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Sinh Viên</title>
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
            width: 90%;
            margin: auto;
            padding: 20px;
            background: white;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #f1f1f1;
        }
        tr:hover {
            background: #f9f9f9;
        }
        img {
            width: 120px;
            height: 140px;
            object-fit: cover;
            border-radius: 8px;
        }
        .action-links a {
            margin-right: 10px;
            text-decoration: none;
            color: blue;
        }
        .action-links a:hover {
            text-decoration: underline;
        }
        .add-btn {
            display: inline-block;
            padding: 8px 12px;
            background: blue;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .add-btn:hover {
            background: darkblue;
        }
    </style>
</head>
<body>

    <!-- <div class="navbar">
        <div><strong>Test1</strong></div>
        <div>
            <a href="#">Sinh Viên</a>
            <a href="#">Học Phần</a>
            <a href="#"><strong>Đăng Kí ( )</strong></a>
            <a href="#">Đăng Nhập</a>
        </div>
    </div> -->

    <div class="navbar">
        <div><strong>Test1</strong></div>
        <div>
            <a href="index.php">Sinh Viên</a> <!-- Trang index.php -->
            <a href="hocphan.php">Học Phần</a> <!-- Trang học phần -->
            <a href="giohang.php"><strong>Đăng Kí ( )</strong></a> <!-- Trang giỏ hàng học phần -->
            <a href="login.php">Đăng Nhập</a> <!-- Trang đăng nhập -->
        </div>
    </div>

    <div class="container">
        <h2>TRANG SINH VIÊN</h2>
        <a href="create.php" class="add-btn">Add Student</a>
        <table>
            <tr>
                <th>MaSV</th>
                <th>HoTen</th>
                <th>GioiTinh</th>
                <th>NgaySinh</th>
                <th>Hình</th>
                <th>MaNganh</th>
                <th>Hành động</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['MaSV'] ?></td>
                <td><?= $row['HoTen'] ?></td>
                <td><?= $row['GioiTinh'] ?></td>
                <td><?= $row['NgaySinh'] ?></td>
                <td><img src="<?= $row['Hinh'] ?>" alt="Hình sinh viên"></td>
                <td><?= $row['TenNganh'] ?></td>
                <td class="action-links">
                    <a href="edit.php?MaSV=<?= $row['MaSV'] ?>">Edit</a>
                    <a href="detail.php?MaSV=<?= $row['MaSV'] ?>">Details</a>
                    <a href="delete.php?MaSV=<?= $row['MaSV'] ?>" onclick="return confirm('Xác nhận xóa?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
</html>
