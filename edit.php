<?php
session_start();

// Handle form submission for updating profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the form submission
    $email = htmlspecialchars($_POST['email']);
    $allergies = isset($_POST['allergy']) ? $_POST['allergy'] : [];

    // Update session variables
    $_SESSION['email'] = $email;
    $_SESSION['allergies'] = json_encode($allergies); // Store allergies as JSON string in session

    // Set cookies to expire in 30 days (86400 seconds per day)
    setcookie('email', $email, time() + (86400 * 30), "/");
    setcookie('allergies', json_encode($allergies), time() + (86400 * 30), "/");

    // Optionally, redirect to avoid resubmission on page refresh
    header("Location: edit.php");
    exit();
}

// Retrieve user information from cookies if session is not set
$username = isset($_SESSION['username']) ? $_SESSION['username'] : (isset($_COOKIE['username']) ? $_COOKIE['username'] : '');
$email = isset($_SESSION['email']) ? $_SESSION['email'] : (isset($_COOKIE['email']) ? $_COOKIE['email'] : '');
$allergies = isset($_SESSION['allergies']) ? $_SESSION['allergies'] : (isset($_COOKIE['allergies']) ? $_COOKIE['allergies'] : '');

// Define an array to map allergy values to their names
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

// Convert allergy values to names for pre-filling the form
$selectedValues = [];
if ($allergies) {
    $allergyValues = json_decode($allergies, true); // Assuming allergies are stored as a JSON string in cookies
    $selectedValues = $allergyValues ?: []; // In case the user has no allergies
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="styles2.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        function showOtherAnswerInput() {
            var otherCheckbox = document.getElementById('other');
            var otherInput = document.getElementById('other-input');
            if (otherCheckbox.checked) {
                otherInput.style.display = 'block';
            } else {
                otherInput.style.display = 'none';
            }
        }

        function updateUserData() {
    const username = "<?php echo htmlspecialchars($username); ?>"; // Username obtained from PHP
    const email = document.getElementById('email').value; 
    const allergies = Array.from(document.querySelectorAll('input[name="allergy[]"]:checked')).map(el => el.value);

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
                // Force cookie update
                document.cookie = "email=" + email + ";path=/;expires=" + new Date(new Date().getTime() + 30*86400*1000).toUTCString();
                document.cookie = "allergies=" + encodeURIComponent(JSON.stringify(allergies)) + ";path=/;expires=" + new Date(new Date().getTime() + 30*86400*1000).toUTCString();

                alert(data.message);
                console.log("Updated User Info:", data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}


        function editPassword() {
            const username = "<?php echo htmlspecialchars($username); ?>"; // Username obtained from PHP
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
                <li><a href="try2.php"><h5>Home</h5></a></li>
                <li><a href="account.php"><h5>Account</h5></a></li>
                <li><a href="aboutus.php"><h5>About Us</h5></a></li>
                <li><a href="allerinfo.php"><h5>Learn more</h5></a></li>
                <li><a href="abt.php"><h5>Details</h5></a></li>
                <li><a href="images\26p23e0039_รายงานฉบับสมบูรณ์ 4.pdf" download="Allergy_paper.pdf"> <h5>Paper</h5> </a></li>
            </ul>
        </div>
    </header>
    <section class="first-section" id="login">
        <h1 class="Topic">Edit Your Profile</h1>
        <h2>Username: <?php echo htmlspecialchars($username); ?></h2> <!-- Display username at the top -->

        <div class="input-form">
        <form id="edit-form" method="POST" action="edit.php" onsubmit="updateUserData(); return false;">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" class="input-text" value="<?php echo htmlspecialchars($email); ?>" required><br><br>

    <label>Allergies (select all that apply):</label><br>
    <div id="checkbox-group">
        <?php
        foreach ($allergyMapping as $value => $name) {
            echo '<div class="checkbox-item">';
            echo '<input type="checkbox" id="' . $value . '" name="allergy[]" value="' . $value . '"' . (in_array($value, $selectedValues) ? ' checked' : '') . '>';
            echo '<label for="' . $value . '">' . $name . '</label>';
            echo '</div>';
        }
        ?>
    </div>
    <button type="submit" id="update-button">Update Profile</button>
</form>

        </div>
    </section>

    <section class="second-section" id="password-change">
        <h1 class="Topic">Change Your Password</h1>
        <div class="input-form">
        <form id="password-form" onsubmit="return false;">
            <label for="old_password">Old Password:</label>
            <input type="password" id="old_password" name="old_password" class="input-text" required><br><br>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" class="input-text" required><br><br>

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" class="input-text" required><br><br>

            <button type="submit" id="password-update-button" onclick="if(document.getElementById('new_password').value === document.getElementById('confirm_password').value) { editPassword(); } else { alert('Passwords do not match!'); }">Change Password</button>
        </form>
        </div>
    </section>
</body>
</html>
