<?= $this->extend('./layout/layout') ?>

<?= $this->section('content') ?>

<?= view('dashboard/templates/header') ?>

<main class="container my-4">
    <div class="container mt-5">
        <h1 class="mb-4">Create New Event</h1>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger">
                <?= session('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('success')): ?>
            <div class="alert alert-success">
                <?= session('success') ?>
            </div>
        <?php endif; ?>

        <?= form_open(route_to('events.store')) ?>
        <?= csrf_field() ?>

        <div class="mb-3">
            <?= form_label('Event Name:', 'event_name', ['class' => 'form-label']) ?>
            <?= form_input([
                'name' => 'event_name',
                'id' => 'event_name',
                'class' => 'form-control',
                'required' => true,
                'value' => old('event_name')
            ]) ?>
        </div>

        <div class="mb-3">
            <?= form_label('Slug:', 'slug', ['class' => 'form-label']) ?>
            <?= form_input([
                'name' => 'slug',
                'id' => 'slug',
                'class' => 'form-control',
                'value' => old('slug')
            ]) ?>
        </div>

        <div class="mb-3">
            <?= form_label('Organizer Name:', 'organizer_name', ['class' => 'form-label']) ?>
            <?= form_input([
                'name' => 'organizer_name',
                'id' => 'organizer_name',
                'class' => 'form-control',
                'required' => true,
                'value' => old('organizer_name')
            ]) ?>
        </div>

        <div class="mb-3">
            <?= form_label('Organizer Surname:', 'organizer_surname', ['class' => 'form-label']) ?>
            <?= form_input([
                'name' => 'organizer_surname',
                'id' => 'organizer_surname',
                'class' => 'form-control',
                'required' => true,
                'value' => old('organizer_surname')
            ]) ?>
        </div>

        <div class="mb-3">
            <?= form_label('Organizer Phone:', 'organizer_phone', ['class' => 'form-label']) ?>
            <?= form_input([
                'name' => 'organizer_phone',
                'id' => 'organizer_phone',
                'class' => 'form-control',
                'required' => true,
                'value' => old('organizer_phone')
            ]) ?>
        </div>

        <div class="mb-3">
            <?= form_label('Organizer Email:', 'organizer_email', ['class' => 'form-label']) ?>
            <?= form_input([
                'name' => 'organizer_email',
                'id' => 'organizer_email',
                'type' => 'email',
                'class' => 'form-control',
                'required' => true,
                'value' => old('organizer_email')
            ]) ?>
        </div>

        <div class="mb-3">
            <?= form_label('Social Links:', 'social_links', ['class' => 'form-label']) ?>
            <?= form_textarea([
                'name' => 'social_links',
                'id' => 'social_links',
                'class' => 'form-control',
                'rows' => 3,
                'value' => old('social_links')
            ]) ?>
        </div>

        <button type="submit" class="btn btn-success">Save</button>

        <?= form_close() ?>
    </div>
</main>

<?= view('dashboard/templates/footer') ?>

<?= $this->endSection() ?>