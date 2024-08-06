<!DOCTYPE html>
<html lang="en">
<head>
    <title>Food Scanner</title>
    <link rel="stylesheet" href="styles2.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

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

        function eraseCookie(name) {
            document.cookie = name + '=; Max-Age=-99999999; path=/';
        }

        function checkIfLoggedIn() {
            const username = getCookie('username');
            if (username) {
                redirect();
            }
        }

        function checkLogin(event) {
            event.preventDefault(); // Prevent the default form submission
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const data = { username, password };

            fetch('https://tameszaza.pythonanywhere.com/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    alert(data.message);
                    setCookie('username', username, 1);
                    setCookie('email', data.email, 1);
                    setCookie('allergies', JSON.stringify(data.allergies), 1);
                    console.log("Email:", data.email);
                    console.log("Allergies:", data.allergies);
                    redirect();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function redirect() {
            window.location.href = "try2.php";
        }

        function logout() {
            fetch('https://tameszaza.pythonanywhere.com/api/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    eraseCookie('username');
                    eraseCookie('email');
                    eraseCookie('allergies');
                    window.location.href = 'login.html';
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        document.addEventListener('DOMContentLoaded', (event) => {
            const username = getCookie('username');
            const email = getCookie('email');
            const allergies = getCookie('allergies');

            if (username && email && allergies) {
                document.getElementById('username').textContent = `Username: ${username}`;
                document.getElementById('email').textContent = `Email: ${email}`;
                document.getElementById('allergies').textContent = `Allergies: ${allergies}`;
            }
        });

        window.onload = checkIfLoggedIn;
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
    <section class="first-section" id="login">
        <h1 class="Topic">Login</h1>
        <div class="input-form">
            <form onsubmit="checkLogin(event);">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="input-text" required><br><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="input-text" required><br><br>

                <button type="submit" id="login-button">Login</button>
            </form>
            <a href="newregist.php">
                <h5 class="register-link">New user? Register</h5>
            </a>
        </div>
    </section>
    <section id="thick-area"></section>
    
    <footer>
        <h2>contact us</h2>
        <p>Email: nscprojectstorage@gmail.com<br>Tel: 0929989812</p>
        <div id="disclaimer">
            <h2>Disclaimer</h2>
            <p>Agreement
                This software is a work developed by Adulvitch Kajittanon, Thanakrit Damduan and Phakthada Pitavaratorn
                from Kamnoetvidya Science Academy (KVIS) under the provision of Dr.Kanes Sumetpipat under Program for
                food allergy warning in food allergy which has been supported by the National Science and Technology
                Development Agency (NSTDA), in order to encourage pupils and students to learn and practice their skills
                in developing software. Therefore, the intellectual property of this software shall belong to the
                developer and the developer gives
                NSTDA a permission to distribute this software as an “as is” and non-modified software for a temporary
                and non-exclusive use without remuneration to anyone for his or her own purpose or academic purpose,
                which are not commercial purposes. In this connection, NSTDA shall not be responsible to the user for
                taking care, maintaining, training, or developing the efficiency of this software. Moreover, NSTDA shall
                not be liable for any error, software efficiency and damages in connection with or arising out of the
                use of the software.</p>
        </div>
    </footer>
</body>

</html>
