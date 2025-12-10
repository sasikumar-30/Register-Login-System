<?php
session_start(); // Always start session

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(135deg, #ffd6e0, #fff0f5); /* rose milk gradient */
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Poppins', sans-serif;
}
.card {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    padding: 50px 40px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    color: #333;
    animation: fadeIn 1s ease;
    text-align: center;
}
@keyframes fadeIn {
    0% {opacity:0; transform: translateY(-20px);}
    100% {opacity:1; transform: translateY(0);}
}
.card h2 {
    margin-bottom: 20px;
    font-weight: 700;
    color: #d63384;
}
.card p {
    font-size: 1.1rem;
    margin-bottom: 30px;
}
.btn-danger {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    font-weight: bold;
    color: white;
    border: none;
    background: linear-gradient(135deg, #f78fb3, #d63384);
    transition: all 0.3s ease;
}
.btn-danger:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(214,51,132,0.3);
}
</style>
</head>
<body>
<div class="card">
    <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    <p>You are now logged in Users dashboard.</p>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
