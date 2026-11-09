<?php defined('BASEPATH') OR exit('No direct script access allowed');
$badge = array(
    'masuk'         => array('Barang Masuk',  'bg-emerald-100 text-emerald-700'),
    'keluar'        => array('Barang Keluar', 'bg-rose-100 text-rose-700'),
    'stok_awal'     => array('Stok Awal',     'bg-teal-100 text-teal-700'),
    'ubah_kondisi'  => array('Ubah Kondisi',  'bg-amber-100 text-amber-700'),
    'pindah_lokasi' => array('Pindah Lokasi', 'bg-sky-100 text-sky-700'),
);
$pills = array('' => 'Semua', 'masuk' => 'Masuk', 'keluar' => 'Keluar', 'stok_awal' => 'Stok awal',
               'ubah_kondisi' => 'Ubah kondisi', 'pindah_lokasi' => 'Pindah lokasi');
$jenis_aktif = (string) $f['jenis'];

$tot_masuk = 0; $tot_keluar = 0;
foreach ($riwayat as $r) {
    if ($r->jenis === 'masuk')  { $tot_masuk  += (int) $r->jumlah; }
    if ($r->jenis === 'keluar') { $tot_keluar += (int) $r->jumlah; }
}
?>

<!-- Filter jenis + cetak -->
<div class="flex flex-wrap items-center justify-between gap-3 print:hidden">
  <div class="flex flex-wrap gap-2">
    <?php foreach ($pills as $k => $l):
        $q  = http_build_query(array_filter(array_merge($f, array('jenis' => $k))));
        $on = ($jenis_aktif === (string) $k);
    ?>
      <a href="<?= site_url('riwayat') . ($q ? '?' . $q : '') ?>"
         class="rounded-full px-4 py-1.5 text-sm font-medium transition <?= $on ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-emerald-50 hover:text-emerald-700' ?>"><?= $l ?></a>
    <?php endforeach; ?>
  </div>
  <button onclick="window.print()" class="btn btn-light">Cetak</button>
</div>

<!-- Ringkasan -->
<div class="grid grid-cols-3 gap-4">
  <div class="card p-4 sm:p-5">
    <div class="text-xs text-slate-500 sm:text-sm">Jumlah Catatan</div>
    <div class="mt-1 text-2xl font-bold text-slate-900"><?= number_format(count($riwayat)) ?></div>
  </div>
  <div class="card p-4 sm:p-5">
    <div class="text-xs text-slate-500 sm:text-sm">Total Unit Masuk</div>
    <div class="mt-1 text-2xl font-bold text-emerald-600">+<?= number_format($tot_masuk) ?></div>
  </div>
  <div class="card p-4 sm:p-5">
    <div class="text-xs text-slate-500 sm:text-sm">Total Unit Keluar</div>
    <div class="mt-1 text-2xl font-bold text-rose-600">-<?= number_format($tot_keluar) ?></div>
  </div>
</div>

<!-- Filter barang & tanggal -->
<div class="card p-5 print:hidden">
  <form method="get" action="<?= site_url('riwayat') ?>">
    <input type="hidden" name="jenis" value="<?= html_escape($jenis_aktif) ?>">
    <div class="grid gap-4 md:grid-cols-4">
      <div class="md:col-span-2">
        <label class="lbl">Barang</label>
        <select name="barang_id" class="input">
          <option value="">Semua barang</option>
          <?php foreach ($barang as $b): ?>
            <option value="<?= $b->id ?>" <?= $f['barang_id'] == $b->id ? 'selected' : '' ?>><?= html_escape($b->kode_barang . ' - ' . $b->nama_barang) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="lbl">Dari tanggal</label>
        <input type="date" name="dari" value="<?= html_escape($f['dari']) ?>" class="input">
      </div>
      <div>
        <label class="lbl">Sampai tanggal</label>
        <input type="date" name="sampai" value="<?= html_escape($f['sampai']) ?>" class="input">
      </div>
    </div>
    <div class="mt-4 flex gap-2">
      <button class="btn btn-primary">Terapkan Filter</button>
      <a href="<?= site_url('riwayat') ?>" class="btn btn-light">Reset</a>
    </div>
  </form>
</div>

<!-- Penjelasan singkat membaca tabel -->
<p class="text-sm text-slate-500">
  <span class="font-medium text-slate-700">Cara membaca:</span>
  <b>Jumlah</b> = banyak unit yang ditambah (+) atau dikurangi (-).
  <b>Stok Sebelum</b> dan <b>Stok Sesudah</b> = stok barang sebelum dan sesudah kejadian itu.
</p>

<!-- Tabel riwayat -->
<div class="card overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full min-w-[860px]">
      <thead>
        <tr>
          <th class="th">Tanggal &amp; Jam</th>
          <th class="th">Barang</th>
          <th class="th">Jenis</th>
          <th class="th text-right">Jumlah</th>
          <th class="th text-right">Stok Sebelum</th>
          <th class="th text-right">Stok Sesudah</th>
          <th class="th">Keterangan</th>
          <th class="th">Petugas</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($riwayat as $r):
          $b  = isset($badge[$r->jenis]) ? $badge[$r->jenis] : array(str_replace('_', ' ', $r->jenis), 'bg-slate-100 text-slate-600');
          $ts = strtotime($r->created_at);
      ?>
        <tr class="hover:bg-slate-50/70">
          <td class="td whitespace-nowrap">
            <div class="font-medium text-slate-800"><?= date('d/m/Y', $ts) ?></div>
            <div class="text-xs text-slate-400"><?= date('H:i', $ts) ?> WIB</div>
          </td>
          <td class="td">
            <div class="font-semibold text-slate-900"><?= html_escape($r->nama_barang) ?></div>
            <div class="font-mono text-xs text-slate-400"><?= html_escape($r->kode_barang) ?></div>
          </td>
          <td class="td whitespace-nowrap">
            <span class="rounded-full px-2.5 py-1 text-xs font-medium <?= $b[1] ?>"><?= $b[0] ?></span>
          </td>
          <td class="td whitespace-nowrap text-right">
            <?php if ($r->jenis === 'masuk' || $r->jenis === 'stok_awal'): ?>
              <span class="font-bold text-emerald-600">+<?= $r->jumlah ?></span>
              <span class="text-xs text-slate-400"><?= html_escape($r->satuan) ?></span>
            <?php elseif ($r->jenis === 'keluar'): ?>
              <span class="font-bold text-rose-600">-<?= $r->jumlah ?></span>
              <span class="text-xs text-slate-400"><?= html_escape($r->satuan) ?></span>
            <?php else: ?>
              <span class="text-slate-300">-</span>
            <?php endif; ?>
          </td>
          <td class="td text-right text-slate-500"><?= $r->stok_sebelum ?></td>
          <td class="td text-right font-bold text-slate-900"><?= $r->stok_sesudah ?></td>
          <td class="td text-slate-600"><?= $r->keterangan ? html_escape($r->keterangan) : '-' ?></td>
          <td class="td whitespace-nowrap text-slate-600"><?= $r->nama_user ? html_escape($r->nama_user) : '-' ?></td>
        </tr>
      <?php endforeach; ?>

      <?php if (!$riwayat): ?>
        <tr>
          <td colspan="8" class="td py-14 text-center">
            <div class="font-semibold text-slate-700">Tidak ada riwayat</div>
            <p class="mt-1 text-sm text-slate-400">Coba ubah atau reset filter pencarian.</p>
          </td>
        </tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php if (count($riwayat) >= 300): ?>
    <div class="border-t border-slate-100 px-5 py-3 text-center text-xs text-slate-400">Menampilkan 300 catatan terbaru. Gunakan filter tanggal untuk mempersempit.</div>
  <?php endif; ?>
</div>