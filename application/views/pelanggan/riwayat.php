<?php defined('BASEPATH') OR exit('No direct script access allowed');
$warna = array(
    'menunggu'     => 'bg-amber-100 text-amber-700',
    'disetujui'    => 'bg-emerald-100 text-emerald-700',
    'ditolak'      => 'bg-rose-100 text-rose-700',
    'dikembalikan' => 'bg-slate-100 text-slate-600',
    'dibatalkan'   => 'bg-slate-100 text-slate-500',
);
?>
<div class="card overflow-hidden">
  <div class="overflow-x-auto"><table class="w-full">
    <thead><tr><th class="th">Barang</th><th class="th text-right">Jumlah</th><th class="th">Periode</th><th class="th">Keperluan</th><th class="th">Status</th><th class="th text-right">Aksi</th></tr></thead>
    <tbody>
    <?php foreach ($daftar as $d): $telat = ($d->status === 'disetujui' && $d->tanggal_kembali < date('Y-m-d')); ?>
      <tr>
        <td class="td font-medium"><?= html_escape($d->nama_barang) ?><div class="text-xs text-slate-400"><?= html_escape($d->kode_barang) ?></div></td>
        <td class="td text-right"><?= $d->jumlah ?> <?= html_escape($d->satuan) ?></td>
        <td class="td whitespace-nowrap"><?= date('d/m/Y', strtotime($d->tanggal_pinjam)) ?> - <?= date('d/m/Y', strtotime($d->tanggal_kembali)) ?></td>
        <td class="td"><?= html_escape($d->keperluan) ?></td>
        <td class="td">
          <span class="rounded-full px-2 py-0.5 text-xs <?= $warna[$d->status] ?>"><?= ucfirst($d->status) ?></span>
          <?php if ($telat): ?><span class="ml-1 rounded-full bg-rose-100 px-2 py-0.5 text-xs text-rose-700">Terlambat</span><?php endif; ?>
          <?php if ($d->catatan_admin): ?><div class="mt-1 text-xs text-slate-500">Catatan: <?= html_escape($d->catatan_admin) ?></div><?php endif; ?>
        </td>
        <td class="td text-right">
          <?php if ($d->status === 'menunggu'): ?>
            <?= form_open('pelanggan/batal/' . $d->id, array('class' => 'inline', 'onsubmit' => "return confirm('Batalkan pengajuan ini?')")) ?>
              <button class="btn btn-danger !py-1">Batalkan</button>
            <?= form_close() ?>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; if (!$daftar): ?>
      <tr><td colspan="6" class="td py-8 text-center text-slate-400">Belum ada peminjaman. <a href="<?= site_url('pelanggan/pinjam') ?>" class="text-emerald-600 hover:underline">Ajukan sekarang</a>.</td></tr>
    <?php endif; ?>
    </tbody></table></div>
</div>