<?php
session_start();
include 'db.php';

$layout = 'admin';
$pageTitle = 'Admin Dashboard - ShoeMart';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: admin_login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add_product') {
        $name = trim($_POST['name']);
        $brand = trim($_POST['brand']);
        $price = floatval($_POST['price']);
        $discount = floatval($_POST['discount']);
        $image = trim($_POST['image_link']);
        $custom_id = isset($_POST['custom_id']) ? intval($_POST['custom_id']) : 0;

        if ($name && $brand && $price && $image) {
            if ($custom_id > 0) {
                $stmt = $conn->prepare("SELECT id FROM products WHERE id=?");
                $stmt->bind_param('i', $custom_id);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    echo json_encode(['error' => 'Product ID already exists.']);
                    exit;
                }
                $stmt->close();

                $stmt = $conn->prepare("INSERT INTO products (id, name, brand, price, discount, image_link) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('issdds', $custom_id, $name, $brand, $price, $discount, $image);
                $stmt->execute();
                $new_id = $custom_id;
            } else {
                $stmt = $conn->prepare("INSERT INTO products (name, brand, price, discount, image_link) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param('ssdds', $name, $brand, $price, $discount, $image);
                $stmt->execute();
                $new_id = $stmt->insert_id;
            }

            echo json_encode([
                'success' => true,
                'id' => $new_id,
                'name' => $name,
                'brand' => $brand,
                'price' => $price,
                'discount' => $discount,
                'image_link' => $image,
            ]);
        } else {
            echo json_encode(['error' => 'Please fill all required fields.']);
        }
        exit;
    }

    if ($action === 'update_user') {
        $id = intval($_POST['id']);
        $username = trim($_POST['username']);
        $stmt = $conn->prepare("UPDATE users SET username=? WHERE id=?");
        $stmt->bind_param('si', $username, $id);
        $stmt->execute();
        echo 'success';
        exit;
    }

    if ($action === 'update_product') {
        $old_id = intval($_POST['id']);
        $new_id = intval($_POST['new_id']);
        $name = trim($_POST['name']);
        $brand = trim($_POST['brand']);
        $price = floatval($_POST['price']);
        $discount = floatval($_POST['discount']);
        $image_link = trim($_POST['image_link']);

        $stmt = $conn->prepare("SELECT id FROM products WHERE id=? AND id!=?");
        $stmt->bind_param('ii', $new_id, $old_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            echo 'error: ID already exists';
            exit;
        }
        $stmt->close();

        $stmt = $conn->prepare("UPDATE products SET id=?, name=?, brand=?, price=?, discount=?, image_link=? WHERE id=?");
        $stmt->bind_param('issddsi', $new_id, $name, $brand, $price, $discount, $image_link, $old_id);
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error: ' . $stmt->error;
        }
        exit;
    }

    if ($action === 'update_order') {
        $id = intval($_POST['id']);
        $status = trim($_POST['status']);
        $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
        $stmt->bind_param('si', $status, $id);
        $stmt->execute();
        echo 'success';
        exit;
    }

    if ($action === 'delete_item') {
        $table = $_POST['table'];
        $id = intval($_POST['id']);
        $allowed = ['users', 'products', 'orders'];
        if (in_array($table, $allowed, true)) {
            $stmt = $conn->prepare("DELETE FROM `$table` WHERE id=?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            echo 'success';
        } else {
            echo 'invalid_table';
        }
        exit;
    }
}

$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$total_orders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
$complete_orders_count = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE status='complete'")->fetch_assoc()['total'];
$complete_orders_amount = $conn->query("
    SELECT SUM(p.price) AS revenue
    FROM orders o
    JOIN products p ON o.product_id = p.id
    WHERE o.status='complete'
")->fetch_assoc()['revenue'] ?? 0;

include 'header.php';
?>

<main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-600">Admin dashboard</p>
        <h1 class="mt-2 text-3xl font-bold text-slate-900">Overview</h1>
        <p class="mt-2 text-sm text-slate-500">A simple admin view for users, products, and orders.</p>
    </section>

    <section id="dashboard" class="mt-6">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total users</p>
                <p class="mt-3 text-3xl font-bold text-slate-900"><?php echo $total_users; ?></p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total orders</p>
                <p class="mt-3 text-3xl font-bold text-slate-900"><?php echo $total_orders; ?></p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Completed orders</p>
                <p class="mt-3 text-3xl font-bold text-slate-900"><?php echo $complete_orders_count; ?></p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Revenue</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">₹<?php echo number_format((float) $complete_orders_amount, 2); ?></p>
            </div>
        </div>
    </section>

    <section id="users" class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Users</h2>
                <p class="mt-1 text-sm text-slate-500">Quickly rename accounts or remove users.</p>
            </div>
        </div>
        <div class="mt-5 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold">ID</th>
                        <th class="px-4 py-3 font-semibold">Username</th>
                        <th class="px-4 py-3 font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php
                    $users = $conn->query("SELECT * FROM users");
                    while ($u = $users->fetch_assoc()):
                    ?>
                        <tr id="row-users-<?= $u['id'] ?>">
                            <td class="px-4 py-3 text-slate-700"><?= htmlspecialchars($u['id']) ?></td>
                            <td class="px-4 py-3 text-slate-900"><?= htmlspecialchars($u['username']) ?></td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <button onclick='editUser(<?= (int) $u["id"] ?>, <?= json_encode($u["username"]) ?>)' class="rounded-full border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100">Edit</button>
                                    <button onclick='deleteItem("users", <?= (int) $u["id"] ?>)' class="rounded-full bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-700">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section id="products" class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Products</h2>
                <p class="mt-1 text-sm text-slate-500">Create, update, or remove products from the catalog.</p>
            </div>
            <button onclick="addProductModal()" class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                <i class="bi bi-plus-circle"></i>
                Add product
            </button>
        </div>
        <div class="mt-5 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold">ID</th>
                        <th class="px-4 py-3 font-semibold">Image</th>
                        <th class="px-4 py-3 font-semibold">Name</th>
                        <th class="px-4 py-3 font-semibold">Brand</th>
                        <th class="px-4 py-3 font-semibold">Price</th>
                        <th class="px-4 py-3 font-semibold">Discount</th>
                        <th class="px-4 py-3 font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php
                    $products = $conn->query("SELECT * FROM products");
                    while ($p = $products->fetch_assoc()):
                    ?>
                        <tr id="row-products-<?= $p['id'] ?>">
                            <td class="px-4 py-3 text-slate-700"><?= htmlspecialchars($p['id']) ?></td>
                            <td class="px-4 py-3"><img src="<?= htmlspecialchars($p['image_link']) ?>" alt="" class="h-12 w-12 rounded object-cover"></td>
                            <td class="px-4 py-3 text-slate-900"><?= htmlspecialchars($p['name']) ?></td>
                            <td class="px-4 py-3 text-slate-700"><?= htmlspecialchars($p['brand']) ?></td>
                            <td class="px-4 py-3 text-slate-700">₹<?= number_format((float) $p['price'], 2) ?></td>
                            <td class="px-4 py-3 text-slate-700"><?= htmlspecialchars($p['discount']) ?>%</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <button onclick='editProduct(<?= (int) $p["id"] ?>, <?= json_encode($p["name"]) ?>, <?= json_encode($p["brand"]) ?>, <?= json_encode($p["price"]) ?>, <?= json_encode($p["discount"]) ?>, <?= json_encode($p["image_link"]) ?>)' class="rounded-full border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100">Edit</button>
                                    <button onclick='deleteItem("products", <?= (int) $p["id"] ?>)' class="rounded-full bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-700">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section id="orders" class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Orders</h2>
            <p class="mt-1 text-sm text-slate-500">Update order status or remove records when needed.</p>
        </div>
        <div class="mt-5 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold">ID</th>
                        <th class="px-4 py-3 font-semibold">Product</th>
                        <th class="px-4 py-3 font-semibold">Image</th>
                        <th class="px-4 py-3 font-semibold">Amount</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php
                    $orders = $conn->query("SELECT * FROM orders");
                    while ($o = $orders->fetch_assoc()):
                        $product = $conn->query("SELECT name,image_link,price FROM products WHERE id={$o['product_id']}")->fetch_assoc();
                    ?>
                        <tr id="row-orders-<?= $o['id'] ?>">
                            <td class="px-4 py-3 text-slate-700"><?= htmlspecialchars($o['id']) ?></td>
                            <td class="px-4 py-3 text-slate-900"><?= htmlspecialchars($product['name'] ?? '') ?></td>
                            <td class="px-4 py-3"><img src="<?= htmlspecialchars($product['image_link'] ?? '') ?>" alt="" class="h-12 w-12 rounded object-cover"></td>
                            <td class="px-4 py-3 text-slate-700">₹<?= number_format((float)($product['price'] ?? 0), 2) ?></td>
                            <td class="px-4 py-3 text-slate-700"><?= htmlspecialchars($o['status']) ?></td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <button onclick='editOrder(<?= (int) $o["id"] ?>, <?= json_encode($o["status"]) ?>)' class="rounded-full border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100">Edit</button>
                                    <button onclick='deleteItem("orders", <?= (int) $o["id"] ?>)' class="rounded-full bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-700">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<div id="modal" class="fixed inset-0 z-[2000] hidden items-center justify-center bg-slate-950/60 p-4">
    <div class="w-full max-w-xl rounded-3xl bg-white p-5 shadow-2xl">
        <div id="modal-content"></div>
    </div>
</div>

<script>
function showModal(html) {
    document.getElementById('modal-content').innerHTML = html;
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modal').classList.add('flex');
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
    document.getElementById('modal').classList.remove('flex');
}

function addProductModal() {
    showModal(`
        <div class="space-y-4">
            <div>
                <h3 class="text-2xl font-bold text-slate-900">Add New Product</h3>
                <p class="mt-1 text-sm text-slate-500">Keep the product form simple and consistent.</p>
            </div>
            <div class="grid gap-3">
                <input type="number" id="pid_new" placeholder="Custom ID (optional)" class="rounded-xl border border-slate-200 px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                <input type="text" id="pname" placeholder="Product name" class="rounded-xl border border-slate-200 px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                <input type="text" id="pbrand" placeholder="Brand" class="rounded-xl border border-slate-200 px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                <input type="number" id="pprice" placeholder="Price" class="rounded-xl border border-slate-200 px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                <input type="number" id="pdiscount" placeholder="Discount" class="rounded-xl border border-slate-200 px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                <input type="text" id="pimage" placeholder="Image URL" class="rounded-xl border border-slate-200 px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
            </div>
            <div class="flex gap-3 pt-2">
                <button onclick="saveNewProduct()" class="rounded-full bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">Save</button>
                <button onclick="closeModal()" class="rounded-full border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Cancel</button>
            </div>
        </div>
    `);
}

function saveNewProduct() {
    const id = document.getElementById('pid_new').value.trim();
    const name = document.getElementById('pname').value.trim();
    const brand = document.getElementById('pbrand').value.trim();
    const price = document.getElementById('pprice').value.trim();
    const discount = document.getElementById('pdiscount').value.trim();
    const image = document.getElementById('pimage').value.trim();

    if (!name || !brand || !price || !image) {
        alert('Fill all required fields.');
        return;
    }

    let body = `action=add_product&name=${encodeURIComponent(name)}&brand=${encodeURIComponent(brand)}&price=${price}&discount=${discount}&image_link=${encodeURIComponent(image)}`;
    if (id) {
        body += `&custom_id=${id}`;
    }

    fetch('admin_dashboard.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.error) {
            alert(data.error);
            return;
        }
        if (data.success) {
            location.reload();
        }
    });
}

function editUser(id, username) {
    showModal(`
        <div class="space-y-4">
            <div>
                <h3 class="text-2xl font-bold text-slate-900">Edit User</h3>
            </div>
            <input type="text" id="username" value="${username}" class="w-full rounded-xl border border-slate-200 px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
            <div class="flex gap-3">
                <button onclick="saveUser(${id})" class="rounded-full bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">Save</button>
                <button onclick="closeModal()" class="rounded-full border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Cancel</button>
            </div>
        </div>
    `);
}

function saveUser(id) {
    const username = document.getElementById('username').value;
    fetch('admin_dashboard.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=update_user&id=${id}&username=${encodeURIComponent(username)}`,
    }).then(() => location.reload());
}

function editProduct(id, name, brand, price, discount, image_link) {
    showModal(`
        <div class="space-y-4">
            <div>
                <h3 class="text-2xl font-bold text-slate-900">Edit Product</h3>
            </div>
            <div class="grid gap-3">
                <input type="number" id="pid" value="${id}" class="rounded-xl border border-slate-200 px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                <input type="text" id="pname" value="${name}" class="rounded-xl border border-slate-200 px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                <input type="text" id="pbrand" value="${brand}" class="rounded-xl border border-slate-200 px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                <input type="number" id="pprice" value="${price}" class="rounded-xl border border-slate-200 px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                <input type="number" id="pdiscount" value="${discount}" class="rounded-xl border border-slate-200 px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                <input type="text" id="pimage" value="${image_link}" class="rounded-xl border border-slate-200 px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
            </div>
            <div class="flex gap-3">
                <button onclick="saveProduct(${id})" class="rounded-full bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">Save</button>
                <button onclick="closeModal()" class="rounded-full border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Cancel</button>
            </div>
        </div>
    `);
}

function saveProduct(oldId) {
    const newId = document.getElementById('pid').value;
    const name = document.getElementById('pname').value;
    const brand = document.getElementById('pbrand').value;
    const price = document.getElementById('pprice').value;
    const discount = document.getElementById('pdiscount').value;
    const image = document.getElementById('pimage').value;

    fetch('admin_dashboard.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=update_product&id=${oldId}&new_id=${newId}&name=${encodeURIComponent(name)}&brand=${encodeURIComponent(brand)}&price=${price}&discount=${discount}&image_link=${encodeURIComponent(image)}`,
    })
    .then((response) => response.text())
    .then((res) => {
        if (res.trim() === 'success') {
            location.reload();
        } else {
            alert('Error: ' + res);
        }
    });
}

function editOrder(id, status) {
    showModal(`
        <div class="space-y-4">
            <div>
                <h3 class="text-2xl font-bold text-slate-900">Edit Order</h3>
            </div>
            <select id="ostatus" class="w-full rounded-xl border border-slate-200 px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                <option value="pending"${status === 'pending' ? ' selected' : ''}>Pending</option>
                <option value="process"${status === 'process' ? ' selected' : ''}>Process</option>
                <option value="complete"${status === 'complete' ? ' selected' : ''}>Complete</option>
                <option value="cancel"${status === 'cancel' ? ' selected' : ''}>Cancel</option>
            </select>
            <div class="flex gap-3">
                <button onclick="saveOrder(${id})" class="rounded-full bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">Save</button>
                <button onclick="closeModal()" class="rounded-full border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Cancel</button>
            </div>
        </div>
    `);
}

function saveOrder(id) {
    const status = document.getElementById('ostatus').value;
    fetch('admin_dashboard.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=update_order&id=${id}&status=${status}`,
    }).then(() => location.reload());
}

function deleteItem(table, id) {
    if (!confirm('Delete this item?')) {
        return;
    }

    fetch('admin_dashboard.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=delete_item&table=${table}&id=${id}`,
    })
    .then((response) => response.text())
    .then((res) => {
        if (res.trim() === 'success') {
            const row = document.getElementById(`row-${table}-${id}`);
            if (row) {
                row.remove();
            }
        } else {
            alert('Delete failed: ' + res);
        }
    });
}
</script>
<?php include 'footer.php'; ?>
