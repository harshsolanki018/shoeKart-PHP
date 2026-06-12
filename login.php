<?php
session_start();
include 'db.php';

$layout = 'auth';
$pageTitle = 'Login / Sign up - ShoeMart';

$mode = $_GET['mode'] ?? 'login';
$message = '';
$error = '';

function findUserByUsername(mysqli $conn, string $username): ?array
{
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result ? $result->fetch_assoc() : null;
    $stmt->close();

    return $user ?: null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'login';
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please fill in all required fields.';
    } elseif ($action === 'register') {
        if ($password !== $confirmPassword) {
            $error = 'Passwords do not match.';
        } elseif (findUserByUsername($conn, $username)) {
            $error = 'That username is already taken.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param('ss', $username, $hashedPassword);
            if ($stmt->execute()) {
                session_regenerate_id(true);
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $stmt->insert_id;
                header('Location: index.php');
                exit;
            }
            $error = 'Unable to create your account right now.';
            $stmt->close();
        }
    } else {
        $user = findUserByUsername($conn, $username);
        if (!$user) {
            $error = 'Invalid username or password.';
        } else {
            $storedPassword = (string) $user['password'];
            $valid = password_verify($password, $storedPassword) || hash_equals($storedPassword, $password);

            if ($valid) {
                if (!password_verify($password, $storedPassword)) {
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $update->bind_param('si', $newHash, $user['id']);
                    $update->execute();
                    $update->close();
                }

                session_regenerate_id(true);
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];
                header('Location: index.php');
                exit;
            }

            $error = 'Invalid username or password.';
        }
    }

    $mode = $action === 'register' ? 'register' : 'login';
}

include 'header.php';
?>

<main class="mx-auto flex min-h-[calc(100vh-5rem)] max-w-6xl items-center px-4 py-10 sm:px-6 lg:px-8">
    <div class="grid w-full gap-8 lg:grid-cols-[1fr_0.95fr]">
        <section class="rounded-[32px] border border-slate-200 bg-white p-8 shadow-[0_24px_60px_rgba(15,23,42,0.08)] sm:p-10">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-600">Account</p>
            <h1 class="mt-3 text-3xl font-black tracking-tight text-slate-900">Sign in or create your account</h1>
            <p class="mt-3 max-w-xl text-sm leading-6 text-slate-500">Use one account for orders, wishlist, and cart history. New users can register in a few seconds.</p>

            <?php if ($error): ?>
                <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($message): ?>
                <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <div class="mt-8 inline-flex rounded-full bg-slate-100 p-1">
                <a href="login.php?mode=login" class="<?= $mode !== 'register' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500' ?> rounded-full px-4 py-2 text-sm font-semibold">Login</a>
                <a href="login.php?mode=register" class="<?= $mode === 'register' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500' ?> rounded-full px-4 py-2 text-sm font-semibold">Sign up</a>
            </div>

            <form method="post" class="mt-8 grid gap-4">
                <input type="hidden" name="action" value="<?= $mode === 'register' ? 'register' : 'login' ?>">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Username</label>
                    <input type="text" name="username" required autocomplete="username" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                    <input type="password" name="password" required autocomplete="<?= $mode === 'register' ? 'new-password' : 'current-password' ?>" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                </div>

                <?php if ($mode === 'register'): ?>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Confirm password</label>
                        <input type="password" name="confirm_password" required autocomplete="new-password" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                    </div>
                <?php endif; ?>

                <button type="submit" class="mt-2 rounded-full bg-slate-900 px-5 py-3 font-semibold text-white transition hover:bg-slate-800">
                    <?= $mode === 'register' ? 'Create account' : 'Login' ?>
                </button>
            </form>
        </section>

        <aside class="rounded-[32px] border border-slate-200 bg-slate-900 p-8 text-white shadow-[0_24px_60px_rgba(15,23,42,0.12)] sm:p-10">
            <div class="flex h-full flex-col justify-between gap-8">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-300">ShoeMart</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight">Simple shopping, one account.</h2>
                    <p class="mt-4 max-w-md text-sm leading-7 text-slate-300">Sign in to save wishlist items, check your cart, and place orders with a clean, low-friction flow.</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm font-semibold">Wishlist</p>
                        <p class="mt-2 text-sm text-slate-300">Save products for later</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm font-semibold">Cart</p>
                        <p class="mt-2 text-sm text-slate-300">Keep checkout simple</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm font-semibold">Orders</p>
                        <p class="mt-2 text-sm text-slate-300">Track order history</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</main>

<?php include 'footer.php'; ?>
