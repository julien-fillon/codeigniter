<?= $this->extend('./layout/layout') ?>

<?= $this->section('content') ?>

<?= view('dashboard/templates/header') ?>

<main class="container my-4">
    <div class="container mt-5">
        <h1 class="mb-4">Edit Event</h1>

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

        <?= form_open(route_to('event.update', $event['id'])) ?>

        <!-- QRCODE display -->
        <div class="mb-3 row">
            <?php if (!empty($event['qrcode'])): ?>
                <p class="col-8">Shorturl : <?= $event['qrcode'] ?></p>
                <img src="<?= base_url($event['qrcode']) ?>" class="col-2" alt="<?= $event['qrcode'] ?>">
            <?php endif; ?>
        </div>

        <!-- Event name -->
        <div class="mb-3">
            <?= form_label('Event Name:', 'event_name', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'  => 'event_name',
                'id'    => 'event_name',
                'class' => 'form-control',
                'required' => true,
                'value' => old('event_name', $event['event_name']),
            ]) ?>
        </div>

        <!-- Slug -->
        <div class="mb-3">
            <?= form_label('Slug:', 'slug', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'  => 'slug',
                'id'    => 'slug',
                'class' => 'form-control',
                'required' => true,
                'value' => old('slug', $event['slug']),
            ]) ?>
        </div>

        <!-- Organizing name -->
        <div class="mb-3">
            <?= form_label('Organizer Name:', 'organizer_name', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'  => 'organizer_name',
                'id'    => 'organizer_name',
                'class' => 'form-control',
                'required' => true,
                'value' => old('organizer_name', $event['organizer_name']),
            ]) ?>
        </div>

        <!-- Organizer's first name -->
        <div class="mb-3">
            <?= form_label('Organizer Surname:', 'organizer_surname', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'  => 'organizer_surname',
                'id'    => 'organizer_surname',
                'class' => 'form-control',
                'required' => true,
                'value' => old('organizer_surname', $event['organizer_surname']),
            ]) ?>
        </div>

        <!-- Organizer phone -->
        <div class="mb-3">
            <?= form_label('Organizer Phone:', 'organizer_phone', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'  => 'organizer_phone',
                'id'    => 'organizer_phone',
                'class' => 'form-control',
                'required' => true,
                'value' => old('organizer_phone', $event['organizer_phone']),
            ]) ?>
        </div>

        <!-- Organizer email -->
        <div class="mb-3">
            <?= form_label('Organizer Email:', 'organizer_email', ['class' => 'form-label']) ?>
            <?= form_input([
                'type'  => 'email',
                'name'  => 'organizer_email',
                'id'    => 'organizer_email',
                'class' => 'form-control',
                'required' => true,
                'value' => old('organizer_email', $event['organizer_email']),
            ]) ?>
        </div>

        <!-- Social ties -->
        <div class="mb-3">
            <?= form_label('Social Links:', 'social_links', ['class' => 'form-label']) ?>
            <?php
            $list_social_links = '';
            if (isset($event['social_links']) && is_array($event['social_links'])) {
                $list_social_links = implode(', ', $event['social_links']);
            }
            ?>
            <?= form_textarea([
                'name'  => 'social_links',
                'id'    => 'social_links',
                'class' => 'form-control',
                'rows'  => 3,
                'value' => old('social_links', $list_social_links),
            ]) ?>
        </div>

        <!-- Bouton de soumission -->
        <button type="submit" class="btn btn-primary">Save Changes</button>

        <?= form_close() ?>
    </div>
</main>

<?= view('dashboard/templates/footer') ?>

<?= $this->endSection() ?>