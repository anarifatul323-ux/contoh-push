<?php defined('BASEPATH') OR exit('No direct script access allowed');
$err = array('<p class="err">', '</p>');
?>
<div class="card max-w-2xl p-6">
  <?= form_open('pelanggan/pinjam') ?>
    <label class="lbl">Barang *</label>
    <select name="barang_id" id="barang_id" class="input">
      <option value="">-- Pilih barang --</option>
      <?php foreach ($barang as $b): ?>
        <option value="<?= $b->id ?>" data-stok="<?= $b->stok ?>" data-satuan="<?= html_escape($b->satuan) ?>" <?= set_value('barang_id', $pilih) == $b->id ? 'selected' : '' ?>>
          <?= html_escape($b->kode_barang . ' - ' . $b->nama_barang) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <p id="info-stok" class="mt-1 text-xs text-slate-500"></p>
    <?= form_error('barang_id', $err[0], $err[1]) ?>

    <label class="lbl mt-4">Jumlah *</label>
    <input type="text" name="jumlah" value="<?= html_escape(set_value('jumlah', '1')) ?>" class="input">
    <?= form_error('jumlah', $err[0], $err[1]) ?>

    <div class="mt-4 grid gap-4 sm:grid-cols-2">
      <div>
        <label class="lbl">Tanggal pinjam *</label>
        <input type="date" name="tanggal_pinjam" value="<?= html_escape(set_value('tanggal_pinjam', date('Y-m-d'))) ?>" class="input">
        <?= form_error('tanggal_pinjam', $err[0], $err[1]) ?>
      </div>
      <div>
        <label class="lbl">Tanggal kembali *</label>
        <input type="date" name="tanggal_kembali" value="<?= html_escape(set_value('tanggal_kembali', date('Y-m-d', strtotime('+7 days')))) ?>" class="input">
        <?= form_error('tanggal_kembali', $err[0], $err[1]) ?>
      </div>
    </div>

    <label class="lbl mt-4">Keperluan *</label>
    <input type="text" name="keperluan" value="<?= html_escape(set_value('keperluan')) ?>" class="input" placeholder="mis. Dokumentasi acara sekolah">
    <?= form_error('keperluan', $err[0], $err[1]) ?>

    <div class="mt-6 flex gap-2">
      <button class="btn btn-primary">Ajukan Peminjaman</button>
      <a href="<?= site_url('pelanggan') ?>" class="btn btn-light">Batal</a>
    </div>
  <?= form_close() ?>
</div>

<script>
(function () {
  var sel = document.getElementById('barang_id'), info = document.getElementById('info-stok');
  function upd() {
    var o = sel.options[sel.selectedIndex];
    info.textContent = o && o.dataset.stok !== undefined ? 'Stok tersedia: ' + o.dataset.stok + ' ' + o.dataset.satuan : '';
  }
  sel.addEventListener('change', upd); upd();
})();
</script>