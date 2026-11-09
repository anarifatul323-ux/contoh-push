<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk - Sistem Inventaris</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-white text-slate-800 antialiased">
<div class="grid min-h-screen lg:grid-cols-2">

  <!-- Panel kiri (disembunyikan di layar kecil) -->
  <aside class="relative hidden overflow-hidden bg-gradient-to-br from-emerald-50 via-emerald-100/70 to-teal-50 lg:flex lg:flex-col lg:justify-between lg:p-12">
    <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-emerald-200/40"></div>
    <div class="pointer-events-none absolute -bottom-20 -left-16 h-64 w-64 rounded-full bg-teal-200/40"></div>

    <a href="<?= site_url('/') ?>" class="relative text-xl font-bold text-emerald-700">Inventaris</a>

    <div class="relative max-w-md">
      <h2 class="text-3xl font-extrabold leading-tight text-slate-900">
        Kelola dan pinjam barang, <span class="text-emerald-600">tanpa ribet.</span>
      </h2>
      <p class="mt-4 text-slate-600">Masuk untuk mencatat stok, mengajukan peminjaman, dan memantau riwayat barang dalam satu tempat.</p>

      <ul class="mt-8 space-y-3 text-sm text-slate-700">
        <?php foreach (array(
            'Stok barang selalu terbarui otomatis',
            'Ajukan peminjaman kapan saja',
            'Riwayat tercatat lengkap dan rapi',
        ) as $p): ?>
          <li class="flex items-center gap-3">
            <span class="flex h-6 w-6 flex-none items-center justify-center rounded-full bg-white text-xs font-bold text-emerald-600 shadow-sm">&#10003;</span>
            <?= $p ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <p class="relative text-xs text-slate-500">SMK Assalafiyyah Sleman &middot; Uji Kompetensi Keahlian Web Technology</p>
  </aside>

  <!-- Panel form -->
  <main class="flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-sm">

      <a href="<?= site_url('/') ?>" class="mb-8 inline-flex items-center gap-1 text-sm text-slate-500 hover:text-emerald-700">&larr; Kembali ke beranda</a>

      <h1 class="text-3xl font-bold text-slate-900">Selamat datang</h1>
      <p class="mt-2 text-sm text-slate-500">Masuk ke akun Anda untuk melanjutkan.</p>

      <?php if ($this->session->flashdata('success')): ?>
        <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"><?= html_escape($this->session->flashdata('success')) ?></div>
      <?php endif; ?>

      <?php if (!empty($error)): ?>
        <div class="mt-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800"><?= html_escape($error) ?></div>
      <?php endif; ?>

      <?= form_open('auth/login', array('id' => 'form-login', 'class' => 'mt-6')) ?>
        <label for="username" class="mb-1.5 block text-sm font-medium text-slate-700">Username</label>
        <input type="text" id="username" name="username" value="<?= html_escape(set_value('username')) ?>" autocomplete="username" autofocus
               placeholder="Masukkan username"
               class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm placeholder-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
        <?= form_error('username', '<p class="mt-1 text-xs text-rose-600">', '</p>') ?>

        <label for="password" class="mb-1.5 mt-4 block text-sm font-medium text-slate-700">Password</label>
        <div class="relative">
          <input type="password" id="password" name="password" autocomplete="current-password"
                 placeholder="Masukkan password"
                 class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 pr-16 text-sm placeholder-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
          <button type="button" id="toggle-pw" class="absolute inset-y-0 right-0 px-4 text-xs font-medium text-slate-500 hover:text-emerald-700">Lihat</button>
        </div>
        <?= form_error('password', '<p class="mt-1 text-xs text-rose-600">', '</p>') ?>

        <button type="submit" id="btn-masuk"
                class="mt-6 w-full rounded-xl bg-emerald-600 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-70">
          Masuk
        </button>
      <?= form_close() ?>

      <p class="mt-6 text-center text-sm text-slate-500">
        Belum punya akun?
        <a href="<?= site_url('auth/daftar') ?>" class="font-semibold text-emerald-700 hover:underline">Daftar sekarang</a>
      </p>
    </div>
  </main>
</div>

<script>
(function () {
  var pw = document.getElementById('password'),
      tg = document.getElementById('toggle-pw'),
      form = document.getElementById('form-login'),
      btn = document.getElementById('btn-masuk');

  // Tombol lihat / sembunyikan password
  tg.addEventListener('click', function () {
    var show = pw.type === 'password';
    pw.type = show ? 'text' : 'password';
    tg.textContent = show ? 'Sembunyikan' : 'Lihat';
  });

  // Cegah klik ganda saat form dikirim
  form.addEventListener('submit', function () {
    btn.disabled = true;
    btn.textContent = 'Memproses...';
  });
})();
</script>
</body>
</html>