<?php defined('BASEPATH') OR exit('No direct script access allowed');
$err = array('<p class="err">', '</p>');
?>
<div class="grid lg:grid-cols-3 gap-6">
  <div class="card p-5 h-fit">
    <h2 class="font-semibold mb-3">Tambah Pengguna</h2>
    <?= form_open('pengguna') ?>
      <label class="lbl">Nama *</label>
      <input type="text" name="nama" value="<?= html_escape(set_value('nama')) ?>" class="input">
      <?= form_error('nama', $err[0], $err[1]) ?>
      <label class="lbl mt-3">Username *</label>
      <input type="text" name="username" value="<?= html_escape(set_value('username')) ?>" class="input">
      <?= form_error('username', $err[0], $err[1]) ?>
      <label class="lbl mt-3">Password *</label>
      <input type="password" name="password" class="input">
      <?= form_error('password', $err[0], $err[1]) ?>
      <label class="lbl mt-3">Konfirmasi Password *</label>
      <input type="password" name="passconf" class="input">
      <?= form_error('passconf', $err[0], $err[1]) ?>
      <label class="lbl mt-3">Peran *</label>
      <select name="role" class="input">
        <option value="petugas" <?= set_value('role') === 'petugas' ? 'selected' : '' ?>>Petugas</option>
        <option value="admin" <?= set_value('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
      </select>
      <?= form_error('role', $err[0], $err[1]) ?>
      <button class="btn btn-primary mt-4">Simpan</button>
    <?= form_close() ?>
  </div>

  <div class="card overflow-hidden lg:col-span-2">
    <table class="w-full">
      <thead><tr><th class="th">Nama</th><th class="th">Username</th><th class="th">Peran</th><th class="th text-right">Aksi</th></tr></thead>
      <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td class="td font-medium"><?= html_escape($u->nama) ?></td>
          <td class="td"><?= html_escape($u->username) ?></td>
          <td class="td"><?= ucfirst($u->role) ?></td>
          <td class="td text-right">
            <?php if ($u->id != $this->session->userdata('user_id')): ?>
              <?= form_open('pengguna/hapus/' . $u->id, array('class' => 'inline', 'onsubmit' => "return confirm('Hapus pengguna ini?')")) ?>
                <button class="btn btn-danger !py-1">Hapus</button>
              <?= form_close() ?>
            <?php else: ?><span class="text-xs text-slate-400">akun Anda</span><?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>