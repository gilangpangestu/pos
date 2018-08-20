<h3>EDIT DATA KATEGORI</h3>
<?php
echo form_open('kategori/edit');
?>
<input type="hidden" value="<?php echo $record['id_kategori']?>" name="id">
<table border="1">
    <tr><td>Nama Kategori</td>
        <td><input type="text" name="kategori" placeholder="kategori"
                   value="<?php echo $record['nama_kategori']?>">
        </td></tr>
    <tr><td colspan="2"><button type="submit" name="submit">Simpan</button></td></tr>
</table>
</form>