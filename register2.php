<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register Form</title>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(135deg, #ffd6e0, #fff0f5);
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
    padding: 40px 30px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    color: #333;
    animation: fadeIn 1s ease;
}
@keyframes fadeIn {
    0% {opacity:0; transform: translateY(-20px);}
    100% {opacity:1; transform: translateY(0);}
}
.card h2 {
    margin-bottom: 30px;
    font-weight: 700;
    text-align: center;
    color: #d63384;
}
.form-control {
    border-radius: 10px;
    padding: 12px 15px;
    background: rgba(255,255,255,0.6);
    border: 1px solid #f5c6d0;
    color: #333;
}
.form-control:focus {
    background: rgba(255,255,255,0.8);
    color: #333;
    box-shadow: 0 0 0 0.2rem rgba(214,51,132,0.25);
}
.btn-primary {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    background: linear-gradient(135deg, #f78fb3, #d63384);
    border: none;
    font-weight: bold;
    color: white;
    transition: all 0.3s ease;
}
.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(214,51,132,0.3);
}
.text-center a {
    color: #d63384;
    text-decoration: underline;
    transition: all 0.3s ease;
}
.text-center a:hover {
    color: #f78fb3;
}
.text-danger {
    font-size: 0.9rem;
    margin-top: 5px;
    display: block;
}
</style>
</head>
<body>

<?php
// 🧠 Step 1: Create variables
$username = $email = $password = $cpassword = "";
$usernameErr = $emailErr = $passwordErr = $cpasswordErr = "";
$msg = "";

// 🧽 Step 2: Sanitize function
function sanitaize($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// 🧾 Step 3: Form validation
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Username
    if(empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } elseif(!preg_match("/^[a-zA-Z ]*$/", $_POST["username"])) {
        $usernameErr = "Only letters and spaces allowed";
    } else {
        $username = sanitaize($_POST["username"]);
    }

    // Email
    if(empty($_POST["email"])){
        $emailErr = "Email is required";
    }elseif(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
        $emailErr = "Invalid Email Format";
    }else{
        $email = sanitaize($_POST["email"]);
    }

    // Password
    if(empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $passwordInput = sanitaize($_POST["password"]);
        if(strlen($passwordInput) < 8) {
            $passwordErr = "Password must be at least 8 characters";
        } elseif(!preg_match("/^(?=.*[A-Za-z])(?=.*\d).+$/", $passwordInput)) {
            $passwordErr = "Password must contain at least one letter and one number";
        } else {
            $password = $passwordInput;
        }
    }

    // Confirm Password
    if(empty($_POST["cpassword"])) {
        $cpasswordErr = "Confirm Password is required";
    } elseif($_POST["cpassword"] != $_POST["password"]) {
        $cpasswordErr = "Passwords do not match";
    } else {
        $cpassword = sanitaize($_POST["cpassword"]);
    }


   // 🗃️ Step 5: If validation passes, store in DB

   if(empty($usernameErr) && empty($emailErr) && empty($passwordErr) && empty($cpasswordErr)){

    // include database file
    require_once 'db_connect1.php';

    // 🧠 Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0){
        $msg = "<div class='alert alert-danger text-center mt-3'>❌ Email already registered! Try another one.</div>";
    }else{

    // create password hashing 

    $hashedpassword = password_hash($password, PASSWORD_DEFAULT);

    // create statment object and create sql qurey 

    $stmt = $conn->prepare("INSERT INTO users (username,email,password) VALUES ( ?,?,?)");

    // create bindparam() values

    $stmt->bind_param("sss",$username,$email,$hashedpassword);

    // execute stmt prepare
    if($stmt->execute()){
    echo "<div class='alert alert-success text-center mt-3'>✅ Registered Successfully!</div>";
    
    // clear field box
    $username = $email = $password = "";

    // Redirect to login page after 2 seconds
    echo "<script>
            setTimeout(() => {
                window.location.href='login.php';
            }, 2000);
          </script>";
          exit;
        } else {
            $msg = "<div class='alert alert-danger text-center mt-3'>❌ Error: " . $stmt->error . "</div>";
        }


    // then finaly close the statment object and dbase connection its a secure method 

    $stmt->close();
    $conn->close();
}
$check->close();
}
}

?>

<!-- 🧱 Step 4: HTML Form -->
<div class="card">
    <h2>Register / Login System</h2>
    <?php if (!empty($msg)) echo $msg; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="mb-3">
            <input type="text" class="form-control" placeholder="Username" name="username" value="<?php echo $username; ?>">
            <span class="text-danger"><?php echo $usernameErr?></span>
        </div>
        <div class="mb-3">
            <input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo $email; ?>">
            <span class="text-danger"><?php echo $emailErr?></span>
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" placeholder="Password" name="password">
            <span class="text-danger"><?php echo $passwordErr?></span>
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" placeholder="Confirm Password" name="cpassword">
            <span class="text-danger"><?php echo $cpasswordErr?></span>
        </div>
        <button type="submit" class="btn btn-primary" name="register">Register</button>
         <p class="text-center mt-3">already you have an account? <a href="login.php">login</a></p>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<script>
setTimeout(() => {
  const alertBox = document.querySelector('.alert');
  if(alertBox) alertBox.style.display = 'none';
}, 3000);
</script>

</body>
</html>