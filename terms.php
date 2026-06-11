<?php
session_start();
include('db.php');
$user = $_SESSION['username'] ?? '';
?>
<?php include 'header.php'; ?>
<main class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
    <section class="rounded-lg border border-slate-200 bg-white p-6 sm:p-8">
      <h1 class="text-3xl font-bold text-slate-900">Terms & Conditions</h1>
      <p class="mt-4 text-sm leading-7 text-slate-600">These terms explain how EasyKart can be used. By browsing or shopping on the site, you agree to follow them.</p>

      <h2 class="mt-8 text-xl font-semibold text-slate-900">1. Use of the Website</h2>
      <p class="mt-3 text-sm leading-7 text-slate-600">You agree to use EasyKart only for lawful purposes and in a way that does not harm the platform or other users.</p>

      <h2 class="mt-8 text-xl font-semibold text-slate-900">2. Accounts</h2>
      <p class="mt-3 text-sm leading-7 text-slate-600">You are responsible for keeping your login information secure and for activity under your account.</p>

      <h2 class="mt-8 text-xl font-semibold text-slate-900">3. Orders</h2>
      <p class="mt-3 text-sm leading-7 text-slate-600">Orders are subject to product availability, payment confirmation, and delivery details provided by you.</p>

      <h2 class="mt-8 text-xl font-semibold text-slate-900">4. Pricing and Availability</h2>
      <p class="mt-3 text-sm leading-7 text-slate-600">Prices and stock may change without notice. We try to keep information accurate, but errors can happen.</p>

      <h2 class="mt-8 text-xl font-semibold text-slate-900">5. Returns</h2>
      <p class="mt-3 text-sm leading-7 text-slate-600">Returns are handled according to our return policy. Please review item details before placing an order.</p>

      <h2 class="mt-8 text-xl font-semibold text-slate-900">6. Limitation of Liability</h2>
      <p class="mt-3 text-sm leading-7 text-slate-600">EasyKart is not liable for indirect losses caused by website outages, shipping delays, or third-party services beyond our control.</p>

      <h2 class="mt-8 text-xl font-semibold text-slate-900">7. Changes</h2>
      <p class="mt-3 text-sm leading-7 text-slate-600">We may update these terms when needed. Continued use of the site means you accept the latest version.</p>

      <div class="mt-8 border-t border-slate-200 pt-6 text-center">
        <a href="index.php" class="inline-flex items-center rounded border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
          <i class="fa-solid fa-arrow-left mr-2"></i> Back to Home
        </a>
      </div>
    </section>
  </main>

<?php include 'footer.php'; ?>