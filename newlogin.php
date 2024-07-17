<!DOCTYPE html>
<html lang="en">
<head>
<title>Food Scanner</title>
<link rel="stylesheet" href="styles2.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<header>
        <div class="top-container">
            <ul class="myUL">
                <li><a href="try2.php">
                        <h5>Home</h5>
                    </a></li>
                <li><a href="newlogin.php">
                        <h5>Account</h5>
                    </a></li>
                <li><a href="aboutus.php">
                        <h5>About Us</h5>
                    </a></li>
                <li><a href="allerinfo.php">
                        <h5>Learn more</h5>
                    </a></li>
                <li><a href="abt.php">
                        <h5>Details</h5>
                    </a></li>
                <li><a href="file\NSC_26p23e0039_Report_Final01.pdf" download="Allergy_paper.pdf">
                        <h5>Paper</h5>
                    </a></li>
            </ul>
        </div>
    </header>
    <section class="first-section" id="login"><h1 class="Topic">Login</h1></section>
    
    <form onsubmit="checkLogin(); return false;">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
    <a href="newregist.php"><h5>New user?Register</h5></a>
</body>
</html>