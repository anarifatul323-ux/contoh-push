<?php defined('BASEPATH') OR exit('No direct script access allowed');
$dash    = isset($dash) ? $dash : 'dashboard';
$cta_url = $logged_in ? site_url($dash) : site_url('auth/login');

// judul, deskripsi, warna
$fitur = array(
    array('Data Barang Terpusat', 'Kode, nama, kategori, lokasi, jumlah, dan kondisi barang tersimpan rapi dalam satu tempat.', 'emerald'),
    array('Stok Otomatis', 'Setiap barang masuk dan keluar langsung memperbarui stok tanpa hitung manual.', 'sky'),
    array('Validasi Stok', 'Barang keluar yang melebihi stok otomatis ditolak, jadi data tidak pernah minus.', 'amber'),
    array('Peminjaman Online', 'Pelanggan bisa mengajukan peminjaman barang, admin tinggal menyetujui atau menolak.', 'violet'),
    array('Riwayat Lengkap', 'Pergerakan barang, perubahan kondisi, dan perpindahan lokasi tercatat beserta petugasnya.', 'rose'),
    array('Rekap Siap Cetak', 'Ringkasan inventaris per kategori, lokasi, dan kondisi yang bisa langsung dicetak.', 'teal'),
);
$langkah = array(
    array('1', 'Daftar Akun', 'Buat akun pelanggan dalam hitungan detik.'),
    array('2', 'Ajukan Peminjaman', 'Pilih barang, tentukan jumlah dan tanggal, lalu kirim pengajuan.'),
    array('3', 'Ambil dan Kembalikan', 'Setelah disetujui admin, ambil barang lalu kembalikan sesuai tanggal.'),
);
?><!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sistem Inventaris - SMK Assalafiyyah Sleman</title>
<meta name="description" content="Aplikasi web untuk mencatat, memantau, dan meminjam barang dengan validasi stok yang tepat.">
<script src="https://cdn.tailwindcss.com"></script>
<style>
  .reveal { opacity: 0; transform: translateY(20px); transition: opacity .6s ease, transform .6s ease; }
  .reveal.show { opacity: 1; transform: none; }
  @keyframes floaty { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
  .floaty { animation: floaty 5s ease-in-out infinite; }
  .floaty-2 { animation: floaty 6.5s ease-in-out infinite; animation-delay: -2s; }
  @media (prefers-reduced-motion: reduce) {
    .reveal { opacity: 1; transform: none; transition: none; }
    .floaty, .floaty-2 { animation: none; }
  }
</style>
<noscript><style>.reveal { opacity: 1; transform: none; }</style></noscript>
</head>
<body class="bg-white text-slate-800 antialiased">

<!-- Navbar -->
<header id="nav" class="sticky top-0 z-20 border-b border-transparent bg-white/90 backdrop-blur transition">
  <div class="mx-auto flex max-w-6xl items-center justify-between px-5 py-4">
    <a href="#" class="flex items-center gap-2">
      <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-600 font-bold text-white">I</span>
      <span class="text-lg font-bold text-emerald-700">Inventaris</span>
    </a>
    <nav class="hidden items-center gap-6 text-sm text-slate-600 md:flex">
      <a href="#fitur" class="hover:text-emerald-700">Fitur</a>
      <a href="#cara-kerja" class="hover:text-emerald-700">Cara Kerja</a>
      <a href="#validasi" class="hover:text-emerald-700">Validasi</a>
    </nav>
    <div class="flex items-center gap-2">
      <?php if ($logged_in): ?>
        <a href="<?= $cta_url ?>" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Ke Dashboard</a>
      <?php else: ?>
        <a href="<?= site_url('auth/login') ?>" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-700 hover:bg-emerald-50">Masuk</a>
        <a href="<?= site_url('auth/daftar') ?>" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Daftar</a>
      <?php endif; ?>
    </div>
  </div>
</header>

<!-- Hero -->
<section class="relative overflow-hidden bg-gradient-to-b from-emerald-50 to-white">
  <div class="pointer-events-none absolute -left-20 top-10 h-72 w-72 rounded-full bg-emerald-200/40 blur-3xl"></div>
  <div class="pointer-events-none absolute -right-16 top-32 h-72 w-72 rounded-full bg-teal-200/40 blur-3xl"></div>

  <div class="relative mx-auto grid max-w-6xl items-center gap-14 px-5 py-16 md:grid-cols-2 md:py-24">
    <div class="reveal">
      <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-emerald-800 shadow-sm ring-1 ring-emerald-100">
        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
      </span>
      <h1 class="mt-5 text-4xl font-extrabold leading-tight text-slate-900 md:text-5xl">
        Kelola inventaris barang, <span class="bg-gradient-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent">tanpa salah hitung.</span>
      </h1>
      <p class="mt-5 text-lg text-slate-600">
        Catat, pantau, dan pinjam barang sekolah atau instansi dengan stok otomatis dan validasi yang menjaga data tetap akurat.
      </p>
      <div class="mt-8 flex flex-wrap gap-3">
        <?php if ($logged_in): ?>
          <a href="<?= $cta_url ?>" class="rounded-xl bg-emerald-600 px-6 py-3 font-medium text-white shadow-lg shadow-emerald-600/20 transition hover:-translate-y-0.5 hover:bg-emerald-700">Ke Dashboard</a>
        <?php else: ?>
          <a href="<?= site_url('auth/daftar') ?>" class="rounded-xl bg-emerald-600 px-6 py-3 font-medium text-white shadow-lg shadow-emerald-600/20 transition hover:-translate-y-0.5 hover:bg-emerald-700">Daftar Akun</a>
          <a href="<?= site_url('auth/login') ?>" class="rounded-xl border border-emerald-200 bg-white px-6 py-3 font-medium text-slate-700 transition hover:-translate-y-0.5 hover:bg-emerald-50">Sudah punya akun? Masuk</a>
        <?php endif; ?>
      </div>
      <div class="mt-7 flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-500">
        <?php foreach (array('Gratis mendaftar', 'Stok selalu terbarui', 'Tanpa hitung manual') as $t): ?>
          <span class="flex items-center gap-1.5"><span class="font-bold text-emerald-600">&#10003;</span><?= $t ?></span>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Preview ilustrasi -->
    <div class="reveal relative mx-auto w-full max-w-md md:max-w-none" style="transition-delay:120ms">
      <div class="absolute -inset-3 rounded-3xl bg-gradient-to-tr from-emerald-200/50 to-transparent blur-2xl"></div>
      <div class="relative rounded-2xl border border-emerald-100 bg-white p-5 shadow-xl shadow-emerald-900/5">
        <div class="mb-3 flex items-center justify-between">
          <span class="text-sm font-semibold">Pinjam Barang</span>
          <span class="text-xs text-slate-400">contoh</span>
        </div>
        <div class="rounded-lg bg-slate-50 p-4 text-sm">
          <div class="flex justify-between"><span class="text-slate-500">Barang</span><span class="font-medium">Kamera DSLR</span></div>
          <div class="mt-2 flex justify-between"><span class="text-slate-500">Stok tersedia</span><span class="font-medium">5 unit</span></div>
          <div class="mt-2 flex justify-between"><span class="text-slate-500">Jumlah pinjam</span><span class="font-medium text-emerald-700">2 unit</span></div>
          <div class="mt-2 flex justify-between"><span class="text-slate-500">Periode</span><span class="font-medium">7 hari</span></div>
        </div>
        <div class="mt-3 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">
          Pengajuan terkirim. Tunggu persetujuan admin.
        </div>
        <div class="mt-4 grid grid-cols-3 gap-3 text-center">
          <div class="rounded-lg bg-amber-50 p-3"><div class="text-xl font-bold text-amber-600">1</div><div class="text-xs text-slate-500">Menunggu</div></div>
          <div class="rounded-lg bg-emerald-50 p-3"><div class="text-xl font-bold text-emerald-700">0</div><div class="text-xs text-slate-500">Dipinjam</div></div>
          <div class="rounded-lg bg-teal-50 p-3"><div class="text-xl font-bold text-teal-700">1</div><div class="text-xs text-slate-500">Dikembalikan</div></div>
        </div>
      </div>

      <!-- Kartu kecil melayang -->
      <div class="floaty absolute -right-2 -top-5 hidden items-center gap-2.5 rounded-xl border border-emerald-100 bg-white px-3.5 py-2.5 shadow-lg sm:flex lg:-right-6">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 font-bold text-emerald-600">&#10003;</span>
        <div><div class="text-xs font-semibold text-slate-800">Disetujui admin</div><div class="text-[11px] text-slate-400">Kamera DSLR</div></div>
      </div>
      <div class="floaty-2 absolute -bottom-5 -left-2 hidden items-center gap-2.5 rounded-xl border border-sky-100 bg-white px-3.5 py-2.5 shadow-lg sm:flex lg:-left-6">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-100 font-bold text-sky-600">&#8645;</span>
        <div><div class="text-xs font-semibold text-slate-800">Stok diperbarui</div><div class="text-[11px] text-slate-400">3 &rarr; 5 unit</div></div>
      </div>
    </div>
  </div>
</section>

<!-- Fitur -->
<section id="fitur" class="mx-auto max-w-6xl scroll-mt-20 px-5 py-16">
  <div class="reveal mx-auto max-w-2xl text-center">
    <span class="text-sm font-semibold uppercase tracking-wider text-emerald-600">Fitur</span>
    <h2 class="mt-2 text-3xl font-bold text-slate-900">Semua yang dibutuhkan untuk inventaris</h2>
    <p class="mt-3 text-slate-600">Dari pencatatan barang sampai peminjaman, dalam satu aplikasi yang sederhana.</p>
  </div>
  <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
    <?php foreach ($fitur as $i => $f): $c = $f[2]; ?>
      <div class="reveal group relative overflow-hidden rounded-2xl border border-<?= $c ?>-100 bg-<?= $c ?>-50/50 p-6 transition hover:-translate-y-1 hover:shadow-md hover:shadow-<?= $c ?>-900/5" style="transition-delay: <?= ($i % 3) * 80 ?>ms">
        <div class="pointer-events-none absolute -bottom-8 -right-8 h-28 w-28 rounded-full bg-<?= $c ?>-100/70 transition group-hover:scale-125"></div>
        <div class="relative">
          <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white font-bold text-<?= $c ?>-600 shadow-sm"><?= $i + 1 ?></div>
          <h3 class="mt-4 font-semibold text-slate-900"><?= $f[0] ?></h3>
          <p class="mt-2 text-sm text-slate-600"><?= $f[1] ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Cara kerja -->
<section id="cara-kerja" class="scroll-mt-20 bg-emerald-50/60">
  <div class="mx-auto max-w-6xl px-5 py-16">
    <h2 class="reveal text-center text-3xl font-bold text-slate-900">Cara meminjam barang</h2>
    <div class="relative mt-12 grid gap-6 md:grid-cols-3">
      <div class="pointer-events-none absolute left-[17%] right-[17%] top-5 hidden border-t-2 border-dashed border-emerald-300 md:block"></div>
      <?php foreach ($langkah as $i => $l): ?>
        <div class="reveal relative rounded-2xl bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md" style="transition-delay: <?= $i * 100 ?>ms">
          <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-emerald-600 font-bold text-white shadow-md shadow-emerald-600/30"><?= $l[0] ?></div>
          <h3 class="mt-4 font-semibold"><?= $l[1] ?></h3>
          <p class="mt-2 text-sm text-slate-600"><?= $l[2] ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Validasi -->
<section id="validasi" class="mx-auto max-w-6xl scroll-mt-20 px-5 py-16">
  <div class="reveal grid items-center gap-10 rounded-3xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-teal-50 p-8 md:grid-cols-2 md:p-12">
    <div>
      <h2 class="text-3xl font-bold text-slate-900">Data aman karena divalidasi</h2>
      <p class="mt-3 text-slate-600">Sistem menolak input yang salah sebelum masuk ke database.</p>
    </div>
    <ul class="space-y-3 text-sm">
      <?php foreach (array(
          'Kode barang harus unik dan nama barang wajib diisi',
          'Kategori dan lokasi wajib dipilih',
          'Jumlah harus berupa angka dan tidak boleh negatif',
          'Barang keluar atau dipinjam tidak boleh melebihi stok tersedia',
          'Tanggal kembali tidak boleh lebih awal dari tanggal pinjam',
          'Barang yang sudah dipakai transaksi tidak bisa dihapus sembarangan',
      ) as $v): ?>
        <li class="flex items-start gap-3 rounded-xl bg-white/80 p-4 ring-1 ring-emerald-100">
          <span class="mt-0.5 flex h-5 w-5 flex-none items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700">&#10003;</span>
          <span><?= $v ?></span>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<!-- CTA -->
<section class="px-5 pb-16">
  <div class="reveal relative mx-auto max-w-4xl overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-600 to-teal-600 px-8 py-12 text-center">
    <div class="pointer-events-none absolute -left-8 -top-8 h-40 w-40 rounded-full bg-white/10"></div>
    <div class="pointer-events-none absolute -bottom-12 -right-8 h-52 w-52 rounded-full bg-white/10"></div>
    <div class="relative">
      <h2 class="text-3xl font-bold text-white">Siap meminjam atau mengelola barang?</h2>
      <p class="mt-3 text-emerald-50">Buat akun gratis dan mulai sekarang.</p>
      <div class="mt-6 flex flex-wrap justify-center gap-3">
        <?php if ($logged_in): ?>
          <a href="<?= $cta_url ?>" class="rounded-xl bg-white px-6 py-3 font-medium text-emerald-800 transition hover:-translate-y-0.5 hover:bg-emerald-50">Ke Dashboard</a>
        <?php else: ?>
          <a href="<?= site_url('auth/daftar') ?>" class="rounded-xl bg-white px-6 py-3 font-medium text-emerald-800 transition hover:-translate-y-0.5 hover:bg-emerald-50">Daftar Sekarang</a>
          <a href="<?= site_url('auth/login') ?>" class="rounded-xl border border-white/40 px-6 py-3 font-medium text-white transition hover:bg-white/10">Masuk</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="border-t border-emerald-100 bg-emerald-50/50">
  <div class="mx-auto max-w-6xl px-5 py-8 text-sm text-slate-500">
    <div class="font-semibold text-slate-700">SMK Assalafiyyah Sleman</div>
    <div class="mt-1">Jl. Kiai Masduqi Mlangi, Nogotirto, Gamping, Sleman, DIY 55592</div>
    <div class="mt-4 text-xs">&copy; <?= date('Y') ?> Sistem Inventaris - Uji Kompetensi Keahlian Web Technology</div>
  </div>
</footer>

<script>
(function () {
  // Bayangan tipis di navbar saat halaman digeser
  var nav = document.getElementById('nav');
  function onScroll() {
    nav.classList.toggle('border-emerald-100', window.scrollY > 10);
    nav.classList.toggle('shadow-sm', window.scrollY > 10);
  }
  window.addEventListener('scroll', onScroll);
  onScroll();

  // Konten muncul halus saat di-scroll
  var els = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { e.target.classList.add('show'); io.unobserve(e.target); }
      });
    }, { threshold: 0.12 });
    els.forEach(function (el) { io.observe(el); });
  } else {
    els.forEach(function (el) { el.classList.add('show'); });
  }
})();
</script>
</body>
</html>