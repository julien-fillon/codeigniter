<?php foreach ($images as $image): ?>
    <div class="col-3">
        <div class="card mb-3">
            <img
                src="<?= base_url($image['path']) ?>"
                class="card-img-top img-thumbnail"
                alt="<?= $image['name'] ?>"
                style="height: 100px; object-fit: cover;"
                data-url="<?= route_to('images.edit', $image['id']) ?>">
            <div class="card-body text-center">
                <input type="checkbox"
                    class="form-check-input"
                    id="image-<?= $image['id'] ?>"
                    name="selected_images[]"
                    value="<?= $image['id'] ?>"
                    <?= in_array($image['id'], $associatedImageIds) ? 'checked' : '' ?>>
                <label for="image-<?= $image['id'] ?>"><?= $image['name'] ?></label>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?= view('dashboard/images/modal/edit'); ?>