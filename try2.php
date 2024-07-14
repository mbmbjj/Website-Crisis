<!DOCTYPE html>
<html>
<head>
    <title>My Page!</title>
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
<header>
        <div class="top-container">
            <ul class="myUL">
                <li><a href="try2.php"><h5>Home</h5></a></li>
                <li><a href="contact.php"><h5>Account</h5></a></li>
                <li><a href="aboutus.php"><h5>About Us</h5></a></li>
                <li><a href="allerinfo.php"><h5>Learn more</h5></a></li>
            </ul>
        </div>
</header>
    <section>
        <div class='column' id='title'>
            <h1 class="Topic"><em>Food Scanning Programme<br><br> For Food Allergies</em></h1>
            <p class='describtion'>"Add Description here Lorem ipsum donor bra/\/\"</p>
            <a href="#scan"><button id="jump-button" class="jump">Try now</button></a>  
            
        </div>
        <div class="imgcontainer">
            <img src="https://i0.wp.com/www.croissantsandcaviar.com/wp-content/uploads/2021/04/croissantsandcaviar_food_photographer-10.jpg?fit=1080%2C1350&ssl=1" class="resize" />
        </div>
    </section>
    <section class="two" id='scan'>
        <div class="container">
            <h1 id="highlight"><em>FEATURES</em></h1>
            <div class="camera-row">
                <div id="side-text-left">1. Create an Account (optional)</div>
                <div id="camera-container">
                    <video id="video" autoplay></video>
                    <div class="button-row">
                        <button id="capture-button" class="capture">Capture Photo</button>
                        <button id="select-file-button" class="modal-button">Select File</button>
                    </div>
                    <canvas id="canvas" style="display:none;"></canvas>
                    <img id="captured-photo" alt="Captured Photo">
                    <img id="uploaded-photo" alt="No Image uploaded" style="display:block;"> <!-- Ensure this image is visible -->
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
        <h2>contact us</h2>
        <p>Email: nscprojectstorage@gmail.com<br>Tel: 0123456789</p>
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
        const detectedAller = document.getElementById('detectedAller');

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

                console.log('Image URL:', imageUrl);  // Debug output
                uploadedPhoto.src = `http://localhost:5000${imageUrl}`;  // Ensure the correct URL is used
                uploadedPhoto.style.display = 'block';

                const detectionsResponse = await fetch('http://localhost:5000/detections');
                if (detectionsResponse.ok) {
                    const detections = await detectionsResponse.json();
                    console.log('Detections:', detections);  // Debug output
                    detectedItems.innerHTML = '';
                    if (detections.length === 0) {
                        const messageItem = document.createElement('li');
                        messageItem.textContent = "Cannot detect any ingredient";
                        detectedItems.appendChild(messageItem);
                    } else {
                        detections.forEach(item => {
                            console.log('Adding item:', item);  // Debug output for each item
                            const listItem = document.createElement('li');
                            listItem.textContent = item;
                            detectedItems.appendChild(listItem);
                        });
                    }
                    
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

// ====================================Hash map==================================================
        const allergenMap = {
    'No': 0,
    'Soy': 1,
    'cow\'s milk': 2,
    'wheat': 3,
    'egg': 4,
    'fish': 5,
    'sea food': 6,
    'peanut': 7,
    'shelled nut': 8
};

const data = [
    ['background', 'No'],
    ['candy', 'cow\'s milk'],
    ['egg tart', 'cow\'s milk+wheat+egg'],
    ['french fries', 'No'],
    ['chocolate', 'cow\'s milk'],
    ['biscuit', 'cow\'s milk+wheat'],
    ['popcorn', 'cow\'s milk'],
    ['pudding', 'cow\'s milk+wheat'],
    ['ice cream', 'cow\'s milk+egg'],
    ['cheese butter', 'cow\'s milk'],
    ['cake', 'cow\'s milk+wheat+egg'],
    ['wine', 'No'],
    ['milkshake', 'cow\'s milk+egg'],
    ['coffee', 'No'],
    ['juice', 'No'],
    ['milk', 'cow\'s milk+Soy'],
    ['tea', 'No'],
    ['almond', 'peanut'],
    ['red beans', 'shelled nut'],
    ['cashew', 'peanut'],
    ['dried cranberries', 'No'],
    ['soy', 'Soy'],
    ['walnut', 'peanut'],
    ['peanut', 'peanut'],
    ['egg', 'egg'],
    ['Fruit', 'No'],
    ['Meat', 'No'],
    ['sausage', 'cow\'s milk+wheat'],
    ['sauce', 'No'],
    ['crab', 'sea food'],
    ['fish', 'fish'],
    ['shellfish', 'sea food'],
    ['shrimp', 'sea food'],
    ['soup', 'cow\'s milk+wheat'],
    ['bread', 'wheat+cow\'s milk'],
    ['corn', 'No'],
    ['hamburg', 'cow\'s milk+wheat+egg'],
    ['pizza', 'wheat+cow\'s milk'],
    ['hanamaki baozi', 'wheat'],
    ['wonton dumplings', 'wheat+egg'],
    ['pasta', 'cow\'s milk+wheat+egg'],
    ['noodles', 'wheat+egg'],
    ['rice', 'No'],
    ['pie', 'cow\'s milk+wheat+egg'],
    ['tofu', 'Soy'],
    ['Vegetable', 'No'],
    ['Mushroom', 'No'],
    ['salad', 'No'],
    ['other ingredients', 'No']
];

function createMultiValueMap(data, allergenMap) {
    const multiValueMap = new Map();

    data.forEach(([key, valueString]) => {
        const values = valueString.split('+').map(value => allergenMap[value.trim().toLowerCase()]);
        multiValueMap.set(key, values);
    });

    return multiValueMap;
}

const multiValueMap = createMultiValueMap(data, allergenMap);

// // Example usage
// console.log(multiValueMap.get('egg tart')); // Output: [2, 3, 4]
// console.log(multiValueMap.get('pizza'));    // Output: [3, 2]
// console.log(multiValueMap.get('soy'));      // Output: [1]

// ==================================Replacing vegetable and fruit===========================
const fruits = ['apple', 'avocado', 'banana', 'blueberry', 'cherry', 'date', 'fig', 'grape', 'kiwi', 'lemon', 'mango', 'melon', 'olives', 'orange', 'peach', 'pear', 'pineapple', 'raspberry', 'strawberry', 'watermelon'];
const vegetables = ['French beans', 'asparagus', 'bamboo shoots', 'bean sprouts', 'broccoli', 'cabbage', 'carrot', 'cauliflower', 'celery stick', 'cilantro mint', 'cucumber', 'eggplant', 'garlic', 'ginger', 'green beans', 'kelp', 'lettuce', 'okra', 'onion', 'pepper', 'potato', 'pumpkin', 'rape', 'seaweed', 'snow peas', 'spring onion', 'tomato', 'white radish'];

const specificNames = {};

fruits.forEach(fruit => specificNames[fruit.toLowerCase()] = 'fruit');
vegetables.forEach(vegetable => specificNames[vegetable.toLowerCase()] = 'vegetable');

// function replaceItems(detectedItem, specificNames) {
//     return detectedItem.map(item => specificNames[item.toLowerCase()] || item);
// }

// const detectedItem = ['apple', 'banana', 'carrot', 'chocolate', 'milk', 'egg', 'blueberry', 'cucumber'];
// const updatedDetectedItem = replaceItems(detectedItem, specificNames);

// console.log(updatedDetectedItem); // Output: ['fruit', 'fruit', 'vegetable', 'chocolate', 'milk', 'egg', 'fruit', 'vegetable']

//==========================================Collect data into set=============================================================
const resultSet = new Set();

// Iterate over detectedItems and store results in resultSet
detectedItems.forEach(item => {
    const values = multiValueMap.get(item.toLowerCase());
    if (values) {
        values.forEach(value => resultSet.add(value));
    } else {
        console.log(`${item} not found in multiValueMap`);
    }
});

//================================================Display Aller======================================================
// Display Allergens based on resultSet
if (resultSet.has(1)) {
    detectedItems.appendChild(document.createTextNode("Soy"));
}
if (resultSet.has(2)) {
    detectedItems.appendChild(document.createTextNode("Cow milk"));
}
if (resultSet.has(3)) {
    detectedItems.appendChild(document.createTextNode("Wheat"));
}
if (resultSet.has(4)) {
    detectedItems.appendChild(document.createTextNode("Egg"));
}
if (resultSet.has(5)) {
    detectedItems.appendChild(document.createTextNode("Fish"));
}
if (resultSet.has(6)) {
    detectedItems.appendChild(document.createTextNode("Seafood"));
}
if (resultSet.has(7)) {
    detectedItems.appendChild(document.createTextNode("Peanut"));
}
if (resultSet.has(8)) {
    detectedItems.appendChild(document.createTextNode("Shelled nut"));
}



// ================================================Hashing Part End===========================================================


    </script>
</body>
</html>
