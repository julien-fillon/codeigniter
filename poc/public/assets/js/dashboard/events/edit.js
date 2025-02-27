document.addEventListener('DOMContentLoaded', function () {
    // Button to save the selected images
    document.querySelector('#saveEventSelectedImages').addEventListener('click', function () {
        // Recover all the selected images
        const selectedImages = Array.from(
            document.querySelectorAll(
                '#image-list input[name="selected_images[]"]:checked'
            )
        ).map(input => input.value);

        // Dynamic URL recovery via the data-* attributes
        const saveUrl = document.querySelector('#saveEventSelectedImages').getAttribute('data-save-url');
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

            }
        })
        .catch(error => {
            console.error(error);
            alert('An error occurred. Please try again.');
        });
    });

    document.querySelector('#saveDateSelectedImage').addEventListener('click', function () {   
        // Récupère l'image sélectionnée (input radio checked)
        const selectedRadio = document.querySelector('#image-list input[name="selected_image"]:checked');

        if (!selectedRadio) {
            alert('Please select an image.');
            return; // Stop execution if no image is selected
        }

        // Récupère l'ID de l'image sélectionnée
        const selectedImageId = selectedRadio.value;

        // Met à jour le champ caché `image_id` dans le formulaire
        const hiddenInput = document.querySelector('input[name="image_id"]');
        if (hiddenInput) {
            hiddenInput.value = selectedImageId;
        }

        // Met à jour l'aperçu de l'image sélectionnée
        const selectedImagePreview = document.querySelector('#selectedImagePreview');
        const selectedImageCard = selectedRadio.closest('.card'); // Récupère la carte parente
        if (selectedImagePreview && selectedImageCard) {
            const imgElement = selectedImageCard.querySelector('img'); // Récupère l'élément img
            selectedImagePreview.src = imgElement.src; // Met à jour l'aperçu
            selectedImagePreview.style.display = 'block'; // Affiche l'aperçu
        }


        // Ferme le modal
        const imageDateSelectorModal = bootstrap.Modal.getInstance(document.getElementById('imageDateSelectorModal'));
        if (imageDateSelectorModal) {
            imageDateSelectorModal.hide();
        }
    });

    // Triggered event each time a button opening the modal is clicked
    document.querySelectorAll('button.image_selector_modal').forEach(button => {
        button.addEventListener('click', function () {
            const modalId = this.getAttribute('data-bs-target');
            const loadUrl = this.getAttribute('data-load-url'); 
            const context = this.getAttribute('data-context'); 

            const modalTitle = document.getElementById('imageSelectorModalLabel');
            modalTitle.textContent = context === 'event' ? 'Select Image for Event' : 'Select Image for Date';

            const modalElement = document.querySelector(modalId); 
            const imageContainer = modalElement.querySelector('.modal-body .row');

            // Displays a default loading message
            imageContainer.innerHTML = '<p class="text-center">Loading images...</p>';

            // Ajax request to load images from loadurl
            fetch(loadUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.html) {
                    imageContainer.innerHTML = data.html; // Insert the HTML received from the server
                } else {
                    imageContainer.innerHTML = '<p>Failed to load images. Please try again later.</p>';
                }
            })
            .catch(error => {
                console.error('Error when loading images :', error);
                imageContainer.innerHTML = '<p>An error occurred while loading images.</p>';
            });
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
                        modalBody.innerHTML = '<p>Unable to recover the data.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error when recovering data :', error);
                    modalBody.innerHTML = '<p>An error occurred.</p>';
                });

            // Modal display
            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        }
    });
});

function openChildModal() {
    // Affiche la modale enfant avec backdrop statique
    var childModal = new bootstrap.Modal(
        document.getElementById('imageDateSelectorModal'), {
            backdrop: "static"
        }
    );
    childModal.show();
}