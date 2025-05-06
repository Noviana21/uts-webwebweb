<?php
    session_start();

    if (!isset($_SESSION['data'])) {
        $_SESSION['data'] = [];
    }

    $allowed_file = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $errors = [];

    if(isset($_POST['submit']) && isset($_POST['nama']) && is_array($_POST['nama'])) {
        $names = $_POST['nama'];
        $images = $_FILES['gambar'];

        for ($i = 0; $i < count($names); $i++) {
            $name = $names[$i];
            $image = $images['name'][$i];
            $tmp_name = $images['tmp_name'][$i];
            $file = strtolower(pathinfo($image, PATHINFO_EXTENSION));

            if(!in_array($file, $allowed_file)) {
                $errors[] = "Hanya file gambar yang diperbolehkan (" . implode(", ", $allowed_file) . ")";
                continue;
            }

            $target_path = 'uploads/' . time() . '_' . basename($image);
            move_uploaded_file($tmp_name, $target_path);

            $_SESSION['data'][] = [
                'nama' => $name,
                'gambar' => basename($target_path)
            ];
        }
    }

    if(isset($_POST['reset']) && $_POST['reset'] === 'true') {
        foreach ($_SESSION['data'] as $item) {
            $filePath = 'uploads/' . $item['gambar'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        $_SESSION['data'] = [];
        header("Location: UTS2025.php");
        exit();
    }
?>

<!DOCTYPE html> 
<head>
    <style>
        .merah {
            color: red;
        }

        h1 {
            font-size: 30px;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
        }

        input[type="text"] {
            width: 220px;
        }

        .kanan {
            padding-left: 20px;
        }

        button {
            background-color:rgb(30, 91, 92);
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 20px;
            margin-right: 6px;
            padding: 8px;
            border-radius: 6px;
        }

        button:hover {
            background-color: rgb(6, 62, 63);
            color: white;
        }

        .red {
            background-color: #800;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 20px;
            margin-right: 6px;
            padding: 8px;
            border-radius: 6px;
        }
        .red:hover {
            background-color: rgb(82, 12, 12);
            color: white;
        }

        h2 {
            color: rgb(30, 91, 92);
        }
    </style>
    <title>UTS 2025</title>
</head>
<body>
    <h2>Form Dinamis: Nama & Gambar</h2>
    <?php if (!empty($errors)): ?>
        <p class="merah"><b>Hanya file gambar yang diperbolehkan (jpg, jpeg, png, gif, webp).</b></p>
    <?php endif; ?>
    <form action="" method="post" enctype="multipart/form-data">
        <table>
            <tr class="baris">
                <td><b>Nama: </b> <br> <input type="text" name="nama[]" required></td>
                <td class="kanan"><b>Gambar: </b> <br> <input type="file" name="gambar[]" required></td>
            </tr>
        </table>
        
        <button type="button" name="tambah" onclick="tambah_baris()">Tambah Input</button>
        <button type="submit" name="submit">Submit</button>
        <button type="button" name="reset" onclick="konfirmasi()" class="red">Reset Data</button>
        <br>
        <br>
        <a href="database.php">Lihat Tabel Data</a>
    </form>

    <script>
        function tambah_baris() {
            const table = document.querySelector('table');

            document.querySelectorAll('tr.baris').forEach(tr => {
                const hapus_baris = tr.querySelector('button');
                if (!hapus_baris) {
                    const td = document.createElement('td');
                    td.innerHTML = `<button type="button" onclick="hapus(this)">Hapus</button>`;
                    tr.appendChild(td);
                }

                const first_button = tr.querySelector('button');
                if (first_button && !first_button.classList.contains('red')) {
                    first_button.classList.add('red');
                }
            });

            const baris_baru = document.createElement('tr');
            baris_baru.className = 'baris';
            baris_baru.innerHTML = `
                <td><b>Nama: </b> <br> <input type="text" name="nama[]" required></td>
                <td class="kanan"><b>Gambar: </b> <br> <input type="file" name="gambar[]" required></td>
                <td><button type="button" onclick="hapus(this)" class="red">Hapus</button></td>
            `;

            table.appendChild(baris_baru);
        }

        function hapus(button) {
            button.parentElement.parentElement.remove();

            const rows = document.querySelectorAll('tr.baris');

            if (rows.length === 1) {
                const akhir = rows[0];
                if (akhir.querySelector('button')) {
                    akhir.querySelector('button').parentElement.remove(); 
                }
            }
        }

        function konfirmasi() {
            if (confirm('Yakin ingin menghapus semua data?')) {
                const form = document.createElement('form');
                form.method = 'post';
                form.action = '';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'reset';
                input.value = 'true';
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
