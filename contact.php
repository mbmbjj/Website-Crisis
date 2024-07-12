<?php 
   session_start();

   include("php/config.php");
   if(!isset($_SESSION['valid'])){
    header("Location: loginpage.php");
   }
?>
<!DOCTYPE html>
<html>
<head>
        <title>My Page!</title>
        <link rel="stylesheet" href="styles.css">
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