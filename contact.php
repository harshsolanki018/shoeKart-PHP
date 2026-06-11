<?php
session_start();
include('db.php');
$user = $_SESSION['username'] ?? '';

$errors = [];
$name = '';
$email = '';
$subject = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name)) $errors[] = "Please enter your name.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Please enter a valid email address.";
    if (empty($subject)) $errors[] = "Please enter a subject.";
    if (empty($message)) $errors[] = "Please enter your message.";

    if (empty($errors)) {
        echo "<script>
                alert('Thank you, " . addslashes($name) . "! Your message has been received. We\\'ll get back to you soon.');
                window.location.href='index.php';
              </script>";
        exit();
    }
}
?>
<?php include 'header.php'; ?>
<div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
    <div class="grid gap-6 lg:grid-cols-2">
        <section class="rounded-lg border border-slate-200 bg-white p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-600">Contact</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Get in Touch</h1>
            <p class="mt-3 text-sm leading-6 text-slate-600">Reach out if you need help with an order, product, or account.</p>

            <div class="mt-6 space-y-4 text-sm text-slate-700">
                <p class="flex items-start gap-3"><i class="fa-solid fa-location-dot mt-1 text-blue-600"></i> 123 EasyKart Plaza, Mumbai, India</p>
                <p class="flex items-start gap-3"><i class="fa-solid fa-envelope mt-1 text-blue-600"></i> <a href="mailto:support@easykart.com" class="underline">support@easykart.com</a></p>
                <p class="flex items-start gap-3"><i class="fa-solid fa-phone mt-1 text-blue-600"></i> <a href="tel:+919876543210" class="underline">+91 98765 43210</a></p>
            </div>

            <div class="mt-6 overflow-hidden rounded-lg border border-slate-200">
                <iframe
                    src="https://www.google.com/maps?q=EasyKart+Plaza,+Mumbai,+India&output=embed"
                    width="100%"
                    height="260"
                    style="border:0;"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-600">Message</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-900">Contact Us</h2>

            <?php if (!empty($errors)): ?>
                <div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-disc space-y-1 pl-5">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="mt-6 space-y-4">
                <input type="text" name="name" placeholder="Your Full Name" value="<?= htmlspecialchars($name) ?>" required class="w-full rounded border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-blue-500">
                <input type="email" name="email" placeholder="Your Email Address" value="<?= htmlspecialchars($email) ?>" required class="w-full rounded border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-blue-500">
                <input type="text" name="subject" placeholder="Subject" value="<?= htmlspecialchars($subject) ?>" required class="w-full rounded border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-blue-500">
                <textarea name="message" placeholder="Write your message..." required class="min-h-40 w-full rounded border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-blue-500"><?= htmlspecialchars($message) ?></textarea>
                <button type="submit" class="rounded bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Send Message</button>
            </form>

            <div class="mt-6">
                <a href="index.php" class="inline-flex items-center rounded border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Back to Site
                </a>
            </div>
        </section>
    </div>
</div>

<?php include 'footer.php'; ?>