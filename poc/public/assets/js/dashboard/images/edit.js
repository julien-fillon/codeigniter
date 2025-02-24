document.addEventListener('DOMContentLoaded', function () {
    // Identify the "Edit" buttons
    const editButtons = document.querySelectorAll('.btn-edit');

    // Add a click "event to each button
    editButtons.forEach(button => {
        button.addEventListener('click', function () {

            console.log(this);
            // URL recovered from the attribute of the item (dynamic option)
            const fetchUrl = this.getAttribute('data-url'); // L'URL de la route de l'image

            console.log(fetchUrl);

            // Recover the modal item and insert a loading message
            const modalBody = document.querySelector('#editModal .modal-body');
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
                        modalBody.innerHTML = '<p>Unable to recover data from the image.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error when recovering data :', error);
                    modalBody.innerHTML = '<p>An error occurred. Please try again.</p>';
                });

            // Display the modal
            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        });
    });
});
