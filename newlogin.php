<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script>
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return null;
        }

        function checkLogin() {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            const storedUsername = getCookie('username');
            const storedPassword = getCookie('password');

            if (username === storedUsername && password === storedPassword) {
                window.location.href = 'try2.php';
            } else {
                alert('Wrong username or password.');
            }
        }
    </script>
</head>
<body>
    <h1>Login</h1>
    <form onsubmit="checkLogin(); return false;">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
    <button onclick="window.location.href='newregister.php'">New user?Register</button>
</body>
</html>