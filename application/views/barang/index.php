<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="card p-4">
  <form method="get" action="<?= site_url('barang') ?>" class="grid gap-3 md:grid-cols-5">
    <input type="text" name="q" value="<?= html_escape($f['q']) ?>" placeholder="Cari kode / nama barang" class="input md:col-span-2">
    <select name="kategori_id" class="input"><option value="">Semua kategori</option>
      <?php foreach ($kategori as $o): ?><option value="<?= $o->id ?>" <?= $f['kategori_id'] == $o->id ? 'selected' : '' ?>><?= html_escape($o->nama_kategori) ?></option><?php endforeach; ?></select>
    <select name="lokasi_id" class="input"><option value="">Semua lokasi</option>
      <?php foreach ($lokasi as $o): ?><option value="<?= $o->id ?>" <?= $f['lokasi_id'] == $o->id ? 'selected' : '' ?>><?= html_escape($o->nama_lokasi) ?></option><?php endforeach; ?></select>
    <select name="kondisi_id" class="input"><option value="">Semua kondisi</option>
      <?php foreach ($kondisi as $o): ?><option value="<?= $o->id ?>" <?= $f['kondisi_id'] == $o->id ? 'selected' : '' ?>><?= html_escape($o->nama_kondisi) ?></option><?php endforeach; ?></select>
    <div class="md:col-span-5 flex gap-2">
      <button class="btn btn-primary">Cari / Saring</button>
      <a href="<?= site_url('barang') ?>" class="btn btn-light">Reset</a>
      <a href="<?= site_url('barang/tambah') ?>" class="btn btn-primary ml-auto">+ Tambah Barang</a>
    </div>
  </form>
</div>

<div class="card overflow-hidden">
  <div class="overflow-x-auto"><table class="w-full">
    <thead><tr><th class="th">Kode</th><th class="th">Nama Barang</th><th class="th">Kategori</th><th class="th">Lokasi</th><th class="th">Kondisi</th><th class="th text-right">Stok</th><th class="th text-right">Aksi</th></tr></thead>
    <tbody>
    <?php foreach ($barang as $b): ?>
      <tr>
        <td class="td font-mono text-xs"><?= html_escape($b->kode_barang) ?></td>
        <td class="td font-medium"><?= html_escape($b->nama_barang) ?></td>
        <td class="td"><?= html_escape($b->nama_kategori) ?></td>
        <td class="td"><?= html_escape($b->nama_lokasi) ?></td>
        <td class="td"><span class="rounded-full px-2 py-0.5 text-xs <?= $b->nama_kondisi === 'Baik' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' ?>"><?= html_escape($b->nama_kondisi) ?></span></td>
        <td class="td text-right font-semibold <?= $b->stok <= 5 ? 'text-rose-600' : '' ?>"><?= $b->stok ?> <?= html_escape($b->satuan) ?></td>
        <td class="td text-right whitespace-nowrap">
          <a href="<?= site_url('barang/edit/' . $b->id) ?>" class="btn btn-light !py-1">Ubah</a>
          <?= form_open('barang/hapus/' . $b->id, array('class' => 'inline', 'onsubmit' => "return confirm('Hapus barang ini?')")) ?>
            <button class="btn btn-danger !py-1">Hapus</button>
          <?= form_close() ?>
        </td>
      </tr>
    <?php endforeach; if (!$barang): ?>
      <tr><td colspan="7" class="td text-center text-slate-400 py-8">Belum ada data barang.</td></tr>
    <?php endif; ?>
    </tbody></table></div>
</div>