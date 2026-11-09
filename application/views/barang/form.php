<?php defined('BASEPATH') OR exit('No direct script access allowed');
$edit = $barang !== NULL;
$v = function ($f, $d = '') use ($barang) { return set_value($f, $barang ? $barang->$f : $d); };
$err = array('<p class="err">', '</p>');
?>
<div class="card p-6 max-w-3xl">
  <?= form_open($edit ? 'barang/edit/' . $barang->id : 'barang/tambah') ?>
  <div class="grid gap-4 md:grid-cols-2">
    <div>
      <label class="lbl">Kode Barang *</label>
      <input type="text" name="kode_barang" value="<?= html_escape($v('kode_barang')) ?>" class="input" placeholder="mis. BRG-004">
      <?= form_error('kode_barang', $err[0], $err[1]) ?>
    </div>
    <div>
      <label class="lbl">Nama Barang *</label>
      <input type="text" name="nama_barang" value="<?= html_escape($v('nama_barang')) ?>" class="input">
      <?= form_error('nama_barang', $err[0], $err[1]) ?>
    </div>
    <div>
      <label class="lbl">Kategori *</label>
      <select name="kategori_id" class="input"><option value="">-- Pilih kategori --</option>
        <?php foreach ($kategori as $o): ?><option value="<?= $o->id ?>" <?= $v('kategori_id') == $o->id ? 'selected' : '' ?>><?= html_escape($o->nama_kategori) ?></option><?php endforeach; ?></select>
      <?= form_error('kategori_id', $err[0], $err[1]) ?>
    </div>
    <div>
      <label class="lbl">Lokasi Penyimpanan *</label>
      <select name="lokasi_id" class="input"><option value="">-- Pilih lokasi --</option>
        <?php foreach ($lokasi as $o): ?><option value="<?= $o->id ?>" <?= $v('lokasi_id') == $o->id ? 'selected' : '' ?>><?= html_escape($o->nama_lokasi) ?></option><?php endforeach; ?></select>
      <?= form_error('lokasi_id', $err[0], $err[1]) ?>
    </div>
    <div>
      <label class="lbl">Kondisi *</label>
      <select name="kondisi_id" class="input"><option value="">-- Pilih kondisi --</option>
        <?php foreach ($kondisi as $o): ?><option value="<?= $o->id ?>" <?= $v('kondisi_id') == $o->id ? 'selected' : '' ?>><?= html_escape($o->nama_kondisi) ?></option><?php endforeach; ?></select>
      <?= form_error('kondisi_id', $err[0], $err[1]) ?>
    </div>
    <div>
      <label class="lbl">Satuan *</label>
      <input type="text" name="satuan" value="<?= html_escape($v('satuan', 'unit')) ?>" class="input">
      <?= form_error('satuan', $err[0], $err[1]) ?>
    </div>
    <div>
      <label class="lbl"><?= $edit ? 'Stok Saat Ini' : 'Jumlah Awal *' ?></label>
      <?php if ($edit): ?>
        <input type="text" value="<?= $barang->stok ?>" class="input bg-slate-100" disabled>
        <p class="text-xs text-slate-500 mt-1">Stok hanya berubah lewat menu Barang Masuk / Keluar.</p>
      <?php else: ?>
        <input type="text" name="stok" value="<?= html_escape(set_value('stok', '0')) ?>" class="input">
        <?= form_error('stok', $err[0], $err[1]) ?>
      <?php endif; ?>
    </div>
    <div>
      <label class="lbl">Keterangan</label>
      <input type="text" name="keterangan" value="<?= html_escape($v('keterangan')) ?>" class="input">
      <?= form_error('keterangan', $err[0], $err[1]) ?>
    </div>
  </div>
  <div class="mt-6 flex gap-2">
    <button class="btn btn-primary">Simpan</button>
    <a href="<?= site_url('barang') ?>" class="btn btn-light">Batal</a>
  </div>
  <?= form_close() ?>
</div>