<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lihat Data</title>

    <style>
        table {
            border-collapse: collapse;
            width: 80%;
        }

        table, th, td {
            border: 2px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: rgb(130, 199, 200);
        }

        th:nth-child(1), td:nth-child(1) {
            width: 10%;
        }

        th:nth-child(2), td:nth-child(2) {
            width: 40%;
        }

        th:nth-child(3), td:nth-child(3) {
            width: 30%;
        }

        img {
            object-fit: cover;
        }
    </style>
</head>
<body>
    <h2>Data yang Disimpan</h2>

    <?php if (empty($_SESSION['data'])): ?>
        <p>Belum ada data.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Nomor</th>
                <th>Nama</th>
                <th>Gambar</th>
            </tr>
            <?php 
            $nomor = 1;
            foreach ($_SESSION['data'] as $item): 
            ?>
                <tr>
                    <td><?php echo $nomor++ ?></td>
                    <td><?php echo htmlspecialchars($item['nama']); ?></td>
                    <td><img src="uploads/<?php echo $item['gambar']; ?>" width="150px" height="150px"></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <br>
    <a href="UTS2025.php">Kembali ke Form</a>
</body>
</html>