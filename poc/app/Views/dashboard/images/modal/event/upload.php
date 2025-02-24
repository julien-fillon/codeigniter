<?php

use App\Enums\ImageCategory;
?>
<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload an image fo Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <!-- Upload form-->
            <?= form_open_multipart(route_to('images.upload.event')) ?>
            <?= csrf_field() ?>

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


                <?= form_hidden('category', ImageCategory::EVENT->value) ?>
                <?= form_hidden('entity_id', $id) ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>