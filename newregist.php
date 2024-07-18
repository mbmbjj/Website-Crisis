<!DOCTYPE html>
<html lang="en">
<head>
<title>Food Scanner</title>
<link rel="stylesheet" href="styles2.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        function storeFormData() {
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const answers = Array.from(document.querySelectorAll('input[name="allergy"]:checked')).map(input => input.value);
            const otherAnswer = document.getElementById('other-answer').value;

            document.cookie = `username=${username}; path=/`;
            document.cookie = `email=${email}; path=/`;
            document.cookie = `password=${password}; path=/`;
            document.cookie = `answers=${answers.join(',')}; path=/`;
            document.cookie = `otherAnswer=${otherAnswer}; path=/`;

            // Redirect to newlogin.php after storing the data
            window.location.href = 'newlogin.php';
        }

        function showOtherAnswerInput() {
            const otherInput = document.getElementById('other-input');
            if (document.getElementById('other').checked) {
                otherInput.style.display = 'block';
            } else {
                otherInput.style.display = 'none';
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
    <section class="first-section" id="login">
    <h1 class="Topic">Register</h1>
    <div class="input-form">
    <form onsubmit="storeFormData(); return false;">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" class="input-text" required><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="input-text" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="input-text" required><br><br>

            <label>Allergies (select all that apply):</label><br>
            <div id="checkbox-group">
                <div class="checkbox-item">
                    <input type="checkbox" id="egg" name="allergy" value="1">
                    <label for="egg">Egg</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="fish" name="allergy" value="2">
                    <label for="fish">Fish</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="peanut" name="allergy" value="3">
                    <label for="peanut">Peanut</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="milk" name="allergy" value="4">
                    <label for="milk">Milk</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="seafood" name="allergy" value="5">
                    <label for="seafood">Seafood</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="soy" name="allergy" value="6">
                    <label for="soy">Soy</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="wheat" name="allergy" value="7">
                    <label for="wheat">Wheat</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="other" name="allergy" value="other" onclick="showOtherAnswerInput()">
                    <label for="other">Other</label>
                </div>
            </div>
            <div id="other-input" style="display:none;">
                <label for="other-answer">Please specify:</label>
                <input type="text" id="other-answer" name="other-answer" class="input-text"><br><br>
            </div>
            <button type="submit" id="register-button">Register</button>
        </form>
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