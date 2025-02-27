<!-- Add Date Modal -->
<div class="modal fade" id="dateAddModal" tabindex="-1" aria-labelledby="dateAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dateAddModalLabel">Add New Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Button to open the modal -->
                <button type="button" class="btn btn-secondary mt-3 image_selector_modal" data-bs-target="#imageDateSelectorModal" data-load-url="<?= route_to('dates.load_images') ?>" onclick="openChildModal()">
                    Select Images
                </button>

                <!-- Image preview -->
                <div class="mt-3">
                    <p>Selected Image:</p>
                    <img id="selectedImagePreview" src="" alt="No image selected" class="img-thumbnail" style="max-width: 100px; display: none;">
                </div>

                <?= form_open(route_to('dates.store')) ?>
                <?= csrf_field() ?>

                <!-- Hidden field for image_id -->
                <?= form_hidden('image_id', ''); ?>
                <?= form_hidden('event_id', $event['id']); ?>

                <div class="mb-3">
                    <?= form_label('Date:', 'date', ['class' => 'form-label']) ?>
                    <?= form_input([
                        'type'  => 'datetime-local',
                        'name'  => 'date',
                        'id'    => 'date',
                        'class' => 'form-control',
                        'required' => true,
                    ]) ?>
                </div>
                <div class="mb-3">
                    <?= form_label('Location:', 'location', ['class' => 'form-label']) ?>
                    <?= form_input([
                        'name'  => 'location',
                        'id'    => 'location',
                        'class' => 'form-control',
                        'required' => true,
                    ]) ?>
                </div>

                <button type="submit" class="btn btn-primary">Add Date</button>

                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<?= view('dashboard/images/modal/dates/upload'); ?>
<?= view('dashboard/images/modal/list', [
    'target' => 'uploadDateModal',
    'modalId' => 'imageDateSelectorModal',
    'saveId' => 'saveDateSelectedImage',
    'route' => route_to('dates.attach_images')
]); ?>