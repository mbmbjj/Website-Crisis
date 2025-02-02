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
                <li><a href="images\26p23e0039_รายงานฉบับสมบูรณ์ 4.pdf" download="Allergy_paper.pdf">
                        <h5>Paper</h5>
                    </a></li>
            </ul>
        </div>
    </header>
    <!--<section id='first-section'>
        <div class='column' id='title'>
            <h1 class="Topic"><em>Food Scanning Programme<br><br> For Food Allergies</em></h1>
            <p class='describtion'>"Quickly identify allergens in your diet."</p>
            <a href="#scan"><button id="jump-button" class="jump">Try now</button></a>

        </div>
        <div class="imgcontainer">
            <img src="images\croissantsandcaviar_food_photographer-10.png" class="resize" />
        </div>
    </section>-->
    <section class="two" id='scan'>
        <div class="container">
            <h1 id="highlight"><em>Food Allergens Warning Program</em></h1>
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

                                <div class="modal-body">
                                    <!-- Move Photo Container to the Left -->
                                    <div id="modal-photo-container">
                                        <div id="img-header">

                                        </div>
                                        <img id="modal-photo" alt="Labeled Photo">
                                        <div id="img-tail">

                                        </div>
                                    </div>

                                    <!-- Text Content on the Right -->
                                    <div class="modal-text">
                                        <p class='output' id='foutput'><strong>Detected Items</strong></p>
                                        <ul id="detectedItems"></ul>
                                        <p class='output' id='soutput'><strong>Allergy Group</strong></p>
                                        <ul id="detectedAller"></ul>
                                        <p class='output' id='toutput'><strong>Match Group</strong></p>
                                        <ul id="matchAller"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

    </section>
    <section class="banner" id="search-allergy">
        <h1 class="Topic">Search allergy</h1>
    </section>
    <section class="no-padding" id="search">

        <div class='column-white'>
            <form id="allergenSearchForm">
                <label for="foodNameInput">Enter Food Name:</label>
                <input type="text" id="foodNameInput" name="foodName" required>
                <button type="submit" id="searchButton">Search</button>
                </m>
        </div>
        <div class='column-white'>
            <div id="allergenResults">
                <h3>Food components</h3>
                <ul id="allergenList"></ul>
            </div>
        </div>

        <!-- New container for allergens -->
        <div class='column-white' style="margin-top:1em;" id="allergenResults2">
            <h3>Detected Allergens:</h3>
            <ul id="detectedAllergensList"></ul>
        </div>


    </section>
    <footer>
        <h2>contact us</h2>
        <p>Email: inewgenprojectstorage@gmail.com<br>Tel: 0929989812</p>
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
        //const tryNowButton = document.getElementById('jump-button');
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

            //Auto submit
            submitFile(fileInput.files[0]);
        });

        selectFileButton.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                console.log(`Original file size: ${file.size} bytes (${(file.size / 1024).toFixed(2)} KB)`);
                compressImage(file, (compressedFile) => {
                    console.log(`Compressed file size: ${compressedFile.size} bytes (${(compressedFile.size / 1024).toFixed(2)} KB)`);
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        uploadedPhoto.src = e.target.result;
                        uploadedPhoto.style.display = 'block';
                        capturedPhoto.style.display = 'none';
                    };
                    reader.readAsDataURL(compressedFile);

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(compressedFile);
                    event.target.files = dataTransfer.files;
                });
            }
        });

        function compressImage(file, callback) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const maxWidth = 1920;
                    const maxHeight = 1080;
                    const srcWidth = img.width;
                    const srcHeight = img.height;

                    const ratio = Math.min(1, Math.min(maxWidth / srcWidth, maxHeight / srcHeight));
                    const newWidth = Math.floor(srcWidth * ratio);
                    const newHeight = Math.floor(srcHeight * ratio);

                    const canvas = document.createElement('canvas');
                    canvas.width = newWidth;
                    canvas.height = newHeight;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, srcWidth, srcHeight, 0, 0, newWidth, newHeight);

                    canvas.toBlob(function(blob) {
                        const compressedFile = new File([blob], file.name, {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });
                        callback(compressedFile);
                    }, 'image/jpeg', 0.7);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        async function fetchDetections(imageId, retryCount = 30, delay = 1000) {
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
                            updateModalInfo(detections);

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


        function updateModalInfo(detections) {
            const imgHeader = document.getElementById('img-header');
            const imgTail = document.getElementById('img-tail');

            // Set header with food name (left) and calories (right)
            imgHeader.innerHTML = `
        <div style="display: flex; justify-content: space-between; font-weight: bold; padding: 10px; font-size: 18px;">
            <span id='detected-food-name'>${detections.food_name || 'Unknown Food'}</span>
            <span id='kcal'>${detections.Kcal ? detections.Kcal + ' kcal' : 'Unknown Calories'}</span>
        </div>
    `;

            // Set tail with food description
            imgTail.innerHTML = `
        <div id="desctiption" style="padding: 0.5em; font-size: 14px;color:rgba(0,0,0,0.4)">
            ${detections.food_description || 'No description available'}
        </div>
    `;
        }

        submitButton.addEventListener('click', async () => {
            submitFile(fileInput.files[0]);
        });

        async function submitFile(file) {
            if (!file) {
                alert('No file selected.');
                submitButton.style.display = 'block';
                submitText.style.display = 'none';
                loading.style.opacity = '0';
                return;
            }
            submitButton.style.display = 'none';
            submitText.style.display = 'block';
            loading.style.opacity = '1';
            const formData = new FormData();
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
                uploadedPhoto.src = `https://tameszaza.pythonanywhere.com${imageUrl}`; // Ensure the correct URL is used
                uploadedPhoto.style.display = 'block';

                try {
                    const detections = await fetchDetections(imageId);
                    foutput.textContent = "Detected Items";
                    soutput.textContent = "Allergy Group";
                    toutput.textContent = "Match Group";
                    console.log('Detections:', detections); // Debug output

                    const foodComponents = detections["food_component"];
                    const allergyGroup = detections["Allergy group"];

                    // Display detected food components
                    detectedItemsList.innerHTML = '';
                    if (!foodComponents || foodComponents.length === 0) {
                        const messageItem = document.createElement('li');
                        messageItem.textContent = "Cannot detect any ingredient";
                        detectedItemsList.appendChild(messageItem);
                    } else {
                        foodComponents.forEach(component => {
                            const name = component["component_name"];
                            console.log('Adding item:', name); // Debug output for each item
                            const listItem = document.createElement('li');
                            listItem.textContent = name;
                            detectedItemsList.appendChild(listItem);
                        });
                    }

                    // Display allergens from the allergy group
                    displayAllergensFromGroup(allergyGroup);
                    document.getElementById('resultModal').style.display = 'block';
                    const modalPhoto = document.getElementById('modal-photo'); // Ensure this ID is in your HTML
                    modalPhoto.src = `https://tameszaza.pythonanywhere.com${imageUrl}`;
                    modalPhoto.alt = "Labeled Photo"; // Add an alternative text for accessibility
                    document.getElementById('modal-photo-container').style.display = 'block';

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
        }

        // Close the modal
        document.getElementById('close').addEventListener('click', () => {
            document.getElementById('resultModal').style.display = 'none';
        });

        // Close the modal if the user clicks outside of it
        window.addEventListener('click', (event) => {
            if (event.target === document.getElementById('resultModal')) {
                document.getElementById('resultModal').style.display = 'none';
            }
        });

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

        //=====================================================

        function displayAllergensFromGroup(allergyGroup) {
            detectedAller.innerHTML = ''; // Clear previous allergens

            let hasAllergens = false;
            for (const [allergen, isPresent] of Object.entries(allergyGroup)) {
                if (isPresent) {
                    hasAllergens = true;
                    const listItem = document.createElement('li');
                    listItem.textContent = allergen.replace('_', ' '); // Replace underscores with spaces
                    detectedAller.appendChild(listItem);
                }
            }

            if (!hasAllergens) {
                const listItem = document.createElement('li');
                listItem.textContent = "No allergens detected";
                detectedAller.appendChild(listItem);
            }

            // Now compare with user's known allergies
            checkAllergies(allergyGroup);
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
                // Decode the URL-encoded cookie value before parsing
                const decodedAnswers = decodeURIComponent(storedAnswers);

                // Check if storedAnswers is already an array or a string
                const allergyStrings = Array.isArray(decodedAnswers) ? decodedAnswers : JSON.parse(decodedAnswers);
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



        function checkAllergies(allergyGroup) {
            const storedAllergies = getCookie('allergies');
            console.log("Stored Allergies (raw cookie value):", storedAllergies);

            const matchAller = document.getElementById('matchAller');
            matchAller.innerHTML = ''; // Clear previous content

            if (storedAllergies) {
                const decodedAllergies = decodeURIComponent(storedAllergies);
                const userAllergies = JSON.parse(decodedAllergies).map(Number); // Should be an array of allergen IDs

                // Mapping of allergen names to IDs (as per your system)
                const allergenNameToId = {
                    "Soy": 1,
                    "Cow_milk": 2,
                    "Wheat": 3,
                    "Egg": 4,
                    "Fish": 5,
                    "Seafood": 6,
                    "Peanut": 7,
                    "Shelled_nut": 8
                };

                let matchingAllergies = [];

                for (const [allergenName, isPresent] of Object.entries(allergyGroup)) {
                    if (isPresent) {
                        const allergenId = allergenNameToId[allergenName];
                        if (userAllergies.includes(allergenId)) {
                            matchingAllergies.push(allergenName);
                        }
                    }
                }

                // Display matching allergies
                if (matchingAllergies.length > 0) {
                    matchingAllergies.forEach(allergen => {
                        const listItem = document.createElement('li');
                        listItem.textContent = allergen.replace('_', ' ');
                        matchAller.appendChild(listItem);
                    });

                    const messageElement = document.createElement('p');

                    const linkElement = document.createElement('a');
                    linkElement.href = "allerinfo.php";
                    linkElement.textContent = "Cannot eat";
                    linkElement.style.color = "red";
                    linkElement.style.textDecoration = "none"; // Prevent underline
                    linkElement.style.cursor = "pointer"; // Keep default pointer behavior

                    // Style the message
                    messageElement.style.marginTop = "10px";
                    messageElement.style.fontSize = "40px";
                    messageElement.style.fontWeight = "bold";
                    messageElement.style.textAlign = "center";

                    messageElement.appendChild(linkElement);
                    matchAller.appendChild(messageElement);
                } else {
                    const listItem = document.createElement('li');
                    listItem.textContent = "No matching allergies";
                    matchAller.appendChild(listItem);

                    const messageElement = document.createElement('p');
                    messageElement.textContent = "Can eat";
                    // Style the message
                    messageElement.style.color = "green";
                    messageElement.style.marginTop = "10px";
                    messageElement.style.fontSize = "40px";
                    messageElement.style.fontWeight = "bold";
                    messageElement.style.textAlign = "center";
                    matchAller.appendChild(messageElement);
                }
            } else {
                console.log('No allergies stored in cookies.');
                const listItem = document.createElement('li');
                listItem.textContent = "No known allergies";
                matchAller.appendChild(listItem);
            }
        }


        document.getElementById('allergenSearchForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const foodName = document.getElementById('foodNameInput').value.trim();
            if (!foodName) {
                alert('Please enter a food name.');
                return;
            }

            try {
                const response = await fetch(
                    `https://tameszaza.pythonanywhere.com/search_allergens?food=${encodeURIComponent(foodName)}`
                );
                if (!response.ok) {
                    alert('Failed to fetch allergen information.');
                    return;
                }

                const data = await response.json();
                console.log("Gemini response:", data);

                // 1) Update the heading with the corrected name
                const allergenResults = document.getElementById('allergenResults');
                allergenResults.querySelector('h3').textContent =
                    `Food components for ${data["corrected name"] || foodName}`;

                // 2) Display the "food_component" items in <ul id="allergenList">
                const allergenList = document.getElementById('allergenList');
                allergenList.innerHTML = ''; // Clear old items

                if (data.food_component && Array.isArray(data.food_component)) {
                    data.food_component.forEach(comp => {
                        const li = document.createElement('li');
                        li.textContent = comp.component_name;
                        // Minimal inline styling (or let your old CSS handle it)
                        li.style.display = 'inline-block';
                        li.style.margin = '4px';
                        li.style.padding = '6px 10px';
                        li.style.backgroundColor = '#f7dec0';
                        allergenList.appendChild(li);
                    });
                } else {
                    const li = document.createElement('li');
                    li.textContent = "No food components detected.";
                    allergenList.appendChild(li);
                }

                // 3) Show only the true allergens in <ul id="detectedAllergensList">
                const detectedAllergensList = document.getElementById('detectedAllergensList');
                detectedAllergensList.innerHTML = '';

                // Get user’s saved allergies from cookie (if you use that approach)
                const storedAllergies = getCookie('allergies');
                // E.g. '["2","4"]'
                const userAllergies = storedAllergies ? JSON.parse(storedAllergies).map(Number) : [];

                // Map your known allergen names to numeric IDs 
                const allergenNameToId = {
                    "Soy": 1,
                    "Cow_milk": 2,
                    "Wheat": 3,
                    "Egg": 4,
                    "Fish": 5,
                    "Seafood": 6,
                    "Peanut": 7,
                    "Shelled_nut": 8
                };

                if (data.Allergy_group) {
                    // Loop each allergen; if it's true, list it
                    for (const [allergenName, isPresent] of Object.entries(data.Allergy_group)) {
                        if (isPresent) {
                            // Create a new <li> for this allergen
                            const li = document.createElement('li');
                            li.textContent = allergenName.replace('_', ' ');

                            // Check if the user has this allergen
                            const allergenId = allergenNameToId[allergenName];
                            if (userAllergies.includes(allergenId)) {
                                // The user is allergic => highlight in red
                                li.style.backgroundColor = 'rgb(247, 222, 192)';
                                li.style.color = 'red';
                            } else {
                                // Otherwise use a neutral style
                                li.style.backgroundColor = 'rgb(247, 222, 192)';
                                li.style.color = 'rgb(58, 44, 21)';

                            }

                            li.style.display = 'inline-block';
                            li.style.margin = '4px';
                            li.style.padding = '6px 10px';
                            detectedAllergensList.appendChild(li);
                        }
                    }
                }

            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while searching for allergens.');
            }
        });

        // Helper function to read cookies
        function getCookie(name) {
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i].trim();
                if (c.indexOf(nameEQ) === 0) {
                    return decodeURIComponent(c.substring(nameEQ.length, c.length));
                }
            }
            return null;
        }
    </script>
</body>

</html>