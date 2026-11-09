<?php defined('BASEPATH') OR exit('No direct script access allowed');
$blok = array('Per Kategori' => $per_kategori, 'Per Lokasi' => $per_lokasi, 'Per Kondisi' => $per_kondisi);
?>
<div class="flex items-center justify-between print:hidden">
  <p class="text-sm text-slate-500">Per <?= date('d/m/Y H:i') ?> &middot; <?= $stat->jenis ?> jenis barang &middot; total stok <?= number_format($stat->total_stok) ?></p>
  <button onclick="window.print()" class="btn btn-primary">Cetak</button>
</div>

<div class="grid md:grid-cols-3 gap-6">
  <?php foreach ($blok as $judul => $rows): ?>
    <div class="card overflow-hidden">
      <div class="px-5 py-4 font-semibold"><?= $judul ?></div>
      <table class="w-full">
        <thead><tr><th class="th">Nama</th><th class="th text-right">Jenis</th><th class="th text-right">Stok</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
          <tr><td class="td"><?= html_escape($r->nama) ?></td><td class="td text-right"><?= $r->jenis ?></td><td class="td text-right font-semibold"><?= $r->total_stok ?></td></tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endforeach; ?>
</div>

<div class="card overflow-hidden">
  <div class="px-5 py-4 font-semibold">Rekap Seluruh Barang</div>
  <div class="overflow-x-auto"><table class="w-full">
    <thead><tr><th class="th">Kode</th><th class="th">Nama</th><th class="th">Kategori</th><th class="th">Lokasi</th><th class="th">Kondisi</th><th class="th text-right">Masuk</th><th class="th text-right">Keluar</th><th class="th text-right">Stok</th></tr></thead>
    <tbody>
    <?php foreach ($barang as $b): ?>
      <tr>
        <td class="td font-mono text-xs"><?= html_escape($b->kode_barang) ?></td>
        <td class="td"><?= html_escape($b->nama_barang) ?></td>
        <td class="td"><?= html_escape($b->nama_kategori) ?></td>
        <td class="td"><?= html_escape($b->nama_lokasi) ?></td>
        <td class="td"><?= html_escape($b->nama_kondisi) ?></td>
        <td class="td text-right"><?= $b->total_masuk ?></td>
        <td class="td text-right"><?= $b->total_keluar ?></td>
        <td class="td text-right font-semibold"><?= $b->stok ?> <?= html_escape($b->satuan) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody></table></div>
</div>