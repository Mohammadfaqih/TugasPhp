<?php
$hasilBarang = [];
if (file_exists('data_barang.txt')) {
  $hasilBarang = json_decode(file_get_contents('data_barang.txt'), true);
}

if (isset($_POST['kirim'])) {
  $nama = trim($_POST['nama']);
  $kategori = trim($_POST['kategori']);
  $harga = intval(trim($_POST['harga'])); // Mengonversi harga menjadi integer

  // Validasi input
  if (!empty($nama) && !empty($kategori) && $harga > 0) {
    $hasilBarang[] = [
      'nama' => $nama,
      'kategori' => $kategori,
      'harga' => $harga
    ];
    file_put_contents('data_barang.txt', json_encode($hasilBarang));
    header('Location: pekan03.php');
    exit;
  }
}

if (isset($_GET['aksi'])) {
  $key = $_GET['key'];
  if ($_GET['aksi'] == 'hapus') {
    unset($hasilBarang[$key]);
    $hasilBarang = array_values($hasilBarang); // Mengatur ulang indeks setelah penghapusan
    file_put_contents('data_barang.txt', json_encode($hasilBarang));
    header('Location: pekan03.php');
    exit;
  } elseif ($_GET['aksi'] == 'edit') {
    $nama = $hasilBarang[$key]['nama'];
    $kategori = $hasilBarang[$key]['kategori'];
    $harga = $hasilBarang[$key]['harga'];
  }
}

if (isset($_POST['edit'])) {
  $key = $_POST['key'];
  $nama = trim($_POST['nama']);
  $kategori = trim($_POST['kategori']);
  $harga = intval(trim($_POST['harga'])); // Mengonversi harga menjadi integer

  if (!empty($nama) && !empty($kategori) && $harga > 0) {
    $hasilBarang[$key] = [
      'nama' => $nama,
      'kategori' => $kategori,
      'harga' => $harga
    ];
    file_put_contents('data_barang.txt', json_encode($hasilBarang));
    header('Location: pekan03.php');
    exit;
  }
}

$hasilCari = $hasilBarang;
if (isset($_POST['submit_cari'])) {
  $cari = strtolower(trim($_POST['cari']));
  $hasilCari = array_filter($hasilBarang, function ($item) use ($cari) {
    return (strpos(strtolower($item['nama']), $cari) !== false) || (strpos(strtolower($item['kategori']), $cari) !== false);
  });
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Barang</title>
</head>

<style>
  table {
    width: 50%;
    margin: auto;
    border-collapse: collapse;
  }

  table,
  th,
  td {
    border: 1px solid black;
    padding: 1rem;
  }

  label {
    margin-top: 10px;
    display: block;
  }

  h1 {
    width: 50%;
    margin: auto;
    margin-bottom: 1rem;
  }

  

</style>

<body>
  <!-- Form untuk tambah atau edit barang -->
  <form action="" method="post">
    <label for="nama">Nama Barang</label>
    <input type="text" name="nama" id="nama" value="<?= isset($nama) ? htmlspecialchars($nama) : '' ?>" required>
    <br>
    <label for="kategori">Kategori Barang</label>
    <input type="text" name="kategori" id="kategori" value="<?= isset($kategori) ? htmlspecialchars($kategori) : '' ?>" required>
    <br>
    <label for="harga">Harga Barang</label>
    <input type="number" name="harga" id="harga" value="<?= isset($harga) ? htmlspecialchars($harga) : '' ?>" required>
    <br>
    <?php if (isset($key)) : ?>
      <input type="hidden" name="key" value="<?= $key ?>">
      <input type="submit" name="edit" value="Edit">
    <?php else : ?>
      <input type="submit" name="kirim" value="Kirim">
    <?php endif; ?>
  </form>
  <br>
  <!-- Form pencarian -->
  <form action="" method="post">
    <label for="cari">Cari Barang</label>
    <input type="text" name="cari" id="cari">
    <br>
    <input type="submit" name="submit_cari" value="Cari">
  </form>
  <br>
  <!-- Tabel daftar barang -->
  <h1>Daftar Barang</h1>
  <table border="1" cellpadding="5" cellspacing="0">
    <tr>
      <th>No</th>
      <th>Nama Barang</th>
      <th>Kategori Barang</th>
      <th>Harga Barang</th>
      <th>Aksi</th>
    </tr>
    <?php if (!empty($hasilCari)) : ?>
      <?php foreach ($hasilCari as $key => $value) : ?>
        <tr align="center">
          <td><?= $key + 1 ?></td>
          <td><?= htmlspecialchars($value['nama']) ?></td>
          <td><?= htmlspecialchars($value['kategori']) ?></td>
          <td><?= htmlspecialchars(number_format($value['harga'], 0, ',', '.')) ?></td> <!-- Format harga -->
          <td>
            <a href="<?= $_SERVER['PHP_SELF'] . '?aksi=edit&key=' . $key ?>">Edit</a> |
            <a href="<?= $_SERVER['PHP_SELF'] . '?aksi=hapus&key=' . $key ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">Hapus</a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else : ?>
      <tr>
        <td colspan="5" align="center">Tidak ada data barang yang ditemukan.</td>
      </tr>
    <?php endif; ?>
  </table>
</body>

</html>