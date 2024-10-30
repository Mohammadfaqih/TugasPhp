<?php
$daftarBuah = [
  "Apel" => 10000,
  "Pisang" => 5000,
  "Mangga" => 15000,
];

echo "masukkan nama buah(Apel, Pisang, Mangga)\n";
$namaBuah  =  (String)readline("Masukan pilihan: ");
function cekBuah($daftarBuah, $namaBuah) {
  foreach ($daftarBuah as $buah => $hargaBuah) {
    if ($namaBuah == $buah) {
      return true;
    }
  }
  return false;
}

$buahAda = cekBuah($daftarBuah, $namaBuah);

if ($buahAda == true) {
  echo "masukan jumlah yang diinginkan";
  $jmlhBuah = (int)readline("masukan jumlah: ");

  $hargaBuah = $daftarBuah[$namaBuah];
  $totalHarga = $jmlhBuah * $hargaBuah;
  echo "buah $totalHarga" ;
}else {
  echo "maaf buahnya lagi kosong";
}

?>