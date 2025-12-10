<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Form</title>
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
    color: #d63384; /* rose accent */
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
    background: linear-gradient(135deg, #f78fb3, #d63384); /* rose button gradient */
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

session_start();

// initialaize variable 

$username=$password="";

$usernameErr=$passwordErr="";

$msg="";

// sanitaize data

function sanitaize($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// check form submission 

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // USERNAME validation 

    if(empty($_POST["username"])){
        $usernameErr = "Username is reuired";
    }else{
        $username = sanitaize($_POST["username"]);
    }

    // password validation 

    if(empty($_POST["password"])){
        $passwordErr = "password is required";
    }else{
        $password = sanitaize($_POST["password"]);
    }


    // check all error are clear

    if(empty($usernameErr) && empty($passwordErr)){

        // include db file

        require_once 'db_connect1.php';

        //create a sql query use stmt and prepare
        $stmt = $conn->prepare("SELECT id,username,password FROM users WHERE username=?");

        // create bindparam ()

        $stmt->bind_param("s",$username);

        // execute the query

        $stmt->execute();

        // create get_resutlt

        $result = $stmt->get_result();

        // check num_rows
      if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        
        if(password_verify($password, $row["password"])){

        // Start session
        session_start();

        // Set session variables
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];

        $msg = "<div class='alert alert-success text-center mt-3'>✅ Login Successful!</div>";
        
        // Redirect immediately to dashboard
        
        header("Location: dashboard.php");
        exit;
    } else {
        $msg = "<div class='alert alert-danger text-center mt-3'>❌ Incorrect password!</div>";
    }
} else {
    $msg = "<div class='alert alert-danger text-center mt-3'>❌ Username not found!</div>";
}


        $stmt->close();
        $conn->close();

    }
}
?>

<div class="card">
    <h2>Login</h2>
    <?php echo $msg; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
        <div class="mb-3">
            <input type="text" class="form-control" placeholder="Username" name="username" value="<?php echo $username; ?>">
            <span class="text-danger"><?php echo $usernameErr?></span>
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" placeholder="Password" name="password" value="<?php echo $password; ?>">
             <span class="text-danger"><?php echo $passwordErr?></span>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <p class="text-center mt-3">Don't have an account? <a href="register2.php">Register</a></p>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
