<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles2.css">
    <link rel="stylesheet" href="loginstyle.css">
    <title>Register</title>
    <style>
    #otherLanguageInput {
        display: none;
    }

    .checkbox-label {
        font-size: 18px;
        /* Adjust the label size here */
        display: inline-block;
        /* Ensure label is inline-block */
        vertical-align: middle;
        /* Align label vertically */
        margin-left: 10px;
        /* Adjust spacing between checkbox and label */
    }

    .checkbox-input {
        width: 20px;
        /* Adjust the checkbox size here */
        height: 20px;
        /* Adjust the checkbox size here */
        vertical-align: middle;
        /* Align checkbox vertically */
        margin-right: 10px;
    }

    .checkbox-wrapper {
        margin-bottom: 10px;
        /* Adjust margin between checkbox rows */
    }

    .checkbox-label-text {
        display: inline-block;
        vertical-align: middle;
    }
    </style>
    <script>
    function toggleOtherLanguageInput() {
        var otherCheckbox = document.getElementById("otherLanguage");
        var otherInput = document.getElementById("otherLanguageInput");
        otherInput.style.display = otherCheckbox.checked ? "block" : "none";
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
                <li><a href="contact.php">
                        <h5>Account</h5>
                    </a></li>
                <li><a href="aboutus.php">
                        <h5>About Us</h5>
                    </a></li>
                <li><a href="allerinfo.php">
                        <h5>Learn more</h5>
                    </a></li>
                <li><a href="file\NSC_26p23e0039_Report rev.1.pdf" download="Allergy_paper.pdf">
                        <h5>Paper</h5>
                    </a></li>
            </ul>
        </div>
    </header>

    <div class="container">
        <div class="box form-box">
            <?php
            include("php/config.php");
            if(isset($_POST['submit'])){
                $username=$_POST['username'];
                $password=$_POST['password'];
                $email=$_POST['email'];
                $age=$_POST['age'];

                $allergies = [];

// Define a mapping of allergies to integer values
$allergyMap = [
    'Egg' => 1,
    'Fish' => 2,
    'Peanut' => 3,
    'Milk' => 4,
    'Seafood' => 5,
    'Soy' => 6,
    'Wheat' => 7,
    'Other' => 8 // Adjust as needed or use a dynamic value assignment
];

if(isset($_POST['Egg'])) $allergies[] = $allergyMap['Egg'];
if(isset($_POST['Fish'])) $allergies[] = $allergyMap['Fish'];
if(isset($_POST['Peanut'])) $allergies[] = $allergyMap['Peanut'];
if(isset($_POST['Milk'])) $allergies[] = $allergyMap['Milk'];
if(isset($_POST['Seafood'])) $allergies[] = $allergyMap['Seafood'];
if(isset($_POST['Soy'])) $allergies[] = $allergyMap['Soy'];
if(isset($_POST['Wheat'])) $allergies[] = $allergyMap['Wheat'];
                
                if(isset($_POST['Other']) && !empty($_POST['otherLanguageInput'])) {
                    $allergies[] = $_POST['otherLanguageInput'];
                }
                $allergiesString = implode(", ", $allergies);
                //$allergies = isset($_POST['allergies']) ? implode(", ", $_POST['allergies']) : "";

                /*$allergies = isset($_POST['allergies']) ? $_POST['allergies'] : [];
                if (in_array("Other", $allergies)) {
                    if (isset($_POST['otherLanguageInput'])) {
                        $otherAllergy = $_POST['otherLanguageInput'];
                        if (!empty($otherAllergy)) {
                            $allergies = array_diff($allergies, ["Other"]);
                            $allergies[] = $otherAllergy;
                        }
                    }
                }
                $allergiesString = implode(", ", $allergies);*/
                //verifying the unique email

                $verify_query = mysqli_query($con,"SELECT Email FROM users WHERE Email='$email'");

                if(mysqli_num_rows($verify_query) !=0 ){
                    echo "<div class='message'>
                            <p>This email is used, Try another One Please!</p>
                        </div> <br>";
                    echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
                }
                else{

                    mysqli_query($con,"INSERT INTO realUser(Username,Email,Age,Password, Allergies) VALUES('$username','$email','$age','$password', '$allergiesString')") or die("Error Occured");

                    echo "<div class='message'>
                            <p>Registration successfully!</p>
                        </div> <br>";
                    echo "<a href='loginpage.php'><button class='btn'>Login Now</button>";
                
                }
         }else{
        ?>
            <header>Sign Up</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="age">Age</label>
                    <input type="number" name="age" id="age" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>


                <!-- Multiple Choice Selection -->
                <div class="field input">
                    <label for="languages">Food allergies:</label><br>
                    <div class="checkbox-wrapper">
                        <input type="checkbox" class="checkbox-input" name="allergies[]" id="eggCheckbox" value="Egg">
                        <label class="checkbox-label" for="eggCheckbox"><span
                                class="checkbox-label-text">Egg</span></label>
                    </div>
                    <div class="checkbox-wrapper">
                        <input type="checkbox" class="checkbox-input" name="allergies[]" id="fishCheckbox" value="Fish">
                        <label class="checkbox-label" for="fishCheckbox"><span
                                class="checkbox-label-text">Fish</span></label>
                    </div>
                    <div class="checkbox-wrapper">
                        <input type="checkbox" class="checkbox-input" name="allergies[]" id="peanutCheckbox"
                            value="Peanut">
                        <label class="checkbox-label" for="peanutCheckbox"><span
                                class="checkbox-label-text">Peanut</span></label>
                    </div>
                    <div class="checkbox-wrapper">
                        <input type="checkbox" class="checkbox-input" name="allergies[]" id="milkCheckbox" value="Milk">
                        <label class="checkbox-label" for="milkCheckbox"><span
                                class="checkbox-label-text">Milk</span></label>
                    </div>
                    <div class="checkbox-wrapper">
                        <input type="checkbox" class="checkbox-input" name="allergies[]" id="seafoodCheckbox"
                            value="Seafood">
                        <label class="checkbox-label" for="seafoodCheckbox"><span
                                class="checkbox-label-text">Seafood</span></label>
                    </div>
                    <div class="checkbox-wrapper">
                        <input type="checkbox" class="checkbox-input" name="allergies[]" id="soyCheckbox" value="Soy">
                        <label class="checkbox-label" for="soyCheckbox"><span
                                class="checkbox-label-text">Soy</span></label>
                    </div>
                    <div class="checkbox-wrapper">
                        <input type="checkbox" class="checkbox-input" name="allergies[]" id="wheatCheckbox"
                            value="Wheat">
                        <label class="checkbox-label" for="wheatCheckbox"><span
                                class="checkbox-label-text">Wheat</span></label>
                    </div>
                    <div class="checkbox-wrapper">
                        <input type="checkbox" class="checkbox-input" id="otherLanguage" name="allergies[]"
                            value="Other" onclick="toggleOtherLanguageInput()">
                        <label class="checkbox-label" for="otherLanguage"><span
                                class="checkbox-label-text">Other</span></label>
                        <input type="text" id="otherLanguageInput" name="Other" placeholder="Please specify">
                    </div>
                </div>


                <div class="field">

                    <input type="submit" class="btn" name="submit" value="Register" required>
                </div>
                <div class="links">
                    Already a member? <a href="loginpage.php">Sign In</a>
                </div>
            </form>
        </div>
        <?php } ?>
    </div>
</body>

</html>