<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/styles.css">
    <style>
        /* Register Page Specific Styles */
        .register-hero {
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
        
        .register-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border-radius: 15px;
            padding: 40px;
            width: 100%;
            max-width: 500px;
            margin: 20px;
            transition: all 0.3s ease;
        }
        
        .register-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .register-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: block;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .register-title {
            color: white;
            margin-bottom: 30px;
            font-size: 1.8rem;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
        }
        
        .register-input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }
        
        .register-input:focus {
            outline: none;
            border-color: var(--accent-color);
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.3);
        }
        
        .register-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row .input-group {
            flex: 1;
        }
        
        .terms-agreement {
            display: flex;
            align-items: center;
            margin: 20px 0;
            font-size: 0.9rem;
        }
        
        .terms-agreement input {
            margin-right: 10px;
        }
        
        .terms-agreement a {
            color: var(--accent-color);
            text-decoration: none;
        }
        
        .register-btn {
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
        
        .register-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-3px);
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-link a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .member-type {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            gap: 10px;
        }
        
        .member-option {
            flex: 1;
            text-align: center;
        }
        
        .member-option input {
            display: none;
        }
        
        .member-option label {
            display: block;
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .member-option input:checked + label {
            background: var(--primary-color);
            box-shadow: 0 5px 15px rgba(74, 111, 165, 0.4);
        }
        
        .member-option i {
            display: block;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Register Content (No Navigation on Register Page) -->
    <section class="register-hero">
        <div class="register-container glass-card">
            <img src="public/images/logo.png" alt="Church Logo" class="register-logo">
            <h1 class="register-title">Create Account</h1>
            
            <form action="includes/functions" method="POST">
                
                <div class="form-row">
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" class="register-input" placeholder="First Name" name="first_name" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" class="register-input" placeholder="Last Name" name="last_name" required>
                    </div>
                     <div class="input-group">
                        <i class="fas fa-file"></i>
                        <input type="file" class="register-input" placeholder="Profile Picture" name="profile_pic" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <i class="fas fa-venus-mars"></i>
                        <input type="text" class="register-input" placeholder="Gender" name="gender" required>
                    </div>
                    <div class="input-group">
                        
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" class="register-input" placeholder="Date joined" name="date_joined" required>
                    </div>
                </div>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input name="email" type="email" class="register-input" placeholder="Email Address" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-home"></i>
                    <input name="address" type="text" class="register-input" placeholder="Physical Address" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <input name="phone" type="tel" class="register-input" placeholder="Phone Number" required>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input name="password" type="password" class="register-input" placeholder="Create Password" required>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input name="confirm_password" type="password" class="register-input" placeholder="Confirm Password" required>
                </div>
               
                <button name="register" type="submit" class="register-btn">Register <i class="fas fa-user-plus"></i></button>
                
                <div class="login-link">
                    Already have an account? <a href="login">Login here</a>
                </div>
            </form>
        </div>
    </section>

    <script src="public/js/scripts.js"></script>
</body>
</html>