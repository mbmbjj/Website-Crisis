<!DOCTYPE html>
<html>
<head>
        <title>My Page!</title>
        <script src="script.js"></script>
        
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
            /*section div {
                    
                    width: 100%;
                    padding: 0;
                    margin:0;
                    /*font-family:monospace;
            }*/
            section {
                width: 100%;
                height: 100%;
            }

            /*-----Features-----*/
            section.two {
                background:moccasin;
            }
            #highlight  {
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
                width: 100%;
                    padding: 0;
                    margin:0;
                
            }
            .camera-row {
                display:flex;
            
                align-items: center;
                justify-content: center;
            }
            #side-text-left{
                margin-top:4%;
                margin-right: 0;
                font-size: 150%;
                font-weight: 500;
                padding:50px ;
                
            }
            #side-text-right{
                margin-top:4%;
                margin-left: 0;
                font-size: 150%;
                font-weight: 500;
                padding:50px ;
            }
            #camera-container {
                position: relative;
                width: 100%;
                max-width: 500px;                
                margin: auto;
                text-align: center;
                margin-top: 50px;
            }

            #video {
                width: 100%;
                border-radius: 4%;
            }

            #capture-button {
                margin-top: 20px;
                margin-bottom: 50px;
                padding: 10px 20px;
                font-size: 16px;
                cursor: pointer;
                background: palevioletred;
                border: none;
                border-radius: 5px;
                color: white;
                box-shadow: 0 0 10px rgba(0, 0, 0, .1);
            }         

            #captured-photo {
                margin-top: 4%;
                margin-bottom: 15%;
                max-width: 100%;
                display: none;
                border-radius:4%;
            }
            
            /********Pop Up Container********/
            .popup-container{
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                /*background: seagreen;*/
                display: flex;
                justify-content: center;
                align-items: center;
                opacity: 0;
                text-align: center;
                pointer-events: none;
                transition: opacity 0.3s ease;
            }
            .popup-container.active{
                opacity: 1;
                pointer-events: all;
            }
            .popup-box{
                width: 50%;
                background: #f2f2f2;
                box-shadow: 0 0 10px rgba(0, 0, 0, .1);
                border-radius: 6px;
                padding: 30px;
                align-items: center;
            }
            .popup h1{
                color: #333;
                line-height: 1;
            }
            .popup p{
                color: #333;
                margin-bottom: 0;
                
            }
            #close-btn{
                
                cursor: pointer;
                width: 100px;
                height: 40px;
                background: palevioletred;
                border-radius: 6px;
                border: none;
                outline: none;
                box-shadow: 0 0 10px rgba(0, 0, 0, .1);
                
                font-size: 15px;
                color: #f2f2f2;
                font-weight: 500;
                margin-top: 15px;
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
                    <button id="capture-button" class="capture" onclick="openForm()">Capture Photo</button>
                    <canvas id="canvas" style="display:none;"></canvas>
                    <img id="captured-photo" alt="Captured Photo">
                    <a id="download-link" style="display:none;" download="captured_photo.png">Download Photo</a>
                </div>
                
                <div id="side-text-right">2. Tap the camera icon</div>
            </div>
        </div>
        
        <div class="popup-container" id="popuppcontainer">
                    <div class="popup-box">
                        <h1>Hello</h1>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                        <button id="close-btn" onclick="closeForm()">OK</button>
                    </div>
        </div>
        </section>
    <footer>
        <h1>footer</h1>
    </footer>


     <script>
        const captureButton = document.getElementById('capture-button');
        const popupContainer = document.getElementById('popuppcontainer');
        const closeButton = document.getElementById('close-btn');

        captureButton.addEventListener('click', () => {
            popupContainer.classList.add('active');
        });

        closeButton.addEventListener('click', () => {
            popupContainer.classList.remove('active');
        });
    </script>
</body>
</html>