<?php
session_start();

// Check if the user is logged in using cookies or session
if (!isset($_SESSION['username']) && !isset($_COOKIE['username'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: newlogin.php");
    exit(); // Make sure to exit after redirect
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

// Convert allergy names back to values for form pre-selection
$selectedAllergies = array_flip($allergyMapping);
$selectedAllergies = array_intersect_key($selectedAllergies, array_flip($allergyNames));
$selectedValues = array_keys($selectedAllergies);
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

        function updateProfile(event) {
            event.preventDefault(); // Prevent default form submission

            var formData = new FormData(document.getElementById('edit-form'));
            var allergies = [];
            document.querySelectorAll('input[name="allergy"]:checked').forEach(function(checkbox) {
                allergies.push(checkbox.value);
            });
            if (document.getElementById('other').checked) {
                var otherAnswer = document.getElementById('other-answer').value;
                allergies.push('other');
                formData.append('other-answer', otherAnswer);
            }
            formData.append('allergies', JSON.stringify(allergies));

            fetch('update_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert('Profile updated successfully!');
                window.location.href = 'account.php';
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
                <li><a href="file\NSC_26p23e0039_Report_Final01.pdf" download="Allergy_paper.pdf"><h5>Paper</h5></a></li>
            </ul>
        </div>
    </header>
    <section class="first-section" id="login">
        <h1>Edit Your Profile</h1>
        <form id="edit-form" onsubmit="updateProfile(event);">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" class="input-text" value="<?php echo htmlspecialchars($username); ?>" required><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="input-text" value="<?php echo htmlspecialchars($email); ?>" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="input-text" required><br><br>

            <label>Allergies (select all that apply):</label><br>
            <div id="checkbox-group">
                <?php
                foreach ($allergyMapping as $value => $name) {
                    echo '<div class="checkbox-item">';
                    echo '<input type="checkbox" id="' . $value . '" name="allergy" value="' . $value . '"' . (in_array($value, $selectedValues) ? ' checked' : '') . '>';
                    echo '<label for="' . $value . '">' . $name . '</label>';
                    echo '</div>';
                }
                ?>
                <div class="checkbox-item">
                    <input type="checkbox" id="other" name="allergy" value="other" onclick="showOtherAnswerInput()" <?php echo in_array('other', $selectedValues) ? 'checked' : ''; ?>>
                    <label for="other">Other</label>
                </div>
            </div>
            <div id="other-input" style="<?php echo in_array('other', $selectedValues) ? 'display:block;' : 'display:none;'; ?>">
                <label for="other-answer">Please specify:</label>
                <input type="text" id="other-answer" name="other-answer" class="input-text" value="<?php echo isset($_POST['other-answer']) ? htmlspecialchars($_POST['other-answer']) : ''; ?>"><br><br>
            </div>
            <button type="submit" id="update-button">Update Profile</button>
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
