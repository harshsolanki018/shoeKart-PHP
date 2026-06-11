<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $_SESSION['username'] = $username;
  $conn->query("INSERT INTO users (username, password) VALUES ('$username', '$password')");
  header("Location: index.php");
  exit;
}
?>

<?php include 'header.php'; ?>
<div class="flex min-h-screen items-center justify-center px-4 py-10">
    <form method="post" class="w-full max-w-md rounded-[28px] border border-slate-200 bg-white p-8 shadow-[0_24px_60px_rgba(15,23,42,0.12)]">
      <div class="mb-8 text-center">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-600">Welcome</p>
        <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900">User Login</h1>
        <p class="mt-3 text-sm leading-6 text-slate-500">Sign in to save your favorites and place orders faster.</p>
      </div>
      <label class="mb-2 block text-sm font-semibold text-slate-700">Username</label>
      <input type="text" name="username" placeholder="Username" required class="mb-4 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
      <label class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
      <input type="password" name="password" placeholder="Password" required class="mb-6 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
      <button type="submit" class="w-full rounded-full bg-gradient-to-r from-blue-600 to-cyan-500 px-5 py-3 font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:-translate-y-0.5">Login</button>
    </form>
  </div>

<?php include 'footer.php'; ?>