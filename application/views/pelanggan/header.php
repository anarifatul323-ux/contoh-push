<?php defined('BASEPATH') OR exit('No direct script access allowed');
$uri = uri_string();
$nav = array(
    array('pelanggan', 'Beranda'),
    array('pelanggan/pinjam', 'Pinjam Barang'),
    array('pelanggan/riwayat', 'Riwayat Saya'),
);
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= html_escape($title) ?> - Peminjaman Barang</title>
<script src="https://cdn.tailwindcss.com"></script>
<style type="text/tailwindcss">
@layer components {
  .input { @apply w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500; }
  .btn { @apply inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium transition; }
  .btn-primary { @apply bg-emerald-600 text-white hover:bg-emerald-700; }
  .btn-light { @apply bg-slate-100 text-slate-700 hover:bg-slate-200; }
  .btn-danger { @apply bg-rose-50 text-rose-700 hover:bg-rose-100; }
  .card { @apply bg-white rounded-xl border border-slate-200 shadow-sm; }
  .th { @apply px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 bg-slate-50; }
  .td { @apply px-4 py-3 text-sm text-slate-700 border-t border-slate-100; }
  .lbl { @apply block text-sm font-medium text-slate-700 mb-1; }
  .err { @apply text-xs text-rose-600 mt-1; }
}
</style>
</head>
<body class="bg-slate-50 text-slate-800">
<header class="border-b border-slate-200 bg-white">
  <div class="mx-auto flex max-w-6xl flex-wrap items-center gap-4 px-5 py-3">
    <a href="<?= site_url('pelanggan') ?>" class="text-lg font-bold text-emerald-600">Peminjaman Barang</a>
    <nav class="flex gap-1 text-sm">
      <?php foreach ($nav as $n): $on = ($n[0] === 'pelanggan') ? ($uri === 'pelanggan') : strpos($uri, $n[0]) === 0; ?>
        <a href="<?= site_url($n[0]) ?>" class="rounded-lg px-3 py-2 <?= $on ? 'bg-emerald-50 font-medium text-emerald-700' : 'text-slate-600 hover:bg-slate-100' ?>"><?= $n[1] ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="ml-auto flex items-center gap-3 text-sm">
      <span class="text-slate-600"><?= html_escape($this->session->userdata('nama')) ?></span>
      <a href="<?= site_url('auth/logout') ?>" class="text-rose-600 hover:underline">Keluar</a>
    </div>
  </div>
</header>

<div class="mx-auto max-w-6xl space-y-6 px-5 py-8">
  <h1 class="text-2xl font-bold"><?= html_escape($title) ?></h1>
  <?php if ($this->session->flashdata('success')): ?>
    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"><?= html_escape($this->session->flashdata('success')) ?></div>
  <?php endif; ?>
  <?php if ($this->session->flashdata('error')): ?>
    <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800"><?= html_escape($this->session->flashdata('error')) ?></div>
  <?php endif; ?>