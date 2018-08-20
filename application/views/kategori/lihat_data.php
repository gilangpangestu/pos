<h3>Kategori Barang</h3>
<?php
echo anchor('kategori/post','Tambah Data')
?>
<table border="2">
    <tr><th>No</th><th>Nama Kategori</th><th colspan="2">Operasi</th></tr>
    <?php
    $no=1;
    foreach ($record->result() as $r)
    {    
        echo "<tr>"
        . "<td>$no</td>"
        . "<td>$r->nama_kategori</td>"
        . "<td>".anchor('kategori/edit/'.$r->id_kategori,'Edit')."</td>"
        . "<td>".anchor('kategori/delete/'.$r->id_kategori,'Delete')."</td>"
        . "</tr>";
        $no++;
    }
    ?>
</table>