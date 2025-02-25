<?php

use App\Enums\ImageCategory;
?>


<!-- Form -->
<?= form_open_multipart(route_to('images.update', $image['id'])) ?>
<?= csrf_field() ?>

<div class="modal-body">

    <!-- Remote of a new image -->
    <div class="mb-3">
        <?= form_label('New image (optional)', 'image' . esc($image['id']), ['class' => 'form-label']) ?>
        <?= form_upload([
            'name' => 'image',
            'id' => 'image' . esc($image['id']),
            'class' => 'form-control'
        ]) ?>
    </div>

    <!-- Image name -->
    <div class="mb-3">
        <?= form_label('Nom', 'name' . esc($image['id']), ['class' => 'form-label']) ?>
        <?= form_input([
            'type' => 'text',
            'name' => 'name',
            'id' => 'name' . esc($image['id']),
            'class' => 'form-control',
            'value' => old('name', $image['name']),
            'required' => true
        ]) ?>
    </div>

    <!-- Dropdown for the category -->
    <div class="mb-3">
        <?= form_label('Category', 'category', ['class' => 'form-label']) ?>
        <?= form_dropdown(
            'category',
            [
                '' => 'Select a category',
                ImageCategory::EVENT->value => 'Event',
                ImageCategory::DATE->value => 'Date'
            ],
            old('category', $image['category']),
            [
                'id' => 'category',
                'class' => 'form-select',
                'required' => true
            ]
        ) ?>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary">Save</button>
</div>