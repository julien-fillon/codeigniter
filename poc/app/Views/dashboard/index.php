<?= $this->extend('./layout/layout') ?>

<?= $this->section('content') ?>

<?= view('dashboard/templates/header'); ?>

<main class="container my-4">
    <p>/index.php</p>
</main>

<?= view('dashboard/templates/footer'); ?>

<?= $this->endSection() ?>