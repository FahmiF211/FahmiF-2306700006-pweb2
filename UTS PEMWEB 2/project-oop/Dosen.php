<?php
require_once "SivitasAkademik.php";

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
?>