<?php
require_once "Dosen.php";
require_once "Mahasiswa.php";

$dosen1 = new Dosen("Pak Budi", "123456");
$mhs1 = new Mahasiswa("Ani", "987654");

echo "Dosen: " . $dosen1->getNama() . " - NIDN: " . $dosen1->getNidn();
echo "<br>";
echo "Mahasiswa: " . $mhs1->getNama() . " - NIM: " . $mhs1->getNim();
?>