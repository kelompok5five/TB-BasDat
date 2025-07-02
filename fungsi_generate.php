<?php
function generateID($conn, $table, $kolom, $prefix, $digit = 3) {
    $num = 1;
    do {
        $id = $prefix . str_pad($num, $digit, '0', STR_PAD_LEFT);
        $cek = mysqli_query($conn, "SELECT COUNT(*) as jml FROM $table WHERE $kolom = '$id'");
        $hasil = mysqli_fetch_assoc($cek);
        $num++;
    } while ($hasil['jml'] > 0);

    return $id;
}
?>
