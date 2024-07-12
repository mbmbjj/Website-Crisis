<!DOCTYPE html>
<html>
<head>
        <title>My Page!</title>
        <script src="script.js"></script>
        <style>
            body{
                background: rgb(255, 255, 255);
                color: rgb(0, 0, 0);
                font-family: Helvetica Neue, Helvetica, sans-serif;
                margin:0;
                padding:0;
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
            section div{
                    
                    width: 100%;
                    padding: 0;
                    margin:0;
                    font-family:monospace;
            }
            section {
                width: 100%;
                height: 100%;
            }

            /*-----Features-----*/
            section.two {
                background:moccasin;
            }
            #highlight {
                margin-top: 0;
                background: rgb(207, 138, 64);
                padding: 3cqh;
                border-radius: 3%;
            }
            footer{
                background: rgb(158, 130, 84);
                color: black;
                padding: 8% 5%
            }
            h5 {
                color: white;
                font-family: Georgia, Times New Roman, Times, serif;
            }
            .headd {
                color: black;
            }
            /*----Topic-----*/
            .Topic {
                margin-left: 2%;
                font-size: 180%;
                padding: 3%;
            }
            /*-----image-----*/

            .imgcontainer {
                max-width: 50%;
                margin-right: 0;
                text-align: right;
            }
            .resize {
                width: 100%;
                height: auto;
                float: right;
            }

            /*-----camera-----*/
            .container {
                
                text-align: center;
            }
            .camera-row {
                display:flex;
                /*
                align-items: center;
                justify-content: center;*/
            }
            #side-text-left{
                margin-top:4%;
                margin-right: 0;
                font-size: 200%;
                padding:0;
            }
            #side-text-right{
                margin-top:4%;
                margin-left: 0;
                font-size: 200%;
                padding:0;
            }
            #camera-container {
                position: relative;
                width: 100%;
                max-width: 200%;                
                margin: auto;
            }

            #video {
                width: 100%;
                border-radius: 4%;
            }

            #capture-button {
                margin-top: 6%;
                margin-bottom: 6%;
                padding: 2% 4%;
                font-size: h2;
                cursor: pointer;
            }

            #captured-photo {
                margin-top: 4%;
                margin-bottom: 15%;
                max-width: 100%;
                display: none;
                border-radius:4%;
            }

            
        </style>
</head>

<body>
    <header>
        <nav class="top-bar">
        <div class="top-container">
            <ul class="myUL">
                <li><a href="try2.php"><h5>Home</h5></a></li>
                <li><a href="FoodAllergies.php"><h5>Camera</h5></a></li>
                <li><a href="contact.php"><h5>Account</h5></a></li>
            </ul>
        </div>
        </nav>
    </header>

    
    <section>
        
        <p><h1 class="Topic"><em>Food Scanning Programme<br><br><br> For Food Allergies</em></h1></p>
        <div class = "imgcontainer">
            <img src="https://i0.wp.com/www.croissantsandcaviar.com/wp-content/uploads/2021/04/croissantsandcaviar_food_photographer-10.jpg?fit=1080%2C1350&ssl=1" class="resize" />
        </div>
    </section>

    
    
    <section class="two">
        <div class="container">
            <h1 id="highlight"><em>FEATURES</em></h1>
            <div class="camera-row">
                <div id="side-text-left">1. Create an Account</div>
                <div id="camera-container">
                    <video id="video" autoplay></video>
                    <button id="capture-button">Capture Photo</button>
                    <canvas id="canvas" style="display:none;"></canvas>
                    <img id="captured-photo" alt="Captured Photo">
                    <a id="download-link" style="display:none;" download="captured_photo.png">Download Photo</a>
                </div>
                <div id="side-text-right">2. Tap the camera icon</div>
            </div>
        </div>
    </section>
    <footer>
        <h1>footer</h1>
    </footer>
</body>
</html>