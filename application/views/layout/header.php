<?php defined('BASEPATH') OR exit('No direct script access allowed');
$role    = $this->session->userdata('role');
$uri     = uri_string();
$nama    = (string) $this->session->userdata('nama');
$pending = $this->db->where('status', 'menunggu')->count_all_results('peminjaman');

// Warna tiap grup: teks judul, titik, dan gaya menu aktif
$warna = array(
    'Utama'      => array('text-emerald-600', 'bg-emerald-400', 'bg-emerald-50 text-emerald-700'),
    'Data Master'=> array('text-sky-600',     'bg-sky-400',     'bg-sky-50 text-sky-700'),
    'Transaksi'  => array('text-amber-600',   'bg-amber-400',   'bg-amber-50 text-amber-700'),
    'Laporan'    => array('text-violet-600',  'bg-violet-400',  'bg-violet-50 text-violet-700'),
    'Pengaturan' => array('text-rose-500',    'bg-rose-400',    'bg-rose-50 text-rose-700'),
);

$menu = array(
    'Utama' => array(
        array('dashboard', 'Dashboard'),
    ),
    'Data Master' => array(
        array('barang',                'Data Barang'),
        array('master/index/kategori', 'Kategori'),
        array('master/index/lokasi',   'Lokasi'),
        array('master/index/kondisi',  'Kondisi Barang'),
    ),
    'Transaksi' => array(
        array('transaksi/masuk',  'Barang Masuk'),
        array('transaksi/keluar', 'Barang Keluar'),
        array('peminjaman',       'Peminjaman'),
    ),
    'Laporan' => array(
        array('riwayat', 'Riwayat Inventaris'),
        array('rekap',   'Rekap Inventaris'),
    ),
);
if ($role === 'admin') {
    $menu['Pengaturan'] = array(array('pengguna', 'Pengguna'));
}
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= html_escape($title) ?> - Sistem Inventaris</title>
<script src="https://cdn.tailwindcss.com"></script>
<style type="text/tailwindcss">
@layer components {
  .input { @apply w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30; }
  .btn { @apply inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium transition; }
  .btn-primary { @apply bg-emerald-600 text-white hover:bg-emerald-700; }
  .btn-light { @apply bg-slate-100 text-slate-700 hover:bg-slate-200; }
  .btn-danger { @apply bg-rose-50 text-rose-700 hover:bg-rose-100; }
  .card { @apply bg-white rounded-2xl border border-slate-200/80 shadow-sm; }
  .th { @apply px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 bg-slate-50; }
  .td { @apply px-4 py-3 text-sm text-slate-700 border-t border-slate-100; }
  .lbl { @apply block text-sm font-medium text-slate-700 mb-1; }
  .err { @apply text-xs text-rose-600 mt-1; }
}
</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

<div id="overlay" class="fixed inset-0 z-20 hidden bg-slate-900/30 md:hidden print:hidden" onclick="toggleSide()"></div>

<div class="flex min-h-screen">

  <!-- Sidebar -->
  <aside id="side" class="fixed inset-y-0 left-0 z-30 flex w-64 -translate-x-full flex-col border-r border-slate-200 bg-white transition-transform md:static md:translate-x-0 print:hidden">
    <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-5">
      <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-600 text-lg font-bold text-white">I</div>
      <div>
        <div class="font-bold leading-tight text-slate-900">Inventaris</div>
        <div class="text-xs text-slate-500">SMK Assalafiyyah Sleman</div>
      </div>
    </div>

    <nav class="flex-1 space-y-5 overflow-y-auto px-3 py-4">
      <?php foreach ($menu as $grup => $items): $w = $warna[$grup]; ?>
        <div>
          <div class="mb-1.5 flex items-center gap-2 px-3 text-[11px] font-bold uppercase tracking-wider <?= $w[0] ?>">
            <span class="h-2 w-2 rounded-full <?= $w[1] ?>"></span><?= $grup ?>
          </div>
          <div class="space-y-0.5">
            <?php foreach ($items as $n): $on = strpos($uri, $n[0]) === 0; ?>
              <a href="<?= site_url($n[0]) ?>"
                 class="flex items-center justify-between rounded-lg px-3 py-2 text-sm transition <?= $on ? $w[2] . ' font-semibold' : 'text-slate-600 hover:bg-slate-100' ?>">
                <span><?= $n[1] ?></span>
                <?php if ($n[0] === 'peminjaman' && $pending > 0): ?>
                  <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-700"><?= $pending ?></span>
                <?php endif; ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </nav>

    <div class="border-t border-slate-100 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-9 w-9 flex-none items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-700"><?= html_escape(strtoupper(substr($nama, 0, 1))) ?></div>
        <div class="min-w-0">
          <div class="truncate text-sm font-semibold text-slate-800"><?= html_escape($nama) ?></div>
          <div class="text-xs text-slate-500"><?= ucfirst($role) ?></div>
        </div>
      </div>
      <a href="<?= site_url('auth/logout') ?>" class="mt-3 block rounded-lg bg-rose-50 py-2 text-center text-sm font-medium text-rose-700 hover:bg-rose-100">Keluar</a>
    </div>
  </aside>

  <main class="min-w-0 flex-1">
    <header class="flex items-center gap-3 border-b border-slate-200 bg-white px-4 py-4 md:px-8 print:hidden">
      <button class="rounded-lg bg-slate-100 px-3 py-1.5 text-sm md:hidden" onclick="toggleSide()">Menu</button>
      <h1 class="text-xl font-semibold text-slate-900"><?= html_escape($title) ?></h1>
    </header>

    <div class="space-y-6 p-4 md:p-8">
      <?php if ($this->session->flashdata('success')): ?>
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"><?= html_escape($this->session->flashdata('success')) ?></div>
      <?php endif; ?>
      <?php if ($this->session->flashdata('error')): ?>
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800"><?= html_escape($this->session->flashdata('error')) ?></div>
      <?php endif; ?>
<script>
function toggleSide() {
  document.getElementById('side').classList.toggle('-translate-x-full');
  document.getElementById('overlay').classList.toggle('hidden');
}
</script>