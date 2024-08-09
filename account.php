<?php
session_start();

// Check if the user is logged in using cookies
if (!isset($_SESSION['username']) && !isset($_COOKIE['username'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: newlogin.php");
    exit(); // Make sure to exit after redirect
}

// Retrieve user information from cookies if session is not set
$username = isset($_SESSION['username']) ? $_SESSION['username'] : (isset($_COOKIE['username']) ? $_COOKIE['username'] : '');
$email = isset($_SESSION['email']) ? $_SESSION['email'] : (isset($_COOKIE['email']) ? $_COOKIE['email'] : '');
$allergies = isset($_SESSION['allergies']) ? $_SESSION['allergies'] : (isset($_COOKIE['allergies']) ? $_COOKIE['allergies'] : '');

$allergyMapping = array(
    '1' => 'Egg',
    '2' => 'Fish',
    '3' => 'Peanut',
    '4' => 'Milk',
    '5' => 'Seafood',
    '6' => 'Soy',
    '7' => 'Wheat',
    'other' => 'Other'
);

// Convert allergy values to names
$allergyNames = [];
if ($allergies) {
    $allergyValues = json_decode($allergies, true); // Assuming allergies are stored as a JSON string in cookies
    foreach ($allergyValues as $value) {
        if (isset($allergyMapping[$value])) {
            $allergyNames[] = $allergyMapping[$value];
        }
    }
}
$allergyDisplay = implode(', ', $allergyNames);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Food Scanner - Account</title>
    <link rel="stylesheet" href="styles2.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script>
          function eraseCookie(name) {
            document.cookie = name + '=; Max-Age=-99999999; path=/';
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
                    window.location.href = 'try2.php';
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
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
                        window.location.href = 'try2.php';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function updateUserData() {
            const username = getCookie('username'); // Or however you're obtaining the username
            const email = document.getElementById('email').value; // Assume this is from a form input
            const allergies = ["3", "4", "6"]; // Example, could be dynamic

            const data = {
                username: username,
                email: email,
                allergies: allergies
            };

            fetch('https://tameszaza.pythonanywhere.com/api/update_user', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        alert(data.message);
                        console.log("Updated User Info:", data);
                        // Use the updated user info as needed
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
        function editPassword() {
            const username = getCookie('username'); // Or however you're obtaining the username
            const oldPassword = document.getElementById('old_password').value;
            const newPassword = document.getElementById('new_password').value;

            const data = {
                username: username,
                old_password: oldPassword,
                new_password: newPassword
            };

            fetch('https://tameszaza.pythonanywhere.com/api/edit_password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        alert(data.message);
                        console.log("Updated User Info:", data);
                        // You can use the returned data (username, email, allergies) here
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
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
                <li><a href="account.php">
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
        <h1 class="Topic">Your Account</h1>
        <div class="input-form">
        <h1>Welcome, <?php echo $username; ?><br></h1>
        <p>Email: <?php echo $email; ?><br></p>
        <p>Allergies: <?php echo htmlspecialchars($allergyDisplay); ?><br></p>
        <button onclick="window.location.href='edit.php';" id="logout-button">Edit</button>
        <button onclick="logout()" id="logout-button">Logout</button>
        
        </div>
    </section>
    <section id="thick-area"></section>
    <button onclick="logout()">Logout</button><button onclick="updateUserData()">edit cookie</button>
    <form id="edit-password-form" onsubmit="editPassword(); return false;">
        <label for="old_password">Old Password:</label>
        <input type="password" id="old_password" name="old_password" required><br><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <button type="submit">Change Password</button>
    </form>
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

