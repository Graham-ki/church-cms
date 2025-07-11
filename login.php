<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/styles.css">
    <style>
        /* Login Page Specific Styles */
        .login-hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('public/images/banner.jpeg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border-radius: 15px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            margin: 20px;
            transition: all 0.3s ease;
        }
        
        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .login-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: block;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .login-title {
            color: white;
            margin-bottom: 30px;
            font-size: 1.8rem;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 25px;
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
        }
        
        .login-input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }
        
        .login-input:focus {
            outline: none;
            border-color: var(--accent-color);
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.3);
        }
        
        .login-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 0.9rem;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-right: 8px;
        }
        
        .forgot-password {
            color: var(--accent-color);
            text-decoration: none;
        }
        
        .login-btn {
            width: 100%;
            padding: 12px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        
        .login-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-3px);
        }
        
        .social-login {
            margin: 25px 0;
            text-align: center;
        }
        
        .social-login p {
            position: relative;
            margin-bottom: 20px;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .social-login p::before,
        .social-login p::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 30%;
            height: 1px;
            background: rgba(255, 255, 255, 0.3);
        }
        
        .social-login p::before {
            left: 0;
        }
        
        .social-login p::after {
            right: 0;
        }
        
        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .social-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s ease;
        }
        
        .social-icon:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .register-link a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <!-- Login Content (No Navigation on Login Page) -->
    <section class="login-hero">
        <div class="login-container glass-card">
            <img src="public/images/logo.png" alt="Church Logo" class="login-logo">
            <h1 class="login-title">Member Login</h1>
            
            <form>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" class="login-input" placeholder="Email Address" required>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" class="login-input" placeholder="Password" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-check"></i>
                  <select class="login-input" required>
                    <option value="" disabled selected>Role</option>
                    <option value="member" style="color: green;">Member</option>
                    <option value="admin" style="color: blue;">Admin</option>
                    <option value="guest" style="color: orange;">Clergy</option>
                  </select>
                </div>
                <div class="remember-forgot">
                    <label class="remember-me">
                        <input type="checkbox"> Remember me
                    </label>
                    <a href="forgot-password.html" class="forgot-password">Forgot password?</a>
                </div>
                
                <button type="submit" class="login-btn">Login <i class="fas fa-sign-in-alt"></i></button>
                <div class="register-link">
                    Don't have an account? <a href="register">Register here</a>
                </div>
            </form>
        </div>
    </section>

    <script src="public/js/scripts.js"></script>
</body>
</html>