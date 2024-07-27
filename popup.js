
        const captureButton = document.getElementById('capture-button');
        const popupContainer = document.getElementById('popuppcontainer');
        const closeButton = document.getElementById('close-btn');

        captureButton.addEventListener('click', () => {
            popupContainer.classList.add('active');
        });

        closeButton.addEventListener('click', () => {
            popupContainer.classList.remove('active');
        });
