<?php 
include 'includes/db.php';
include 'includes/auth.php';
include 'includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$email = trim($_POST['email']);
$password = trim($_POST['password']);

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
$error = "Invalid email or password.";
} else {
if (password_verify($password, $user['password'])) {
// Login success 
login_user_by_id($user['id']);

// Redirect to requested page or home
$redirect = $_GET['redirect'] ?? 'index.php';
header("Location: " . $redirect);
exit;
} else {
$error = "Invalid email or password.";
}
}
}
?>

<div class="container py-4">
<div class="row justify-content-center">
<div class="col-md-6">
<h2 class="fw-semibold mb-4">Login</h2>

<?php if (!empty($error)): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form id="loginForm" method="POST" class="card shadow-sm p-4" novalidate>
<div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-control" required>
</div>

<button type="submit" class="btn btn-primary">Login</button>
</form>

<p class="mt-3 text-center">Don't have an account? <a href="register.php">Register here</a></p>
</div>
</div>
</div>

<?php include 'includes/footer.php'; ?>
