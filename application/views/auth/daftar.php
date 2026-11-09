<?php defined('BASEPATH') OR exit('No direct script access allowed');
$err = array('<p class="mt-1 text-xs text-rose-600">', '</p>');
$in  = 'w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm placeholder-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30';
$lbl = 'mb-1.5 block text-sm font-medium text-slate-700';
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Daftar - Sistem Inventaris</title>
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
        Daftar sekali, <span class="text-emerald-600">pinjam kapan saja.</span>
      </h2>
      <p class="mt-4 text-slate-600">Buat akun peminjam untuk mengajukan peminjaman barang sekolah dengan mudah dan memantau statusnya.</p>

      <ol class="mt-8 space-y-4 text-sm text-slate-700">
        <?php foreach (array(
            array('Buat akun', 'Isi nama, username, dan password.'),
            array('Ajukan peminjaman', 'Pilih barang, jumlah, dan tanggal.'),
            array('Tunggu persetujuan', 'Admin menyetujui, lalu ambil barangnya.'),
        ) as $i => $s): ?>
          <li class="flex items-start gap-3">
            <span class="flex h-7 w-7 flex-none items-center justify-center rounded-full bg-emerald-600 text-xs font-bold text-white"><?= $i + 1 ?></span>
            <div><div class="font-semibold text-slate-800"><?= $s[0] ?></div><div class="text-slate-500"><?= $s[1] ?></div></div>
          </li>
        <?php endforeach; ?>
      </ol>
    </div>

    <p class="relative text-xs text-slate-500">SMK Assalafiyyah Sleman &middot; Uji Kompetensi Keahlian Web Technology</p>
  </aside>

  <!-- Panel form -->
  <main class="flex items-center justify-center px-6 py-10">
    <div class="w-full max-w-sm">

      <a href="<?= site_url('/') ?>" class="mb-6 inline-flex items-center gap-1 text-sm text-slate-500 hover:text-emerald-700">&larr; Kembali ke beranda</a>

      <h1 class="text-3xl font-bold text-slate-900">Buat Akun</h1>
      <p class="mt-2 text-sm text-slate-500">Daftar sebagai peminjam barang.</p>

      <?= form_open('auth/daftar', array('id' => 'form-daftar', 'class' => 'mt-6')) ?>

        <label for="nama" class="<?= $lbl ?>">Nama lengkap</label>
        <input type="text" id="nama" name="nama" value="<?= html_escape(set_value('nama')) ?>" autocomplete="name" autofocus
                             class="<?= $in ?>">
        <?= form_error('nama', $err[0], $err[1]) ?>

        <label for="username" class="<?= $lbl ?> mt-4">Username</label>
        <input type="text" id="username" name="username" value="<?= html_escape(set_value('username')) ?>" autocomplete="username"
                            class="<?= $in ?>">
        <?= form_error('username', $err[0], $err[1]) ?>

               <label for="password" class="<?= $lbl ?> mt-4">Password (min. 6 karakter)</label>
        <div class="relative">
          <input type="password" id="password" name="password" autocomplete="new-password"
                 class="<?= $in ?> pr-16">
          <button type="button" data-toggle="password" class="absolute inset-y-0 right-0 px-4 text-xs font-medium text-slate-500 hover:text-emerald-700">Lihat</button>
        </div>
        <div class="mt-2 flex items-center gap-2">
          <div class="flex flex-1 gap-1">
            <span class="bar h-1.5 flex-1 rounded-full bg-slate-200"></span>
            <span class="bar h-1.5 flex-1 rounded-full bg-slate-200"></span>
            <span class="bar h-1.5 flex-1 rounded-full bg-slate-200"></span>
            <span class="bar h-1.5 flex-1 rounded-full bg-slate-200"></span>
          </div>
          <span id="kekuatan" class="w-14 text-right text-xs text-slate-400"></span>
        </div>
        <?= form_error('password', $err[0], $err[1]) ?>

        <label for="passconf" class="<?= $lbl ?> mt-4">Konfirmasi password</label>
        <div class="relative">
          <input type="password" id="passconf" name="passconf" autocomplete="new-password"
                 class="<?= $in ?> pr-16">
          <button type="button" data-toggle="passconf" class="absolute inset-y-0 right-0 px-4 text-xs font-medium text-slate-500 hover:text-emerald-700">Lihat</button>
        </div>
        <p id="cocok" class="mt-1 text-xs"></p>
        <?= form_error('passconf', $err[0], $err[1]) ?>

        <button type="submit" id="btn-daftar"
                class="mt-6 w-full rounded-xl bg-emerald-600 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-70">
          Daftar
        </button>
      <?= form_close() ?>

      <p class="mt-6 text-center text-sm text-slate-500">
        Sudah punya akun?
        <a href="<?= site_url('auth/login') ?>" class="font-semibold text-emerald-700 hover:underline">Masuk</a>
      </p>
    </div>
  </main>
</div>

<script>
(function () {
  var pw = document.getElementById('password'),
      pc = document.getElementById('passconf'),
      bars = document.querySelectorAll('.bar'),
      teks = document.getElementById('kekuatan'),
      cocok = document.getElementById('cocok'),
      form = document.getElementById('form-daftar'),
      btn = document.getElementById('btn-daftar');

  // Tombol lihat / sembunyikan password (untuk dua kolom)
  document.querySelectorAll('[data-toggle]').forEach(function (b) {
    b.addEventListener('click', function () {
      var el = document.getElementById(b.dataset.toggle), show = el.type === 'password';
      el.type = show ? 'text' : 'password';
      b.textContent = show ? 'Sembunyikan' : 'Lihat';
    });
  });

  // Indikator kekuatan password
  var level = [
    ['', ''],
    ['Lemah', 'bg-rose-400'],
    ['Cukup', 'bg-amber-400'],
    ['Bagus', 'bg-emerald-400'],
    ['Kuat', 'bg-emerald-600']
  ];
  function skor(v) {
    if (!v) { return 0; }
    var s = 0;
    if (v.length >= 6) { s++; }
    if (v.length >= 10) { s++; }
    if (/[a-z]/.test(v) && /[A-Z]/.test(v)) { s++; }
    if (/\d/.test(v) && /[^A-Za-z0-9]/.test(v)) { s++; }
    return Math.max(s, 1);
  }
  function cekKekuatan() {
    var v = pw.value, s = skor(v);
    bars.forEach(function (b, i) {
      b.className = 'bar h-1.5 flex-1 rounded-full ' + (i < s ? level[s][1] : 'bg-slate-200');
    });
    teks.textContent = level[s][0];
  }

  // Cek kecocokan konfirmasi
  function cekCocok() {
    if (!pc.value) { cocok.textContent = ''; return; }
    var ok = pc.value === pw.value;
    cocok.textContent = ok ? 'Password cocok' : 'Password belum sama';
    cocok.className = 'mt-1 text-xs ' + (ok ? 'text-emerald-600' : 'text-rose-600');
  }

  pw.addEventListener('input', function () { cekKekuatan(); cekCocok(); });
  pc.addEventListener('input', cekCocok);

  // Cegah klik ganda saat dikirim
  form.addEventListener('submit', function () {
    btn.disabled = true;
    btn.textContent = 'Memproses...';
  });
})();
</script>
</body>
</html>