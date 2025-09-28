<?php
session_start();
include "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Prepare statement to prevent SQL injection
    $sql = "SELECT id, username, email, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $users = $result->fetch_assoc();
        
        // Verify password using password_verify (assuming passwords are hashed with password_hash)
        if (password_verify($password, $users['password'])) {
            $_SESSION['user_id'] = $users['id'];
            $_SESSION['email'] = $users['email'];
            $_SESSION['username'] = $users['username'];
            $_SESSION['role'] = $users['role'];
            
            header("Location: Dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "Invalid email or password!";
    }
    
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<style>
    html, body {
        height: 100%;
        margin: 0;
    }
    .Login-container {
        margin-top: 80px;
        width: 90%;
        height: 100%;
        padding: 10px;
    }
    .input-label{
        font-size: 1rem;
        font-weight: 500;
        color: #374151;
    }
    .text-heading-main{
        text-align: center;
        font-size: 30px;
        font-weight: 700;
        color: black;
    }
    .text-subheading{
        text-align: center;
        font-size: 20px;
        font-weight: 600;
        color: #dc2626;
        margin-bottom: 30px;
    }
    .input-component {
        width: 100%;
        padding: 12px; 
        border: 1px solid #d1d5db;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 16px;
        transition: border-color 0.3s, box-shadow 0.3s;
    }
    .input-component:focus {
        outline: none;
        border-color: #C8EE44;
        box-shadow: 0 0 0 3px rgba(200, 238, 68, 0.2);
    }
    .signin-button{
        width: 100%;
        background-color: #C8EE44;
        height: 45px;
        margin-top: 10px;
        font-size: 18px;
        font-weight: 600;
        border-radius: 10px;
        border: none;
        transition: background-color 0.3s;
    }
    .signin-button:hover{
        background-color: #A3C644;
        cursor: pointer;
    }
    .main-container{
        display: flex;
        flex-direction: row;
        height: 100vh;
    }
    .error-message {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        color: #dc2626;
        padding: 12px;
        border-radius: 8px;
        margin-top: 15px;
        text-align: center;
    }
    .company-name {
        font-size: 1.875rem;
        font-weight: 500;
        font-family: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
        color: #1f2937;
    }
    @media (max-width: 768px) {
        .main-container {
            flex-direction: column;
        }
        .w-1\/2 {
            width: 100%;
        }
        .Login-container {
            margin-top: 40px;
        }
    }
</style>
    
<body>
    <div class="main-container flex h-screen">
        <div class="w-1/2 p-6 flex flex-col">
            <p class="company-name">Battery Panel System</p>

            <div class="Login-container mt-10 flex-1">
                <p class="text-heading-main text-3xl font-bold">Welcome Back</p>
                <p class="text-subheading">For Admin Only Login</p>

                <form method="POST" action="">
                    <div class="mb-4">
                        <label class="input-label block mb-2">Email:</label>
                        <input type="email" name="email" required 
                               class="input-component border rounded p-2 w-full" 
                               placeholder="Enter your email"
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"/>
                    </div>

                    <div class="mb-4">
                        <label class="input-label block mb-2">Password:</label>
                        <input type="password" name="password" required 
                               class="input-component border rounded p-2 w-full" 
                               placeholder="Enter your password"/>
                    </div>

                    <button type="submit" class="signin-button bg-lime-400 hover:bg-lime-500 text-black font-bold py-2 px-4 rounded w-full mt-4">
                        Sign in
                    </button>
                </form>
                
                <?php if (!empty($error)): ?>
                    <div class="error-message mt-4">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <div class="mt-6 text-center text-sm text-gray-600">
                    <p>Don't have an account? Contact administrator to create one.</p>
                </div>
            </div>
        </div>
        <div class="w-1/2">
            <img class="object-cover h-full w-full" src="image.png" alt="Battery System Image"/>
        </div>
    </div>
</body>
</html>