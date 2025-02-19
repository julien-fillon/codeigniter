<?= $this->extend('./layout/layout') ?>

<?= $this->section('content') ?>

<?= view('dashboard/templates/header'); ?>

<main class="container my-4">
    <div class="container mt-5">
        <h1 class="mb-4">Create New Event</h1>

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

        <form action="/dashboard/events/store" method="post">
            <div class="mb-3">
                <label for="event_name" class="form-label">Event Name:</label>
                <input type="text" class="form-control" name="event_name" id="event_name" required>
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label">Slug :</label>
                <input type="text" class="form-control" name="slug" id="slug">
            </div>

            <div class="mb-3">
                <label for="organizer_name" class="form-label">Organizer Name:</label>
                <input type="text" class="form-control" name="organizer_name" id="organizer_name" required>
            </div>

            <div class="mb-3">
                <label for="organizer_surname" class="form-label">Organizer Surname:</label>
                <input type="text" class="form-control" name="organizer_surname" id="organizer_surname" required>
            </div>

            <div class="mb-3">
                <label for="organizer_phone" class="form-label">Organizer Phone:</label>
                <input type="text" class="form-control" name="organizer_phone" id="organizer_phone" required>
            </div>

            <div class="mb-3">
                <label for="organizer_email" class="form-label">Organizer Email:</label>
                <input type="email" class="form-control" name="organizer_email" id="organizer_email" required>
            </div>

            <div class="mb-3">
                <label for="social_links" class="form-label">Social Links :</label>
                <textarea class="form-control" name="social_links" id="social_links" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
    </div>
</main>

<?= view('dashboard/templates/footer'); ?>

<?= $this->endSection() ?>