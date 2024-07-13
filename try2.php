<!DOCTYPE html>
<html>
<head>
    <title>My Page!</title>
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <header>
        <nav class="top-bar">
            <div class="top-container">
                <ul class="myUL">
                    <li><a href="try2.php"><h5>Home</h5></a></li>
                    <li><a href="FoodAllergies.php"><h5>Camera</h5></a></li>
                    <li><a href="contact.php"><h5>Account</h5></a></li>
                    <li><a href="aboutus.php"><h5>About Us</h5></a></li>
                </ul>
            </div>
        </nav>
    </header>
    <section>
        <h1 class="Topic"><em>Food Scanning Programme<br><br><br> For Food Allergies</em></h1>
        <div class="imgcontainer">
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
                    <div class="button-row">
                        <button id="capture-button" class="capture">Capture Photo</button>
                        <button id="select-file-button" class="modal-button">Select File</button>
                    </div>
                    <canvas id="canvas" style="display:none;"></canvas>
                    <img id="captured-photo" alt="Captured Photo">
                    <img id="uploaded-photo" alt="Uploaded Photo" style="display:none;">
                    <input type="file" id="fileInput" name="file" accept="image/*" style="display:none;">
                    <button id="submit-button" class="submit">Submit</button>
                    <ul id="detectedItems"></ul>
                </div>
                <div id="side-text-right">2. Tap the camera icon</div>
            </div>
        </div>
        <div class="popup-container" id="popup-container">
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
        const video = document.getElementById('video');
        const captureButton = document.getElementById('capture-button');
        const canvas = document.getElementById('canvas');
        const capturedPhoto = document.getElementById('captured-photo');
        const selectFileButton = document.getElementById('select-file-button');
        const fileInput = document.getElementById('fileInput');
        const uploadedPhoto = document.getElementById('uploaded-photo');
        const submitButton = document.getElementById('submit-button');
        const detectedItems = document.getElementById('detectedItems');

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(error => {
                console.error('Error accessing the camera', error);
            });

        captureButton.addEventListener('click', () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            const dataUrl = canvas.toDataURL('image/png');
            capturedPhoto.src = dataUrl;
            capturedPhoto.style.display = 'block';
            uploadedPhoto.style.display = 'none';

            const blob = dataURLToBlob(dataUrl);
            const fileList = createFileList(blob);
            fileInput.files = fileList;
        });

        selectFileButton.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    uploadedPhoto.src = e.target.result;
                    uploadedPhoto.style.display = 'block';
                    capturedPhoto.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });

        submitButton.addEventListener('click', async () => {
            const formData = new FormData();
            const file = fileInput.files[0];
            if (!file) {
                alert('No file selected.');
                return;
            }

            formData.append('file', file);

            const response = await fetch('http://localhost:5000/upload', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                const data = await response.json();
                const imageUrl = data.image_url;

                uploadedPhoto.src = imageUrl;
                uploadedPhoto.style.display = 'block';

                const detectionsResponse = await fetch('http://localhost:5000/detections');
                if (detectionsResponse.ok) {
                    const detections = await detectionsResponse.json();
                    console.log('Detections:', detections);  // Debug output
                    detectedItems.innerHTML = '';
                    detections.forEach(item => {
                        const listItem = document.createElement('li');
                        listItem.textContent = item;
                        detectedItems.appendChild(listItem);
                    });
                } else {
                    console.error('Failed to fetch detections');
                }
            } else {
                alert('Upload failed');
                console.error('Upload failed');
            }
        });

        function dataURLToBlob(dataURL) {
            const parts = dataURL.split(',');
            const byteString = atob(parts[1]);
            const mimeString = parts[0].split(':')[1].split(';')[0];
            const arrayBuffer = new ArrayBuffer(byteString.length);
            const intArray = new Uint8Array(arrayBuffer);
            for (let i = 0; i < byteString.length; i++) {
                intArray[i] = byteString.charCodeAt(i);
            }
            return new Blob([arrayBuffer], { type: mimeString });
        }

        function createFileList(blob) {
            const file = new File([blob], 'captured_photo.png', { type: blob.type });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            return dataTransfer.files;
        }
    </script>
</body>
</html>
