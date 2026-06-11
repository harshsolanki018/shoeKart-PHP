<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if ($username === 'Admin' && $password === 'Admin@1234') {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_name'] = $username;
    header("Location: admin_dashboard.php");
    exit;
  } else {
    $error = "Invalid credentials!";
  }
}
?>

<?php include 'header.php'; ?>
<div class="flex min-h-screen items-center justify-center px-4 py-10">
    <form method="post" class="w-full max-w-md rounded-[28px] border border-slate-200 bg-white p-8 shadow-[0_24px_60px_rgba(15,23,42,0.12)]">
      <div class="mb-8 text-center">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-600">Admin access</p>
        <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900">Admin Login</h1>
        <p class="mt-3 text-sm leading-6 text-slate-500">Use the dashboard account to manage products and orders.</p>
      </div>
      <label class="mb-2 block text-sm font-semibold text-slate-700">Username</label>
      <input type="text" name="username" placeholder="Username" required class="mb-4 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
      <label class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
      <input type="password" name="password" placeholder="Password" required class="mb-4 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
      <?php if ($error): ?>
        <p class="mb-4 rounded-2xl bg-red-50 px-4 py-3 text-sm font-medium text-red-700"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>
      <button type="submit" class="w-full rounded-full bg-gradient-to-r from-blue-600 to-cyan-500 px-5 py-3 font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:-translate-y-0.5">Login</button>
    </form>
  </div>

<?php include 'footer.php'; ?>