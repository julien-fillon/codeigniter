<!-- Modal Bootstrap -->
<meta name="csrf-token-name" content="<?= csrf_token() ?>">
<meta name="csrf-hash" content="<?= csrf_hash() ?>">

<div class="modal fade" id="imageSelectorModal" tabindex="-1" aria-labelledby="imageSelectorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageSelectorModalLabel">Select Images</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row" id="image-list">
                    <!-- Placeholder: Images will be dynamically loaded here -->
                    <div id="image-list" class="row text-center">
                        <p>Loading images...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadModal">Add New Image</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-selected-images" data-save-url="<?= route_to('events.attach_images', $event['id']) ?>">Save Selection</button>
            </div>
        </div>
    </div>
</div>