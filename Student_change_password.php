<?php
// Start session
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 40px; }
        .container { background: #fff; padding: 20px 30px; border-radius: 10px; max-width: 400px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
        input[type=text], input[type=password] {
            width: 100%; padding: 10px; margin: 8px 0; border-radius: 5px; border: 1px solid #ccc;
        }
        button {
            background-color: #4CAF50; color: white; padding: 10px 15px;
            border: none; border-radius: 5px; cursor: pointer;
        }
        button:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Change Password</h2>
        <form method="POST" action="change_password_action.php">
            <label>Mobile Number:</label>
            <input type="text" name="mobile" required />

            <label>New Password:</label>
            <input type="password" name="new_password" required />

            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required />

            <button type="submit">Update Password</button>
        </form>
    </div>
</body>
</html>
