<?= $this->extend('./layout/layout') ?>

<?= $this->section('content') ?>

<?= view('templates/header'); ?>

<main class="container my-4">
    <p>/index.php</p>
</main>

<?= view('templates/footer'); ?>

<?= $this->endSection() ?>