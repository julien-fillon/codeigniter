<!-- Button to open the modal -->
<button type="button" class="btn btn-secondary mt-3 image_selector_modal" data-bs-target="#imageDateSelectorModal" data-load-url="<?= route_to('dates.load_images') ?>" onclick="openChildModal()">
    Select Images
</button>

<!-- Image preview -->
<div class="mt-3">
    <p>Selected Image:</p>
    <img id="selectedImagePreview" src="<?= $date['image']['path']; ?>" alt="No image selected" class="img-thumbnail" style="max-width: 100px;">
</div>

<?= form_open(route_to('dates.update', $date['id'])) ?>
<?= csrf_field() ?>

<!-- Hidden field for image_id -->
<?= form_hidden('image_id', $date['image_id']); ?>
<?= form_hidden('event_id', $date['event_id']); ?>

<div class="mb-3">
    <?= form_label('Date:', 'date', ['class' => 'form-label']) ?>
    <?= form_input([
        'type'  => 'datetime-local',
        'name'  => 'date',
        'id'    => 'date',
        'class' => 'form-control',
        'value' => old('date', $date['date']),
        'required' => true,
    ]) ?>
</div>
<div class="mb-3">
    <?= form_label('Location:', 'location', ['class' => 'form-label']) ?>
    <?= form_input([
        'name'  => 'location',
        'id'    => 'location',
        'class' => 'form-control',
        'value' => old('location', $date['location']),
        'required' => true,
    ]) ?>
</div>

<button type="submit" class="btn btn-primary">Edit Date</button>

<?= form_close() ?>