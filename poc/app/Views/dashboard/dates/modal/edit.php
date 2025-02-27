<!-- Unique Edit Modal -->
<div class="modal fade" id="dateEditModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit the date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Loading in progress ...</p>
            </div>
        </div>
    </div>
</div>

<?= view('dashboard/images/modal/dates/upload'); ?>
<?= view('dashboard/images/modal/list', [
    'target' => 'uploadDateModal',
    'modalId' => 'imageDateSelectorModal',
    'saveId' => 'saveDateSelectedImage'
]); ?>