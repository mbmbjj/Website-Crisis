<?php 
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start output buffering
ob_start();

// Start the session
session_start();

// Include the config file
include("php/config.php");

// Check if session is valid
if(!isset($_SESSION['valid'])){
    header("Location: loginpage.php");
    exit();
}

// End output buffering and flush output
ob_end_flush();
?>
<!DOCTYPE html>
<html>
<head>
        <title>My Page!</title>
        <!--<link rel="stylesheet" href="styles.css">-->
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
            *{
                padding: 0;
                margin: 0;
                box-sizing: border-box;
                font-family: 'Poppins',sans-serif;
            }   
            body{
                background: rgb(255, 255, 255);
                color: rgb(0, 0, 0);
                /*font-family: Helvetica Neue, Helvetica, sans-serif;*/
                margin: 0;
                padding: 0;
            }
            header {
                background: rgb(207, 138, 64);
                padding: 2% 3%;
                height: 75px;
                
                align-items: center;
            }
            .top-container{
                text-align: center;
                width: 100%;
            }
            ul .myUL {
                padding: 0;
                margin:0;
                list-style: none;
            }
            li{
                display:inline-block;
                margin-right: 4%;
            }
            li:last-child {
                margin-right:0;
            }
            section{
                    display: flex;
            }
            section {
                width: 100%;
                height: 100%;
            }
            h5 {
                color: white;
            }
        </style>
        <link rel="stylesheet" href="loginstyle.css">
</head>

<body>
    <header >
        <nav>
            <div class="top-container">
            <ul class="myUL">
                <li><a href="try2.php"><h5>Home</h5></a></li>
                <li><a href="FoodAllergies.php"><h5>Camera</h5></a></li>
                <li><a href="contact.php"><h5>Account</h5></a></li>
            </ul>
            </div>
            <h1>Your Account</h1>
        </nav>
    </header>

    
        <!--<div>a</div>
        <div>b</div>
        <div>c</div>-
        <div class="links">
            <button><a href="loginpage.html">Log In Now</a></button>
        </div>-->
        <div >
            <!--<div class="logo">
                <p><a href="home.php">Logo</a></p>
            </div>-->

            <div class="right-links">
                <?php
                    $id = $_SESSION['id'];
                    $query = mysqli_query($con,"SELECT*FROM users WHERE Id=$id");
        
                    while($result = mysqli_fetch_assoc($query)){
                        $res_Uname = $result['Username'];
                        $res_Email = $result['Email'];
                        $res_Age = $result['Age'];
                        $res_id = $result['Id'];
                    }
                    
                    echo "<a href='edit.php?Id=$res_id'>Change Profile</a>";
                    
                ?>
                <a href="php/logout.php" ><button class="btn">Log Out</button></a>
                
            </div>
        </div>
        <main>
            <div class="main-box" >
                <div class="top">
                <!--<a href="php/logout.php" ><button class="btn">Log Out</button></a>-->
                    <div class="box">
                        <p>Hello <b><?php echo $res_Uname ?></b>, Welcome</p>
                    </div>
                    <div class="box">
                        <p>Your email is <b><?php echo $res_Email ?></b>.</p>
                    </div>
                    <div class="box">
                        <p>And you are <b><?php echo $res_Age ?></b>.</p>
                    </div>
                </div>
            </div>
        </main>
    

    

    </div>
    
    <footer>footer</footer>
</body>
</html>