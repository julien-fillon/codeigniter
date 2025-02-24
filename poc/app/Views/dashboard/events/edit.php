<?= $this->extend('./layout/layout') ?>

<?= $this->section('content') ?>

<?= view('dashboard/templates/header') ?>

<main class="container my-4">
    <div class="container mt-5">
        <h1 class="mb-4">Edit Event</h1>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger">
                <?= session('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('success')): ?>
            <div class="alert alert-success">
                <?= session('success') ?>
            </div>
        <?php endif; ?>

        <!-- QRCODE display -->
        <div class="mb-3 row">
            <?php if (!empty($event['qrcode'])): ?>
                <p class="col-8">Shorturl : /events/<?= $event['shorturl'] ?></p>
                <img src="<?= base_url($event['qrcode']) ?>" class="col-2" alt="<?= $event['qrcode'] ?>">
            <?php endif; ?>
        </div>

        <!-- List of associated images -->
        <div class="mb-3 mt-3">
            <h4>Associated Images</h4>
            <!-- Parent container for the grid -->
            <div id="associated-images" class="row">
                <?php if (!empty($event['images'])): ?>
                    <?php foreach ($event['images'] as $image): ?>
                        <!-- Each image in a Bootstrap column -->
                        <div class="col-md-1 mb-3">
                            <img src="<?= base_url($image['path']) ?>" alt="<?= $image['name'] ?>" class="img-thumbnail">
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No images associated with this event.</p>
                <?php endif; ?>
            </div>

            <!-- Button to open the modal -->
            <button type="button" class="btn btn-secondary mt-3" data-bs-toggle="modal" data-bs-target="#imageSelectorModal">
                Select Images
            </button>

            <?= view('dashboard/images/modal/list', ['id' => $event['id']]); ?>
            <?= view('dashboard/images/modal/event/upload', ['id' => $event['id']]); ?>
        </div>

        <?= form_open(route_to('events.update', $event['id'])) ?>
        <?= csrf_field() ?>
        <!-- Event name -->
        <div class="mb-3">
            <?= form_label('Event Name:', 'event_name', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'  => 'event_name',
                'id'    => 'event_name',
                'class' => 'form-control',
                'required' => true,
                'value' => old('event_name', $event['event_name']),
            ]) ?>
        </div>

        <!-- Slug -->
        <div class="mb-3">
            <?= form_label('Slug:', 'slug', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'  => 'slug',
                'id'    => 'slug',
                'class' => 'form-control',
                'required' => true,
                'value' => old('slug', $event['slug']),
            ]) ?>
        </div>

        <!-- Organizing name -->
        <div class="mb-3">
            <?= form_label('Organizer Name:', 'organizer_name', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'  => 'organizer_name',
                'id'    => 'organizer_name',
                'class' => 'form-control',
                'required' => true,
                'value' => old('organizer_name', $event['organizer_name']),
            ]) ?>
        </div>

        <!-- Organizer's first name -->
        <div class="mb-3">
            <?= form_label('Organizer Surname:', 'organizer_surname', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'  => 'organizer_surname',
                'id'    => 'organizer_surname',
                'class' => 'form-control',
                'required' => true,
                'value' => old('organizer_surname', $event['organizer_surname']),
            ]) ?>
        </div>

        <!-- Organizer phone -->
        <div class="mb-3">
            <?= form_label('Organizer Phone:', 'organizer_phone', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'  => 'organizer_phone',
                'id'    => 'organizer_phone',
                'class' => 'form-control',
                'required' => true,
                'value' => old('organizer_phone', $event['organizer_phone']),
            ]) ?>
        </div>

        <!-- Organizer email -->
        <div class="mb-3">
            <?= form_label('Organizer Email:', 'organizer_email', ['class' => 'form-label']) ?>
            <?= form_input([
                'type'  => 'email',
                'name'  => 'organizer_email',
                'id'    => 'organizer_email',
                'class' => 'form-control',
                'required' => true,
                'value' => old('organizer_email', $event['organizer_email']),
            ]) ?>
        </div>

        <!-- Social ties -->
        <div class="mb-3">
            <?= form_label('Social Links:', 'social_links', ['class' => 'form-label']) ?>
            <?php
            $list_social_links = '';
            if (isset($event['social_links']) && is_array($event['social_links'])) {
                $list_social_links = implode(', ', $event['social_links']);
            }
            ?>
            <?= form_textarea([
                'name'  => 'social_links',
                'id'    => 'social_links',
                'class' => 'form-control',
                'rows'  => 3,
                'value' => old('social_links', $list_social_links),
            ]) ?>
        </div>

        <!-- Bouton de soumission -->
        <button type="submit" class="btn btn-primary">Save Changes</button>

        <?= form_close() ?>
    </div>
</main>

<?= view('dashboard/templates/footer') ?>

<script>
    document.querySelector('#save-selected-images').addEventListener('click', function() {
        // Recover all the selected images
        const selectedImages = Array.from(document.querySelectorAll('#image-list input[name="selected_images[]"]:checked')).map(input => input.value);

        // Ajax call to record images associated with the event
        fetch('<?= route_to("events.attach_images", $event["id"]) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                },
                body: JSON.stringify({
                    image_ids: selectedImages
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the list of associated images in the DOM
                    const associatedImagesContainer = document.querySelector('#associated-images');
                    associatedImagesContainer.innerHTML = '';

                    data.images.forEach(image => {
                        associatedImagesContainer.innerHTML += `
                        <div class="col-md-1 mb-3">
                            <img src="<?= base_url() ?>/${image.path}" alt="${image.name}" class="img-thumbnail">
                        </div>
                    `;
                    });

                    // Fermer La Modal
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
    document.querySelector('[data-bs-target="#imageSelectorModal"]').addEventListener('click', function() {
        const modalBody = document.querySelector('#image-list');
        modalBody.innerHTML = '<p>Loading images...</p>'; // Indicates that the images are being loaded

        // Dynamically load images via Ajax
        fetch('<?= route_to("events.load_images", $event["id"]) ?>', {
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
                console.error('Erreur lors du chargement des images :', error);
                modalBody.innerHTML = '<p>An error occurred while loading images.</p>';
            });
    });
</script>

<?= $this->endSection() ?>