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
        const selectedRadio = document.querySelector('#image-list input[name="selected_image"]:checked');

        if (!selectedRadio) {
            alert('Please select an image.');
            return; 
        }

        //Recover the selected image ID
        const selectedImageId = selectedRadio.value;

        // Updates the hidden field `Image_id` in the form
        const hiddenInputs = document.querySelectorAll('input[name="image_id"]');
        hiddenInputs.forEach(hiddenInput => {
            hiddenInput.value = selectedImageId;
        });

        // Updates the selected image preview
        const selectedImagePreviews = document.querySelectorAll('#selectedImagePreview');
        const selectedImageCard = selectedRadio.closest('.card');
        if (selectedImageCard && selectedImagePreviews) {
            const imgElement = selectedImageCard.querySelector('img');
        
            selectedImagePreviews.forEach(selectedImagePreview => {
                if (imgElement) {
                    selectedImagePreview.src = imgElement.src;
                    selectedImagePreview.style.display = 'block';
                }
            });
        }

        // Close modal
        const imageDateSelectorModal = bootstrap.Modal.getInstance(document.getElementById('imageDateSelectorModal'));
        if (imageDateSelectorModal) {
            imageDateSelectorModal.hide();
        }
    });

    // Attach an event listened to the document (or a static parent)
    document.addEventListener('click', function (event) {
        // Check if the clicked item is a button with the Image_Selector_Modal class
        if (event.target && event.target.matches('button.image_selector_modal')) {

            const button = event.target; // The clicked button
            const modalId = button.getAttribute('data-bs-target');
            const loadUrl = button.getAttribute('data-load-url');
            const context = button.getAttribute('data-context');

            const modalTitle = document.getElementById('imageSelectorModalLabel');
            modalTitle.textContent = context === 'event' ? 'Select Image for Event' : 'Select Image for Date';

            const modalElement = document.querySelector(modalId);
            const imageContainer = modalElement.querySelector('.modal-body .row');

            // Displays a default loading message
            imageContainer.innerHTML = '<p class="text-center">Loading images...</p>';

            // Ajax request to load images from Loadurl
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
        }
    });

    // Event earphone on a parent container
    document.querySelector('#image-list').addEventListener('dblclick', function(e) {
        // Check if the target element is an image with the "Img-Thumbnail" class
        if (e.target && e.target.classList.contains('img-thumbnail')) {
            const fetchUrl = e.target.getAttribute('data-url'); // Récupération de l'URL

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

    // Identify the "Edit" buttons
    const editButtons = document.querySelectorAll('.btn-edit');

    // Add a click "event to each button
    editButtons.forEach(button => {
        button.addEventListener('click', function () {

            // URL recovered from the attribute of the item (dynamic option)
            const fetchUrl = this.getAttribute('data-url'); // L'URL de la route de l'image

            // Recover the modal item and insert a loading message
            const modalBody = document.querySelector('#dateEditModal .modal-body');
            modalBody.innerHTML = '<p>Loading in progress ...</p>';

            // Make an Ajax request to recover image data
            fetch(fetchUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', //Indicate that the request is Ajax
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Insert the form returned by the server in the modalbody`
                    modalBody.innerHTML = data.html;
                } else {
                    modalBody.innerHTML = '<p>Unable to recover data from the date.</p>';
                }
            })
            .catch(error => {
                console.error('Error when recovering data :', error);
                modalBody.innerHTML = '<p>An error occurred. Please try again.</p>';
            });

            // Display the modal
            const modal = new bootstrap.Modal(document.getElementById('dateEditModal'));
            modal.show();
        });
    });
});

function openChildModal() {

    // Optional: Force the modal in the foreground by adjusting its Zndex
    const modalElement = document.getElementById('imageDateSelectorModal');
    if (modalElement) {
        // Opens the children's modal with a static backdrop
        let childModal = new bootstrap.Modal(
            modalElement, {
                backdrop: "static"
            }
        );

        // Poster the modal
        childModal.show();

        // Find the highest Z-INDEX currently in the DOM
        const highestZIndex = Math.max(
            ...Array.from(document.querySelectorAll('body *')).map(el =>
                parseFloat(window.getComputedStyle(el).zIndex)
            ).filter(zIndex => !isNaN(zIndex))
        );

        // Apply a higher Z-INDEX to the modal
        modalElement.style.zIndex = highestZIndex + 1;
    }
}
