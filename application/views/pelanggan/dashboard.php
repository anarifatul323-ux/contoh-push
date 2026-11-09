<?php defined('BASEPATH') OR exit('No direct script access allowed');
$cards = array(
    array('Menunggu Persetujuan', $stat['menunggu'], 'text-amber-600'),
    array('Sedang Dipinjam', $stat['disetujui'], 'text-emerald-600'),
    array('Sudah Dikembalikan', $stat['dikembalikan'], 'text-slate-700'),
);
?>
<div class="grid gap-4 sm:grid-cols-3">
  <?php foreach ($cards as $c): ?>
    <div class="card p-5">
      <div class="text-sm text-slate-500"><?= $c[0] ?></div>
      <div class="mt-1 text-3xl font-bold <?= $c[2] ?>"><?= $c[1] ?></div>
    </div>
  <?php endforeach; ?>
</div>

<div class="card p-4">
  <form method="get" action="<?= site_url('pelanggan') ?>" class="grid gap-3 md:grid-cols-4">
    <input type="text" name="q" value="<?= html_escape($f['q']) ?>" placeholder="Cari barang..." class="input md:col-span-2">
    <select name="kategori_id" class="input"><option value="">Semua kategori</option>
      <?php foreach ($kategori as $o): ?><option value="<?= $o->id ?>" <?= $f['kategori_id'] == $o->id ? 'selected' : '' ?>><?= html_escape($o->nama_kategori) ?></option><?php endforeach; ?></select>
    <button class="btn btn-primary justify-center">Cari</button>
  </form>
</div>

<h2 class="text-lg font-semibold">Barang yang tersedia</h2>
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
  <?php foreach ($barang as $b): $habis = $b->stok <= 0; ?>
    <div class="card flex flex-col p-5">
      <div class="text-xs text-slate-400"><?= html_escape($b->kode_barang) ?> &middot; <?= html_escape($b->nama_kategori) ?></div>
      <div class="mt-1 font-semibold"><?= html_escape($b->nama_barang) ?></div>
      <div class="mt-1 text-sm text-slate-500">Lokasi: <?= html_escape($b->nama_lokasi) ?> &middot; <?= html_escape($b->nama_kondisi) ?></div>
      <div class="mt-3 flex items-center justify-between">
        <span class="text-sm font-semibold <?= $habis ? 'text-rose-600' : 'text-emerald-600' ?>"><?= $habis ? 'Stok habis' : 'Tersedia ' . $b->stok . ' ' . html_escape($b->satuan) ?></span>
        <?php if ($habis): ?>
          <span class="btn btn-light cursor-not-allowed opacity-60">Pinjam</span>
        <?php else: ?>
          <a href="<?= site_url('pelanggan/pinjam/' . $b->id) ?>" class="btn btn-primary">Pinjam</a>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; if (!$barang): ?>
    <div class="col-span-full py-10 text-center text-slate-400">Barang tidak ditemukan.</div>
  <?php endif; ?>
</div>