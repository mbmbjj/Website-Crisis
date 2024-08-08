

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard - Food Scanner</title>
    <link rel="stylesheet" href="styles2.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        function loadUserData() {
            const username = getCookie('username');
            const email = getCookie('email');
            const allergies = JSON.parse(getCookie('allergies'));

            if (username && email && allergies) {
                document.getElementById('username').value = username;
                document.getElementById('email').value = email;
                document.getElementById('allergies').value = allergies.join(', ');
            }
        }

        function saveUserData() {
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const allergies = document.getElementById('allergies').value.split(',').map(item => item.trim());

            document.cookie = `username=${username}; path=/`;
            document.cookie = `email=${email}; path=/`;
            document.cookie = `allergies=${JSON.stringify(allergies)}; path=/`;

            alert('Data saved successfully!');
        }

        function logout() {
            document.cookie = 'username=; Max-Age=-99999999;';
            document.cookie = 'email=; Max-Age=-99999999;';
            document.cookie = 'allergies=; Max-Age=-99999999;';
            window.location.href = 'newlogin.php';
        }

        window.onload = loadUserData;
    </script>
</head>

<body>
    <header>
        <div class="top-container">
            <ul class="myUL">
                <li><a href="try2.php"><h5>Home</h5></a></li>
                <li><a href="account.php"><h5>Account</h5></a></li>
                <li><a href="aboutus.php"><h5>About Us</h5></a></li>
                <li><a href="allerinfo.php"><h5>Learn more</h5></a></li>
                <li><a href="abt.php"><h5>Details</h5></a></li>
                <li><a href="file/NSC_26p23e0039_Report_Final01.pdf" download="Allergy_paper.pdf"><h5>Paper</h5></a></li>
            </ul>
        </div>
    </header>

    <section class="user-section">
        <h1>Welcome, <span id="user-name-display"></span></h1>
        <!--<form onsubmit="saveUserData(); return false;">
            <label for="username">Username:</label>
            <input type="text" id="username" class="input-text" readonly><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" class="input-text" required><br><br>

            <label for="allergies">Allergies:</label>
            <input type="text" id="allergies" class="input-text" required><br><br>

            <button type="submit">Save</button>
        </form>-->
        <p>Hiii</p>

        <button onclick="logout()">Logout</button>
    </section>

    <footer>
        <h2>Contact Us</h2>
        <p>Email: nscprojectstorage@gmail.com<br>Tel: 0929989812</p>
        <div id="disclaimer">
            <h2>Disclaimer</h2>
            <p>This software is a work developed by Adulvitch Kajittanon, Thanakrit Damduan, and Phakthada Pitavaratorn from Kamnoetvidya Science Academy (KVIS) under the provision of Dr. Kanes Sumetpipat under Program for food allergy warning in food allergy...</p>
        </div>
    </footer>
</body>

</html>
