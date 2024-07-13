document.addEventListener('DOMContentLoaded', () => {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureButton = document.getElementById('capture-button');
    const capturedPhoto = document.getElementById('captured-photo');
    const popupContainer = document.getElementById('popup-box');
    const closeBtn = document.queryselector('.close-btn');

    // Request access to the camera
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                video.srcObject = stream;
                video.play();
            })
            .catch(function(error) {
                console.error("Something went wrong: ", error);
            });
    } else {
        alert('getUserMedia is not supported in this browser.');
    }

    closebtn.addEventListener('click', () => {
        popupContainer.classList.remove('active');
    });
    // Capture a photo when the button is clicked
    captureButton.addEventListener('click', () => {
        popupContainer.classList.add('active');

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
        sendPhotoToAPI(blob);
    });
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

    function sendPhotoToAPI(blob) {
        const formData = new FormData();
        formData.append('photo', blob, 'captured_photo.png');

        fetch('YOUR_API_ENDPOINT', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Photo successfully uploaded:', data);
        })
        .catch(error => {
            console.error('Error uploading photo:', error);
        });
    }
});