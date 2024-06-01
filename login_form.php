<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
    <div class="container">
        <h2>Login Form</h2>
        <form action="login.php" method="post">
            <div class="input-group">
                <label for="email">Username / Email</label>
                <input type="text" id="username_or_email" name="username_or_email" required />
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required />
            </div>

            <!-- Login button -->
            <button type="submit" class="btn">Login</button>
        </form>
        <!-- Register button -->
        <form action="register_form.php" method="post">
            <button type="submit" class="btn">Register</button>
        </form>
    </div>
</body>
</html>

