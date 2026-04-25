<?php
// 1. Class Induk
class SivitasAkademik {
    protected $nama;

    public function __construct($nama) {
        $this->nama = $nama;
    }

    public function getNama() {
        return $this->nama;
    }
}

// 2. Class Anak: Dosen
class Dosen extends SivitasAkademik {
    private $nidn;

    public function __construct($nama, $nidn) {
        parent::__construct($nama);
        $this->nidn = $nidn;
    }

    public function getNidn() {
        return $this->nidn;
    }
}

// 3. Class Anak: Mahasiswa
class Mahasiswa extends SivitasAkademik {
    private $nim;

    public function __construct($nama, $nim) {
        parent::__construct($nama);
        $this->nim = $nim;
    }

    public function getNim() {
        return $this->nim;
    }
}

// 4. Instansiasi Object
$dosen1 = new Dosen("Pak Budi", "123456");
$mhs1 = new Mahasiswa("Ani", "987654");

// 5. Output
echo "Dosen: " . $dosen1->getNama() . " - NIDN: " . $dosen1->getNidn();
echo "<br>";
echo "Mahasiswa: " . $mhs1->getNama() . " - NIM: " . $mhs1->getNim();
?>