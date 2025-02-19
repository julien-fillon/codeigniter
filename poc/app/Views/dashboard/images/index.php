<?= $this->extend('./layout/layout') ?>

<?= $this->section('content') ?>

<?= view('templates/header'); ?>

<main class="container my-4">
    <div class="container mt-5">
        <h1>Image Library</h1>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#uploadModal">
            Upload New Image
        </button>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger">
                <?= session('error'); ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('success')): ?>
            <div class="alert alert-success">
                <?= session('success'); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($images)) : ?>

            <?php foreach ($images as $image): ?>
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <img src="<?= base_url($image['path']) ?>" class="card-img-top" alt="<?= $image['name'] ?>">
                        <div class="card-body text-center">
                            <a href="/dashboard/images/delete/<?= $image['id'] ?>" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer cette image ?')">Supprimer</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No images found.</p>
        <?php endif; ?>
    </div>

    <?= view('dashboard/images/modal/upload'); ?>
</main>

<?= view('templates/footer'); ?>

<?= $this->endSection() ?>