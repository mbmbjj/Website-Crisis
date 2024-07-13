<?php 
   session_start();
?>
<!DOCTYPE html>
<html>
<head>
        <title>My Page!</title>
        <!--<link rel="stylesheet" href="styles.css">-->
        <link rel="stylesheet" href="loginstyle.css">
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
</head>

<body>
    <header>
        <nav class="top-bar">
            <div class="top-container" >
            <ul class="myUL">
                <li><a href="try2.php"><h5>Home</h5></a></li>
                <li><a href="FoodAllergies.php"><h5>Camera</h5></a></li>
                <li><a href="contact.php"><h5>Account</h5></a></li>
            </ul>
            </div>
            <h1>Your Account</h1>
        </nav>
    </header>
    <!--<section>
        <div>a</div>
        <div>b</div>
        <div>c</div>
    </section>-->
    <main>
        <div class="bigcontainer">
            <div class="box form-box">
            <?php 
             
             include("php/config.php");
             if(isset($_POST['submit'])){
               $email = mysqli_real_escape_string($con,$_POST['email']);
               $password = mysqli_real_escape_string($con,$_POST['password']);

               $result = mysqli_query($con,"SELECT * FROM users WHERE Email='$email' AND Password='$password' ") or die("Select Error");
               $row = mysqli_fetch_assoc($result);

               if(is_array($row) && !empty($row)){
                   $_SESSION['valid'] = $row['Email'];
                   $_SESSION['username'] = $row['Username'];
                   $_SESSION['age'] = $row['Age'];
                   $_SESSION['id'] = $row['Id'];
               }else{
                   echo "<div class='message'>
                     <p>Wrong Username or Password</p>
                      </div> <br>";
                  echo "<a href='loginpage.php'><button class='btn'>Go Back</button>";
        
               }
               if(isset($_SESSION['valid'])){
                   header("Location: contact.php");
               }
             }else{

           
           ?>
                <header class = "logbox">Log In</header>
                <form action="" method="post">
                    <div class = "field input">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" autocomplete="off" required>
                    </div>

                    <div class = "field input">
                        <label for="password">Password</label>
                        <input type="text" name="password" id="password" autocomplete="off" required>
                    </div>

                    <div class = "field">
                        <input type="submit" class="btn" name="submit" value="Login" required>
                    </div>

                    <div class="links">
                        Don't have account? <a href="register.php">Sign Up Now</a>
                    </div>

                    
                </form>
            </div>
            <?php } ?>
        </div>
    </main>
    <footer>footer</footer>
</body>
</html>