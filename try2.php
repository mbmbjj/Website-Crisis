<!DOCTYPE html>
<html>

<head>
    <title>Food Scanner</title>
    <link rel="stylesheet" href="styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header>
        <div class="top-container">
            <ul class="myUL">
                <li><a href="try2.php">
                        <h5>Home</h5>
                    </a></li>
                <li><a href="account.php">
                        <h5>Account</h5>
                    </a></li>
                <li><a href="aboutus.php">
                        <h5>About Us</h5>
                    </a></li>
                <li><a href="allerinfo.php">
                        <h5>Learn more</h5>
                    </a></li>
                <li><a href="abt.php">
                        <h5>Details</h5>
                    </a></li>
                <li><a href="file\NSC_26p23e0039_Report_Final01.pdf" download="Allergy_paper.pdf">
                        <h5>Paper</h5>
                    </a></li>
            </ul>
        </div>
    </header>
    <section id='first-section'>
        <div class='column' id='title'>
            <h1 class="Topic"><em>Food Scanning Programme<br><br> For Food Allergies</em></h1>
            <p class='describtion'>"Quickly identify allergens in your diet."</p>
            <a href="#scan"><button id="jump-button" class="jump">Try now</button></a>

        </div>
        <div class="imgcontainer">
            <img src="images\croissantsandcaviar_food_photographer-10.png" class="resize" />
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
                        <button id="change-camera-button" class="capture"><i
                                class="fa-solid fa-camera-rotate"></i></button>
                        <button id="select-file-button" class="modal-button">Select File</button>
                    </div>
                    <canvas id="canvas" style="display:none;"></canvas>
                    <img id="captured-photo" alt="Captured Photo">
                    <img id="uploaded-photo" alt="No Image uploaded" style="display:block;">
                    <!-- Ensure this image is visible -->
                    <input type="file" id="fileInput" name="file" accept="image/*" style="display:none;">
                    <div id="submit-container"><button id="submit-button" class="submit">Submit</button></div>
                    <div id="loading">
                        <i class="fa fa-spinner"></i>
                    </div>
                    <p class='output' id='submit-text'>Processing</p>
                    <!--<p class='output' id='foutput'></p>
                    <ul id="detectedItems"></ul>
                    <p class='output' id='soutput'></p>
                    <ul id="detectedAller"></ul>
                    <p class='output' id='toutput'></p>
                    <ul id="matchAller"></ul>-->
                    <p class='describtion'>Becareful the scaning result can be wrong!!</p>

                </div>
                <div id="side-text-right">2. Tap the capture button <div>
                <div id="resultModal" class="modal">
                    <div class="modal-content">
                    <span id="close">&times;</span>
                        <!--<h2>Detected Food</h2>-->
                        <p class='output' id='foutput'></p>
                        <ul id="detectedItems"></ul>
                        <p class='output' id='soutput'></p>
                        <ul id="detectedAller"></ul>
                        <p class='output' id='toutput'></p>
                        <ul id="matchAller"></ul>                        
                    </div>
                    </div>
                </div>
            </div>
        </div>
        
    </section>
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
    <script>
    const loading = document.getElementById('loading');
    const video = document.getElementById('video');
    const captureButton = document.getElementById('capture-button');
    const canvas = document.getElementById('canvas');
    const capturedPhoto = document.getElementById('captured-photo');
    const selectFileButton = document.getElementById('select-file-button');
    const fileInput = document.getElementById('fileInput');
    const uploadedPhoto = document.getElementById('uploaded-photo');
    const submitButton = document.getElementById('submit-button');
    const detectedItemsList = document.getElementById('detectedItems');
    const detectedAller = document.getElementById('detectedAller');
    const matchAller = document.getElementById('matchAller');
    const foutput = document.getElementById("foutput");
    const soutput = document.getElementById("soutput");
    const toutput = document.getElementById("toutput");
    const changeCameraButton = document.getElementById('change-camera-button');
    const tryNowButton = document.getElementById('jump-button');
    const submitText = document.getElementById("submit-text");
    loading.style.opacity = '0';
    let currentStream;
    let currentDeviceIndex = 0;
    let videoDevices = [];
    

    async function fetchData() {
        try {
            const testresponse = await fetch('https://tameszaza.pythonanywhere.com/test');
            if (testresponse.ok) {
                const jsonResponse = await testresponse.json();
                console.log(jsonResponse);
            } else {
                console.error('HTTP error', testresponse.status);
            }
        } catch (error) {
            console.error('Fetch error:', error);
        }
    }

    // Call the async function
    fetchData();

    async function getVideoDevices() {
        const devices = await navigator.mediaDevices.enumerateDevices();
        videoDevices = devices.filter(device => device.kind === 'videoinput');
    }

    async function startStream(deviceId) {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }
        const stream = await navigator.mediaDevices.getUserMedia({
            video: {
                deviceId: deviceId ? {
                    exact: deviceId
                } : undefined
            }
        });
        currentStream = stream;
        video.srcObject = stream;
    }
    tryNowButton.addEventListener('click', () => {
        fetch('https://tameszaza.pythonanywhere.com/delete_all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    folder: 'processed'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    console.log(data.message);
                } else {
                    alert(data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        fetch('https://tameszaza.pythonanywhere.com/delete_all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    folder: 'uploads'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    console.log(data.message);
                } else {
                    alert(data.error);
                }
            })
            .catch(error => console.error('Error:', error));
    })

    captureButton.addEventListener('click', () => {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataUrl = canvas.toDataURL('image/png');
        uploadedPhoto.src = dataUrl;
        uploadedPhoto.style.display = 'block';

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

    async function fetchDetections(imageId, retryCount = 10, delay = 1000) {
    while (retryCount > 0) {
        try {
            const detectionsResponse = await fetch(
                `https://tameszaza.pythonanywhere.com/detections/${imageId}`
            );

            const rawText = await detectionsResponse.text(); // Get raw response as text
            console.log('Raw Response:', rawText); // Log the raw response
            console.log('Raw Response Length:', rawText.length);
            console.log('First character (ASCII):', rawText.charCodeAt(0));
            console.log('Last character (ASCII):', rawText.charCodeAt(rawText.length - 1));

            if (detectionsResponse.ok) {
                try {
                    const cleanedText = rawText.trim();
                    const detections = JSON.parse(cleanedText);
                    console.log('Parsed Detections:', detections);

                    // Update UI
                    submitButton.style.display = 'block';
                    submitText.style.display = 'none';
                    loading.style.opacity = '0';

                    return detections;
                } catch (e) {
                    console.error('Failed to parse JSON:', rawText);
                    throw e; // Re-throw the error to handle it in the outer catch block
                }
            } else if (detectionsResponse.status === 404) {
                console.log(`Detections not found yet, retrying in ${delay}ms...`);
                await new Promise(resolve => setTimeout(resolve, delay));
                retryCount--;
            } else {
                console.error(`Unexpected status code: ${detectionsResponse.status}`);
                throw new Error('Failed to fetch detections');
            }
        } catch (error) {
            console.error('Fetch error:', error);
            retryCount--;
            await new Promise(resolve => setTimeout(resolve, delay));
        }
    }
    throw new Error('Failed to fetch detections after multiple attempts');
}




    submitButton.addEventListener('click', async () => {
        submitButton.style.display = 'none';
        submitText.style.display = 'block';
        loading.style.opacity = '1';
        const formData = new FormData();
        const file = fileInput.files[0];
        if (!file) {
            alert('No file selected.');
            submitButton.style.display = 'block';
            submitText.style.display = 'none';
            loading.style.opacity = '0';
            return;
        }

        formData.append('file', file);

        const response = await fetch('https://tameszaza.pythonanywhere.com/upload', {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            const data = await response.json();
            const imageUrl = data.image_url;
            const imageId = data.id; // Capture the unique identifier

            console.log('Image URL:', imageUrl); // Debug output
            uploadedPhoto.src =
                `https://tameszaza.pythonanywhere.com${imageUrl}`; // Ensure the correct URL is used
            uploadedPhoto.style.display = 'block';

            try {
                const detections = await fetchDetections(imageId);
                foutput.textContent = "Detected Items";
                soutput.textContent = "Allergy Group";
                toutput.textContent = "Match Group";
                console.log('Detections:', detections); // Debug output
                detectedItemsList.innerHTML = '';
                if (detections.length === 0) {
                    const messageItem = document.createElement('li');
                    messageItem.textContent = "Cannot detect any ingredient";
                    detectedItemsList.appendChild(messageItem);
                } else {
                    detections.forEach(item => {
                        console.log('Adding item:', item); // Debug output for each item
                        if (item != 'other ingredients') {
                            const listItem = document.createElement('li');
                            listItem.textContent = item;
                            detectedItemsList.appendChild(listItem);
                        }
                    });
                }

                displayAllergens(detections);
                document.getElementById('resultModal').style.display = 'block';

            } catch (error) {
                console.error('Failed to fetch detections:', error);
                submitButton.style.display = 'block';
                submitText.style.display = 'none';
                loading.style.opacity = '0';
            }
        } else {
            alert('Upload failed');
            console.error('Upload failed');
            submitButton.style.display = 'block';
            submitText.style.display = 'none';
            loading.style.opacity = '0';
        }
    });
    // Close the modal
    document.getElementById('close').addEventListener('click', () => {
        document.getElementById('resultModal').style.display = 'none';
    });

    // Close the modal if the user clicks outside of it
    window.addEventListener('click', (event) => {
        if (event.target === document.getElementById('resultModal')) {
            document.getElementById('resultModal').style.display = 'none';
        }
    })

    changeCameraButton.addEventListener('click', () => {
        if (videoDevices.length > 1) {
            currentDeviceIndex = (currentDeviceIndex + 1) % videoDevices.length;
            startStream(videoDevices[currentDeviceIndex].deviceId);
        }
    });

    // Initialize video stream and devices
    getVideoDevices().then(() => {
        if (videoDevices.length > 0) {
            startStream(videoDevices[currentDeviceIndex].deviceId);
        } else {
            console.error('No video devices found');
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
        return new Blob([arrayBuffer], {
            type: mimeString
        });
    }

    function createFileList(blob) {
        const file = new File([blob], 'captured_photo.png', {
            type: blob.type
        });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        return dataTransfer.files;
    }

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
            multiValueMap.set(key.toLowerCase(), values);
        });

        return multiValueMap;
    }

    const multiValueMap = createMultiValueMap(data, allergenMap);

    const fruits = ['apple', 'avocado', 'banana', 'blueberry', 'cherry', 'date', 'fig', 'grape', 'kiwi', 'lemon',
        'mango', 'melon', 'olives', 'orange', 'peach', 'pear', 'pineapple', 'raspberry', 'strawberry', 'watermelon'
    ];
    const vegetables = ['French beans', 'asparagus', 'bamboo shoots', 'bean sprouts', 'broccoli', 'cabbage', 'carrot',
        'cauliflower', 'celery stick', 'cilantro mint', 'cucumber', 'eggplant', 'garlic', 'ginger', 'green beans',
        'kelp', 'lettuce', 'okra', 'onion', 'pepper', 'potato', 'pumpkin', 'rape', 'seaweed', 'snow peas',
        'spring onion', 'tomato', 'white radish'
    ];

    const specificNames = {};

    fruits.forEach(fruit => specificNames[fruit.toLowerCase()] = 'fruit');
    vegetables.forEach(vegetable => specificNames[vegetable.toLowerCase()] = 'vegetable');

    function replaceItems(detectedItems, specificNames) {
        return detectedItems.map(item => specificNames[item.toLowerCase()] || item);
    }

    function displayAllergens(detections) {
        const resultSet = new Set();
        const updatedDetectedItems = replaceItems(detections, specificNames);

        updatedDetectedItems.forEach(item => {
            const values = multiValueMap.get(item.toLowerCase());
            if (values) {
                values.forEach(value => resultSet.add(value));
            } else {
                console.log(`${item} not found in multiValueMap`);
            }
        });

        detectedAller.innerHTML = ''; // Clear previous allergens

        if (resultSet.has(1)) {
            listItem = document.createElement('li');
            listItem.textContent = "Soy"
            detectedAller.appendChild(listItem);
        }
        if (resultSet.has(2)) {
            listItem = document.createElement('li');
            listItem.textContent = "Cow milk"
            detectedAller.appendChild(listItem);
        }
        if (resultSet.has(3)) {
            listItem = document.createElement('li');
            listItem.textContent = "Wheat"
            detectedAller.appendChild(listItem);
        }
        if (resultSet.has(4)) {
            listItem = document.createElement('li');
            listItem.textContent = "Egg"
            detectedAller.appendChild(listItem);
        }
        if (resultSet.has(5)) {
            listItem = document.createElement('li');
            listItem.textContent = "Fish"
            detectedAller.appendChild(listItem);
        }
        if (resultSet.has(6)) {
            listItem = document.createElement('li');
            listItem.textContent = "Seafood"
            detectedAller.appendChild(listItem);
        }
        if (resultSet.has(7)) {
            listItem = document.createElement('li');
            listItem.textContent = "Peanut"
            detectedAller.appendChild(listItem);
        }
        if (resultSet.has(8)) {
            listItem = document.createElement('li');
            listItem.textContent = "Shelled nut"
            detectedAller.appendChild(listItem);
        }
        checkAllergies(resultSet);
    }

    //==================================================


    // const matchSet = new Set();

    function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function compareAllergies(resultSet) {
    const storedAnswers = getCookie('allergies');
    console.log("Stored Answers (raw cookie value):", storedAnswers);

    if (storedAnswers) {
        // Check if storedAnswers is already an array or a string
        const allergyStrings = Array.isArray(storedAnswers) ? storedAnswers : JSON.parse(storedAnswers);
        const allergyIntegers = allergyStrings.map(value => {
            const parsedValue = Number(value);
            console.log(`Parsed Value: ${value} -> ${parsedValue}`);
            return parsedValue;
        });

        console.log("Allergy Integers:", allergyIntegers);
        console.log("ResultSet:", resultSet);

        const matchingAllergies = allergyIntegers.filter(value => resultSet.has(value));
        console.log('Matching Allergies:', matchingAllergies);
        
        return matchingAllergies;
    } else {
        console.log('No allergies stored in cookies.');
        return [];
    }
}



function checkAllergies(resultSet) {
    const matchingAllergies = compareAllergies(resultSet);
    
    if (matchAller) { // Ensure matchAller is not null
        matchAller.innerHTML = ''; // Clear previous allergens

        if (matchingAllergies.includes(1)) {
            const listItem = document.createElement('li');
            listItem.textContent = "Soy";
            matchAller.appendChild(listItem);
        }
        if (matchingAllergies.includes(2)) {
            const listItem = document.createElement('li');
            listItem.textContent = "Cow milk";
            matchAller.appendChild(listItem);
        }
        if (matchingAllergies.includes(3)) {
            const listItem = document.createElement('li');
            listItem.textContent = "Wheat";
            matchAller.appendChild(listItem);
        }
        if (matchingAllergies.includes(4)) {
            const listItem = document.createElement('li');
            listItem.textContent = "Egg";
            matchAller.appendChild(listItem);
        }
        if (matchingAllergies.includes(5)) {
            const listItem = document.createElement('li');
            listItem.textContent = "Fish";
            matchAller.appendChild(listItem);
        }
        if (matchingAllergies.includes(6)) {
            const listItem = document.createElement('li');
            listItem.textContent = "Seafood";
            matchAller.appendChild(listItem);
        }
        if (matchingAllergies.includes(7)) {
            const listItem = document.createElement('li');
            listItem.textContent = "Peanut";
            matchAller.appendChild(listItem);
        }
        if (matchingAllergies.includes(8)) {
            const listItem = document.createElement('li');
            listItem.textContent = "Shelled nut";
            matchAller.appendChild(listItem);
        }
    } else {
        console.error('Element with ID matchAller not found.');
    }

    // Display the appropriate message based on matching allergies
    const message = matchingAllergies.length === 0 ? "Can eat  " : "Cannot eat  ";
    const messageElement = document.createElement('p');
    messageElement.textContent = message;

    // Style the message
    messageElement.style.color = "red";
    messageElement.style.marginTop = "10px"
    messageElement.style.fontSize = "40px"; // You can adjust the font size as needed
    messageElement.style.fontWeight = "bold";
    messageElement.style.textAlign = "center";

    matchAller.appendChild(messageElement);
}
    
    
    </script>
</body>

</html>