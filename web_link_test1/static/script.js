document.addEventListener('DOMContentLoaded', () => {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureButton = document.getElementById('capture-button');
    const capturedPhoto = document.getElementById('captured-photo');

    // Request access to the camera
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                video.srcObject = stream;
                video.play();
                console.log("Camera stream started successfully");
            })
            .catch(function(error) {
                console.error("Something went wrong: ", error);
            });
    } else {
        alert('getUserMedia is not supported in this browser.');
    }

    // Capture a photo when the button is clicked
    captureButton.addEventListener('click', async (event) => {
        event.preventDefault();

        // Draw the current video frame onto the canvas
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Convert the canvas to a data URL and display it
        const dataUrl = canvas.toDataURL('image/png');
        capturedPhoto.src = dataUrl;
        capturedPhoto.style.display = 'block';

        // Convert the data URL to a Blob
        const blob = dataURLToBlob(dataUrl);

        // Send the Blob to the API
        const formData = new FormData();
        formData.append('file', blob, 'captured_photo.png');

        try {
            const response = await fetch('/upload', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                console.log("response OK");
                const detections = await response.json();
                const detectedItems = document.getElementById('detectedItems');
                detectedItems.innerHTML = '';
                detections.forEach(item => {
                    const listItem = document.createElement('li');
                    listItem.textContent = item;
                    detectedItems.appendChild(listItem);
                });
            } else {
                alert('Upload failed');
            }
        } catch (error) {
            console.error('Error uploading the image:', error);
            alert('An error occurred while uploading the image.');
        }
    });

    // Helper function to convert a data URL to a Blob
    function dataURLToBlob(dataURL) {
        const byteString = atob(dataURL.split(',')[1]);
        const mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0];
        const ab = new ArrayBuffer(byteString.length);
        const ia = new Uint8Array(ab);
        for (let i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }
        return new Blob([ab], { type: mimeString });
    }
});
