<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload an image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <!-- Upload form-->
            <?= form_open_multipart(route_to('images.upload')) ?>
            <div class="modal-body">

                <!-- Selection of an image -->
                <div class="mb-3">
                    <?= form_label('Choose an image', 'image', ['class' => 'form-label']) ?>
                    <?= form_upload([
                        'name' => 'image',
                        'id' => 'image',
                        'class' => 'form-control',
                        'accept' => 'image/*',
                        'required' => true
                    ]) ?>
                </div>

                <!-- Field for name -->
                <div class="mb-3">
                    <?= form_label('Name', 'name', ['class' => 'form-label']) ?>
                    <?= form_input([
                        'type' => 'text',
                        'name' => 'name',
                        'id' => 'name',
                        'class' => 'form-control',
                        'value' => old('name'),
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
                            'event' => 'Event',
                            'date' => 'Date'
                        ],
                        old('category'),
                        [
                            'id' => 'category',
                            'class' => 'form-select',
                            'required' => true
                        ]
                    ) ?>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>