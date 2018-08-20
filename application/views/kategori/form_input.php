<h3>TAMBAH DATA KATEGORI</h3>
<?php
echo form_open('kategori/post');
?>
<table border="1">
    <tr><td>Nama Kategori</td>
        <td><input type="text" name="kategori" placeholder="kategori">
        </td></tr>
    <tr><td colspan="2"><button type="submit" name="submit">Simpan</button></td></tr>
</table>
</form>