<?php defined('BASEPATH') OR exit('No direct script access allowed');
$masuk = ($jenis === 'masuk');
$err = array('<p class="err">', '</p>');
?>
<div class="grid lg:grid-cols-5 gap-6">
  <div class="card p-6 lg:col-span-2 h-fit">
    <h2 class="font-semibold mb-4">Catat Barang <?= $masuk ? 'Masuk' : 'Keluar' ?></h2>
    <?= form_open('transaksi/' . $jenis) ?>
      <label class="lbl">Barang *</label>
      <select name="barang_id" id="barang_id" class="input">
        <option value="">-- Pilih barang --</option>
        <?php foreach ($barang as $b): ?>
          <option value="<?= $b->id ?>" data-stok="<?= $b->stok ?>" data-satuan="<?= html_escape($b->satuan) ?>" <?= set_value('barang_id') == $b->id ? 'selected' : '' ?>>
            <?= html_escape($b->kode_barang . ' - ' . $b->nama_barang) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <p id="info-stok" class="text-xs text-slate-500 mt-1"></p>
      <?= form_error('barang_id', $err[0], $err[1]) ?>

      <label class="lbl mt-4">Jumlah *</label>
      <input type="text" name="jumlah" value="<?= html_escape(set_value('jumlah')) ?>" class="input" placeholder="mis. 3">
      <?= form_error('jumlah', $err[0], $err[1]) ?>

      <label class="lbl mt-4">Tanggal *</label>
      <input type="date" name="tanggal" value="<?= html_escape(set_value('tanggal', date('Y-m-d'))) ?>" class="input">
      <?= form_error('tanggal', $err[0], $err[1]) ?>

      <label class="lbl mt-4">Keterangan</label>
      <input type="text" name="keterangan" value="<?= html_escape(set_value('keterangan')) ?>" class="input" placeholder="<?= $masuk ? 'mis. Pembelian / hibah' : 'mis. Dipinjam kelas XII' ?>">
      <?= form_error('keterangan', $err[0], $err[1]) ?>

      <button class="btn btn-primary mt-5">Simpan Transaksi</button>
    <?= form_close() ?>
  </div>

  <div class="card overflow-hidden lg:col-span-3">
    <div class="px-5 py-4 font-semibold">Transaksi Terakhir</div>
    <div class="overflow-x-auto"><table class="w-full">
      <thead><tr><th class="th">Tanggal</th><th class="th">Barang</th><th class="th text-right">Jumlah</th><th class="th">Petugas</th></tr></thead>
      <tbody>
      <?php foreach ($daftar as $d): ?>
        <tr>
          <td class="td whitespace-nowrap"><?= date('d/m/Y', strtotime($d->tanggal)) ?></td>
          <td class="td"><?= html_escape($d->nama_barang) ?><div class="text-xs text-slate-400"><?= html_escape($d->keterangan) ?></div></td>
          <td class="td text-right font-semibold <?= $masuk ? 'text-emerald-600' : 'text-rose-600' ?>"><?= $masuk ? '+' : '-' ?><?= $d->jumlah ?> <?= html_escape($d->satuan) ?></td>
          <td class="td"><?= html_escape($d->nama_user) ?></td>
        </tr>
      <?php endforeach; if (!$daftar): ?>
        <tr><td colspan="4" class="td text-center text-slate-400 py-6">Belum ada transaksi.</td></tr>
      <?php endif; ?>
      </tbody></table></div>
  </div>
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