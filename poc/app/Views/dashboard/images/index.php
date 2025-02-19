<?= $this->extend('./layout/layout') ?>

<?= $this->section('content') ?>

<?= view('templates/header'); ?>

<main class="container my-4">
    <div class="container mt-5">
        <h1>Image Library</h1>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#uploadModal">
            Upload New Image
        </button>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?= $error; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <?= $success; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($images)) : ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Size (bytes)</th>
                        <th>Dimensions</th>
                        <th>Preview</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($images as $image): ?>
                        <tr>
                            <td><?= $image['id'] ?></td>
                            <td><?= $image['name'] ?></td>
                            <td><?= $image['category'] ?: '-' ?></td>
                            <td><?= $image['type'] ?></td>
                            <td><?= $image['size'] ?></td>
                            <td><?= $image['width'] ?> x <?= $image['height'] ?></td>
                            <td><img src="<?= $image['path'] ?>" width="100" alt="<?= $image['path'] ?>"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No images found.</p>
        <?php endif; ?>
    </div>

    <?= view('dashboard/images/modal/upload'); ?>
</main>

<?= view('templates/footer'); ?>

<?= $this->endSection() ?>