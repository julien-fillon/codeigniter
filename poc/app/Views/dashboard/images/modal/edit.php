<!-- Edition modal -->
<div class="modal fade" id="editModal<?= $image['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $image['id'] ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel<?= $image['id'] ?>">Edit the image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/dashboard/images/update/<?= $image['id'] ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="image<?= $image['id'] ?>" class="form-label">New image (optional)</label>
                        <input type="file" class="form-control" id="image<?= $image['id'] ?>" name="image">
                    </div>
                    <div class="mb-3">
                        <label for="name<?= $image['id'] ?>" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name<?= $image['id'] ?>" name="name" value="<?= esc($image['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="category<?= $image['id'] ?>" class="form-label">Category</label>
                        <input type="text" class="form-control" id="category<?= $image['id'] ?>" name="category" value="<?= esc($image['category']) ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>