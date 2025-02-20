<?= $this->extend('./layout/layout') ?>

<?= $this->section('content') ?>

<?= view('dashboard/templates/header'); ?>

<main class="container my-4">
    <div class="container mt-5">
        <h1>Events</h1>

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


        <a href="/dashboard/events/create" class="btn btn-primary mb-3">Create New Event</a>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Organizer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= $event['id']; ?></td>
                        <td><?= $event['event_name']; ?></td>
                        <td><?= $event['organizer_name'] . ' ' . $event['organizer_surname']; ?></td>
                        <td>
                            <a href="/dashboard/events/edit/<?= $event['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="/dashboard/events/delete/<?= $event['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</main>

<?= view('dashboard/templates/footer'); ?>

<?= $this->endSection() ?>