<?php
session_start();
$conn = new mysqli("localhost", "root", "", "test1");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$sinhvien_id = $_SESSION['sinhvien_id'];

// Xóa học phần đã đăng ký
if (isset($_GET['delete'])) {
    $hocphan_id = $_GET['delete'];
    $sql_delete = "DELETE FROM ChiTietDangKy WHERE MaDK IN (SELECT MaDK FROM DangKy WHERE MaSV = '$sinhvien_id') AND MaHP = '$hocphan_id'";
    $conn->query($sql_delete);
}

// Xóa tất cả các học phần
if (isset($_GET['delete_all'])) {
    $sql_delete_all = "DELETE FROM ChiTietDangKy WHERE MaDK IN (SELECT MaDK FROM DangKy WHERE MaSV = '$sinhvien_id')";
    $conn->query($sql_delete_all);
}

// Lấy danh sách học phần đã đăng ký
$sql = "SELECT hocphan.MaHP, hocphan.TenHP, hocphan.SoTinChi 
        FROM hocphan
        INNER JOIN ChiTietDangKy ON hocphan.MaHP = ChiTietDangKy.MaHP
        INNER JOIN DangKy ON ChiTietDangKy.MaDK = DangKy.MaDK
        WHERE DangKy.MaSV = '$sinhvien_id'";
$result = $conn->query($sql);

// Tính tổng số học phần và số tín chỉ
$so_hoc_phan = $result->num_rows;
$tong_so_tin_chi = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Học Phần Đã Đăng Ký</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Học Phần Đã Đăng Ký</h2>
    <table>
        <tr>
            <th>Mã Học Phần</th>
            <th>Tên Học Phần</th>
            <th>Số Tín Chỉ</th>
            <th>Hành Động</th>
        </tr>
        <?php
        if ($so_hoc_phan > 0) {
            // Reset lại pointer của kết quả truy vấn để lặp qua
            $result->data_seek(0);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['MaHP']) . "</td>";
                echo "<td>" . htmlspecialchars($row['TenHP']) . "</td>";
                echo "<td>" . htmlspecialchars($row['SoTinChi']) . "</td>";
                echo "<td><a href='giohang.php?delete=" . $row['MaHP'] . "'>Xóa</a></td>";
                echo "</tr>";
                $tong_so_tin_chi += $row['SoTinChi'];
            }
            echo "<tr class='total'><td colspan='3'>Số học phần: $so_hoc_phan</td><td>Tổng số tín chỉ: $tong_so_tin_chi</td></tr>";
        } else {
            echo "<tr><td colspan='4'>Chưa có học phần nào được đăng ký.</td></tr>";
        }
        ?>
    </table>
    <br>
    <a href="giohang.php?delete_all=1">Xóa Tất Cả Học Phần</a>
</body>
</html>

<?php $conn->close(); ?>
