document.addEventListener('DOMContentLoaded', function () {
    // Button to save the selected images
    document.querySelector('#save-selected-images').addEventListener('click', function () {
        // Recover all the selected images
        const selectedImages = Array.from(
            document.querySelectorAll(
                '#image-list input[name="selected_images[]"]:checked'
            )
        ).map(input => input.value);

        // Dynamic URL recovery via the data-* attributes
        const saveUrl = document.querySelector('#save-selected-images').getAttribute('data-save-url');
        const csrfTokenName = document.querySelector('meta[name="csrf-token-name"]').getAttribute('content');
        const csrfHash = document.querySelector('meta[name="csrf-hash"]').getAttribute('content');

        // Ajax request to record images associated with the event
        fetch(saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                [csrfTokenName]: csrfHash,
            },
            body: JSON.stringify({
                image_ids: selectedImages
            }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update of the list of images associated in the DOM
                    const associatedImagesContainer = document.querySelector('#associated-images');
                    associatedImagesContainer.innerHTML = '';

                    data.images.forEach(image => {
                        associatedImagesContainer.innerHTML += `
                        <div class="col-md-1 mb-3">
                            <img src="${image.path}" alt="${image.name}" class="img-thumbnail">
                        </div>
                    `;
                    });

                    // Close modal
                    const modal = document.querySelector('#imageSelectorModal');
                    const bootstrapModal = bootstrap.Modal.getInstance(modal);
                    bootstrapModal.hide();

                }
            })
            .catch(error => {
                console.error(error);
                alert('An error occurred. Please try again.');
            });
    });

    // Click management on the button to open the modal
    document.querySelector('[data-bs-target="#imageSelectorModal"]').addEventListener('click', function () {
        const modalBody = document.querySelector('#image-list');
        modalBody.innerHTML = '<p>Loading images...</p>'; // Indicates that the images are being loaded

        // Dynamically load images via Ajax
        const loadUrl = document.querySelector('[data-bs-target="#imageSelectorModal"]').getAttribute('data-load-url');

        fetch(loadUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.html) {
                    modalBody.innerHTML = data.html; // Insert the HTML received from the server
                } else {
                    modalBody.innerHTML = '<p>Failed to load images. Please try again later.</p>';
                }
            })
            .catch(error => {
                console.error('Error when loading images :', error);
                modalBody.innerHTML = '<p>An error occurred while loading images.</p>';
            });
    });

    // Event earphone on a parent container
    document.querySelector('#image-list').addEventListener('dblclick', function(e) {
        // Check if the target element is an image with the "Img-Thumbnail" class
        if (e.target && e.target.classList.contains('img-thumbnail')) {
            const fetchUrl = e.target.getAttribute('data-url'); // Récupération de l'URL
            console.log(fetchUrl);

            // Modal recovery and loading message
            const modalBody = document.querySelector('#editModal .modal-body');
            modalBody.innerHTML = '<p>Chargement en cours...</p>';

            // Image data loading via Ajax
            fetch(fetchUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        modalBody.innerHTML = data.html;
                    } else {
                        modalBody.innerHTML = '<p>Impossible de récupérer les données.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des données :', error);
                    modalBody.innerHTML = '<p>Une erreur est survenue.</p>';
                });

            // Modal display
            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        }
    });
});
