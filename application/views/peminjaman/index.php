<?php defined('BASEPATH') OR exit('No direct script access allowed');
$warna = array(
    'menunggu'     => 'bg-amber-100 text-amber-700',
    'disetujui'    => 'bg-emerald-100 text-emerald-700',
    'ditolak'      => 'bg-rose-100 text-rose-700',
    'dikembalikan' => 'bg-slate-100 text-slate-600',
    'dibatalkan'   => 'bg-slate-100 text-slate-500',
);
$tab = array('' => 'Semua', 'menunggu' => 'Menunggu', 'disetujui' => 'Dipinjam', 'dikembalikan' => 'Dikembalikan', 'ditolak' => 'Ditolak');
?>
<div class="flex flex-wrap gap-2">
  <?php foreach ($tab as $k => $l): ?>
    <a href="<?= site_url('peminjaman') . ($k ? '?status=' . $k : '') ?>" class="btn <?= (string) $status === (string) $k ? 'btn-primary' : 'btn-light' ?>"><?= $l ?></a>
  <?php endforeach; ?>
</div>

<div class="card overflow-hidden">
  <div class="overflow-x-auto"><table class="w-full">
    <thead><tr><th class="th">Pelanggan</th><th class="th">Barang</th><th class="th text-right">Jumlah</th><th class="th">Periode</th><th class="th">Keperluan</th><th class="th">Status</th><th class="th text-right">Aksi</th></tr></thead>
    <tbody>
    <?php foreach ($daftar as $d): $telat = ($d->status === 'disetujui' && $d->tanggal_kembali < date('Y-m-d')); ?>
      <tr>
                <td class="td font-medium"><?= html_escape($d->nama_pelanggan) ?></td>
        <td class="td"><?= html_escape($d->nama_barang) ?><div class="text-xs text-slate-400"><?= html_escape($d->kode_barang) ?></div></td>
        <td class="td text-right"><?= $d->jumlah ?> <?= html_escape($d->satuan) ?></td>
        <td class="td whitespace-nowrap"><?= date('d/m/Y', strtotime($d->tanggal_pinjam)) ?> - <?= date('d/m/Y', strtotime($d->tanggal_kembali)) ?></td>
        <td class="td"><?= html_escape($d->keperluan) ?></td>
        <td class="td">
          <span class="rounded-full px-2 py-0.5 text-xs <?= $warna[$d->status] ?>"><?= ucfirst($d->status) ?></span>
          <?php if ($telat): ?><span class="ml-1 rounded-full bg-rose-100 px-2 py-0.5 text-xs text-rose-700">Terlambat</span><?php endif; ?>
        </td>
        <td class="td text-right">
          <?php if ($d->status === 'menunggu'): ?>
            <div class="flex flex-wrap justify-end gap-2">
              <?= form_open('peminjaman/setujui/' . $d->id, array('class' => 'inline')) ?>
                <button class="btn btn-primary !py-1">Setujui</button>
              <?= form_close() ?>
              <?= form_open('peminjaman/tolak/' . $d->id, array('class' => 'inline-flex gap-1')) ?>
                <input type="text" name="catatan" placeholder="Alasan (opsional)" class="input !w-36 !py-1">
                <button class="btn btn-danger !py-1">Tolak</button>
              <?= form_close() ?>
            </div>
          <?php elseif ($d->status === 'disetujui'): ?>
            <?= form_open('peminjaman/kembali/' . $d->id, array('class' => 'inline', 'onsubmit' => "return confirm('Tandai barang sudah dikembalikan?')")) ?>
              <button class="btn btn-light !py-1">Tandai Dikembalikan</button>
            <?= form_close() ?>
          <?php else: ?>
            <span class="text-xs text-slate-400"><?= $d->catatan_admin ? html_escape($d->catatan_admin) : '-' ?></span>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; if (!$daftar): ?>
      <tr><td colspan="7" class="td py-8 text-center text-slate-400">Belum ada data peminjaman.</td></tr>
    <?php endif; ?>
    </tbody></table></div>
</div>