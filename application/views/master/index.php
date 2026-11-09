<?php defined('BASEPATH') OR exit('No direct script access allowed');
$field = $c['field'];
$action = $edit ? 'master/edit/' . $type . '/' . $edit->id : 'master/index/' . $type;
?>
<div class="grid lg:grid-cols-3 gap-6">
  <div class="card p-5 h-fit">
    <h2 class="font-semibold mb-3"><?= $edit ? 'Ubah' : 'Tambah' ?> <?= $c['label'] ?></h2>
    <?= form_open($action) ?>
      <label class="lbl">Nama <?= strtolower($c['label']) ?> *</label>
      <input type="text" name="<?= $field ?>" value="<?= html_escape(set_value($field, $edit ? $edit->$field : '')) ?>" class="input">
      <?= form_error($field, '<p class="err">', '</p>') ?>
      <div class="mt-4 flex gap-2">
        <button class="btn btn-primary">Simpan</button>
        <?php if ($edit): ?><a href="<?= site_url('master/index/' . $type) ?>" class="btn btn-light">Batal</a><?php endif; ?>
      </div>
    <?= form_close() ?>
  </div>

  <div class="card overflow-hidden lg:col-span-2">
    <table class="w-full">
      <thead><tr><th class="th w-16">No</th><th class="th">Nama</th><th class="th text-right">Aksi</th></tr></thead>
      <tbody>
      <?php foreach ($items as $i => $it): ?>
        <tr>
          <td class="td"><?= $i + 1 ?></td>
          <td class="td font-medium"><?= html_escape($it->$field) ?></td>
          <td class="td text-right whitespace-nowrap">
            <a href="<?= site_url('master/edit/' . $type . '/' . $it->id) ?>" class="btn btn-light !py-1">Ubah</a>
            <?= form_open('master/hapus/' . $type . '/' . $it->id, array('class' => 'inline', 'onsubmit' => "return confirm('Hapus data ini?')")) ?>
              <button class="btn btn-danger !py-1">Hapus</button>
            <?= form_close() ?>
          </td>
        </tr>
      <?php endforeach; if (!$items): ?>
        <tr><td colspan="3" class="td text-center text-slate-400 py-6">Belum ada data.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>