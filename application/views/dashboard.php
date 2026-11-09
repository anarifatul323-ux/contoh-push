<?php defined('BASEPATH') OR exit('No direct script access allowed');
$hari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
$bln  = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
$tgl  = $hari[(int) date('w')] . ', ' . date('j') . ' ' . $bln[(int) date('n')] . ' ' . date('Y');
$depan = explode(' ', trim($this->session->userdata('nama')));

$cards = array(
    array('Jenis Barang',     $stat->jenis,        '&#9776;', 'bg-emerald-100 text-emerald-700', 'barang terdaftar'),
    array('Total Stok',       $stat->total_stok,   '&Sigma;', 'bg-teal-100 text-teal-700',       'seluruh unit'),
    array('Masuk Bulan Ini',  $stat->masuk_bulan,  '&darr;',  'bg-sky-100 text-sky-700',         'unit masuk'),
    array('Keluar Bulan Ini', $stat->keluar_bulan, '&uarr;',  'bg-rose-100 text-rose-700',       'unit keluar'),
);
$meta = array(
    'masuk'         => array('Barang masuk',  'bg-emerald-500', '+'),
    'keluar'        => array('Barang keluar', 'bg-rose-500',    '-'),
    'stok_awal'     => array('Stok awal',     'bg-slate-400',   ''),
    'ubah_kondisi'  => array('Ubah kondisi',  'bg-amber-400',   ''),
    'pindah_lokasi' => array('Pindah lokasi', 'bg-sky-400',     ''),
);
?>
<!-- Sapaan -->
<div class="flex flex-wrap items-end justify-between gap-2">
  <div>
    <h2 class="text-2xl font-bold text-slate-900">Halo, <?= html_escape($depan[0]) ?></h2>
    <p class="text-sm text-slate-500"><?= $tgl ?></p>
  </div>
</div>

<!-- Pemberitahuan -->
<?php if ($menunggu > 0): ?>
  <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4">
    <div class="text-sm text-amber-900"><span class="font-semibold"><?= $menunggu ?> pengajuan peminjaman</span> menunggu persetujuan Anda.</div>
    <a href="<?= site_url('peminjaman?status=menunggu') ?>" class="btn bg-amber-500 text-white hover:bg-amber-600">Tinjau</a>
  </div>
<?php endif; ?>
<?php if ($terlambat > 0): ?>
  <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4">
    <div class="text-sm text-rose-900"><span class="font-semibold"><?= $terlambat ?> peminjaman terlambat</span> belum dikembalikan.</div>
    <a href="<?= site_url('peminjaman?status=disetujui') ?>" class="btn bg-rose-500 text-white hover:bg-rose-600">Lihat</a>
  </div>
<?php endif; ?>

<!-- Kartu statistik -->
<div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
  <?php foreach ($cards as $c): ?>
    <div class="card p-5">
      <div class="flex items-start justify-between">
        <div class="text-sm text-slate-500"><?= $c[0] ?></div>
        <div class="flex h-9 w-9 items-center justify-center rounded-xl text-base font-bold <?= $c[3] ?>"><?= $c[2] ?></div>
      </div>
      <div class="mt-3 text-3xl font-bold text-slate-900"><?= number_format($c[1]) ?></div>
      <div class="mt-1 text-xs text-slate-400"><?= $c[4] ?></div>
    </div>
  <?php endforeach; ?>
</div>

<!-- Aksi cepat -->
<div class="flex flex-wrap gap-2">
  <a href="<?= site_url('barang/tambah') ?>" class="btn btn-primary">+ Tambah Barang</a>
  <a href="<?= site_url('transaksi/masuk') ?>" class="btn btn-light">Catat Barang Masuk</a>
  <a href="<?= site_url('transaksi/keluar') ?>" class="btn btn-light">Catat Barang Keluar</a>
  <a href="<?= site_url('rekap') ?>" class="btn btn-light">Lihat Rekap</a>
</div>

<div class="grid gap-6 lg:grid-cols-5">

  <!-- Stok menipis -->
  <div class="card overflow-hidden lg:col-span-2">
    <div class="flex items-center justify-between px-5 py-4">
      <h3 class="font-semibold text-slate-900">Stok Menipis</h3>
      <span class="text-xs text-slate-400">&le; 5 unit</span>
    </div>
    <div class="divide-y divide-slate-100 border-t border-slate-100">
      <?php foreach ($menipis as $b): $habis = ((int) $b->stok === 0); ?>
        <div class="px-5 py-3">
          <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
              <div class="truncate text-sm font-medium text-slate-800"><?= html_escape($b->nama_barang) ?></div>
              <div class="text-xs text-slate-400"><?= html_escape($b->kode_barang) ?> &middot; <?= html_escape($b->nama_lokasi) ?></div>
            </div>
            <span class="flex-none rounded-full px-2.5 py-0.5 text-xs font-semibold <?= $habis ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' ?>">
              <?= $habis ? 'Habis' : $b->stok . ' ' . html_escape($b->satuan) ?>
            </span>
          </div>
          <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100">
            <div class="h-full rounded-full <?= $habis ? 'bg-rose-400' : 'bg-amber-400' ?>" style="width: <?= min(100, max(4, $b->stok * 20)) ?>%"></div>
          </div>
        </div>
      <?php endforeach; if (!$menipis): ?>
        <div class="px-5 py-10 text-center text-sm text-slate-400">Semua stok aman.</div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Aktivitas terbaru -->
  <div class="card overflow-hidden lg:col-span-3">
    <div class="flex items-center justify-between px-5 py-4">
      <h3 class="font-semibold text-slate-900">Aktivitas Terbaru</h3>
      <a href="<?= site_url('riwayat') ?>" class="text-xs font-medium text-emerald-700 hover:underline">Lihat semua</a>
    </div>
    <div class="divide-y divide-slate-100 border-t border-slate-100">
      <?php foreach ($riwayat as $r):
          $m = isset($meta[$r->jenis]) ? $meta[$r->jenis] : array(str_replace('_', ' ', $r->jenis), 'bg-slate-400', '');
          $tampil = in_array($r->jenis, array('masuk', 'keluar', 'stok_awal'), TRUE);
      ?>
        <div class="flex items-center gap-3 px-5 py-3">
          <span class="h-2.5 w-2.5 flex-none rounded-full <?= $m[1] ?>"></span>
          <div class="min-w-0 flex-1">
            <div class="truncate text-sm font-medium text-slate-800"><?= html_escape($r->nama_barang) ?></div>
            <div class="text-xs text-slate-400"><?= $m[0] ?> &middot; <?= date('d/m H:i', strtotime($r->created_at)) ?><?= $r->nama_user ? ' &middot; ' . html_escape($r->nama_user) : '' ?></div>
          </div>
          <?php if ($tampil): ?>
            <div class="flex-none text-sm font-semibold <?= $r->jenis === 'masuk' ? 'text-emerald-600' : ($r->jenis === 'keluar' ? 'text-rose-600' : 'text-slate-500') ?>">
              <?= $m[2] . $r->jumlah ?> <span class="text-xs font-normal text-slate-400"><?= html_escape($r->satuan) ?></span>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; if (!$riwayat): ?>
        <div class="px-5 py-10 text-center text-sm text-slate-400">Belum ada aktivitas.</div>
      <?php endif; ?>
    </div>
  </div>
</div>