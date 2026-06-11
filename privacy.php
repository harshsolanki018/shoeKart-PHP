<?php
session_start();
include('db.php');
$user = $_SESSION['username'] ?? '';
?>
<?php include 'header.php'; ?>
<main class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
    <section class="rounded-lg border border-slate-200 bg-white p-6 sm:p-8">
      <h1 class="text-3xl font-bold text-slate-900">Privacy Policy</h1>
      <p class="mt-4 text-sm leading-7 text-slate-600">Welcome to EasyKart. We value your trust and are committed to protecting your privacy. This policy explains how we collect, use, and protect your information.</p>

      <h2 class="mt-8 text-xl font-semibold text-slate-900">1. Information We Collect</h2>
      <ul class="mt-3 list-disc space-y-2 pl-5 text-sm leading-7 text-slate-600">
        <li>Personal details such as your name, email, address, and contact number.</li>
        <li>Login credentials for your EasyKart account.</li>
        <li>Order history, wishlist items, and payment information.</li>
        <li>Technical information such as IP address, browser type, and cookies.</li>
      </ul>

      <h2 class="mt-8 text-xl font-semibold text-slate-900">2. How We Use Your Information</h2>
      <ul class="mt-3 list-disc space-y-2 pl-5 text-sm leading-7 text-slate-600">
        <li>Process and deliver your orders.</li>
        <li>Provide customer support and resolve queries.</li>
        <li>Send promotional offers and product updates, if opted in.</li>
        <li>Improve our website design and functionality.</li>
      </ul>

      <h2 class="mt-8 text-xl font-semibold text-slate-900">3. Data Security</h2>
      <p class="mt-3 text-sm leading-7 text-slate-600">We use secure servers and encryption protocols to protect your data from unauthorized access, alteration, or disclosure. No method of transmission over the Internet is fully secure, so we also encourage careful account use.</p>

      <h2 class="mt-8 text-xl font-semibold text-slate-900">4. Sharing of Information</h2>
      <p class="mt-3 text-sm leading-7 text-slate-600">We do not sell or rent your personal data. We may share limited information with trusted third-party service providers to fulfill your orders.</p>

      <h2 class="mt-8 text-xl font-semibold text-slate-900">5. Cookies</h2>
      <p class="mt-3 text-sm leading-7 text-slate-600">We use cookies to personalize your shopping experience, analyze site traffic, and improve performance. You can disable cookies in your browser settings.</p>

      <h2 class="mt-8 text-xl font-semibold text-slate-900">6. Your Rights</h2>
      <p class="mt-3 text-sm leading-7 text-slate-600">You can access, update, or delete your account information anytime by logging in. You can also contact us to request data deletion.</p>

      <h2 class="mt-8 text-xl font-semibold text-slate-900">7. Changes to This Policy</h2>
      <p class="mt-3 text-sm leading-7 text-slate-600">We may update this Privacy Policy occasionally. Please check this page regularly to stay informed of any changes.</p>

      <h2 class="mt-8 text-xl font-semibold text-slate-900">8. Contact Us</h2>
      <p class="mt-3 text-sm leading-7 text-slate-600">If you have any questions or concerns, contact us at <strong>support@easykart.com</strong>.</p>

      <div class="mt-8 border-t border-slate-200 pt-6">
        <a href="index.php" class="inline-flex items-center rounded border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
          <i class="fa-solid fa-arrow-left mr-2"></i> Back to Home
        </a>
      </div>
    </section>
  </main>

<?php include 'footer.php'; ?>