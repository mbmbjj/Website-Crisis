<?php 
   session_start();

   include("php/config.php");
   if(!isset($_SESSION['valid'])){
    header("Location: loginpage.php");
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="loginstyle.css">
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
    <title>Change Profile</title>
   
</head>
<body>
    <header>
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
    <div class="nav">

        <div class="right-links">
            <a href="#"> Change Profile</a>
            <a href="php/logout.php"><button class="btn">Log Out</button></a>
        </div>
    </div>
    <div class="bigcontainer">
        <div class="box form-box">

            <?php 
               if(isset($_POST['submit'])){
                $username = $_POST['username'];
                $email = $_POST['email'];
                $age = $_POST['age'];

                $id = $_SESSION['id'];

                $edit_query = mysqli_query($con,"UPDATE users SET Username='$username', Email='$email', Age='$age' WHERE Id=$id ") or die("error occurred");

                if($edit_query){
                    echo "<div class='message'>
                    <p>Profile Updated!</p>
                </div> <br>";
              echo "<a href='contact.php'><button class='btn'>Go Back</button>";
       
                }
                }else{

                    $id = $_SESSION['id'];
                    $query = mysqli_query($con,"SELECT*FROM users WHERE Id=$id ");

                    while($result = mysqli_fetch_assoc($query)){
                        $res_Uname = $result['Username'];
                        $res_Email = $result['Email'];
                        $res_Age = $result['Age'];
                    }
            ?>
            <header class="logbox">Change Profile</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" value="<?php echo $res_Uname; ?>" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" value="<?php echo $res_Email; ?>" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="age">Age</label>
                    <input type="number" name="age" id="age" value="<?php echo $res_Age; ?>" autocomplete="off" required>
                </div>

                <div class="field">
                    
                    <input type="submit" class="btn" name="submit" value="Update" required>
                </div>
                
            </form>
        </div>
        <?php } ?>
      </div>
</body>
</html>