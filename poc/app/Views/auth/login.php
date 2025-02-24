<?= $this->extend('./layout/layout') ?>

<?= $this->section('content') ?>

<main class="container my-4">
    <!-- Connection form display-->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h2>Connexion</h2>
                    </div>
                    <div class="card-body">

                        <!-- Validation errors display -->
                        <?php if (isset($validation)) : ?>
                            <div class="alert alert-danger">
                                <?= $validation->listErrors(); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Exceptional errors display -->
                        <?php if (isset($error)) : ?>
                            <div class="alert alert-danger">
                                <?= esc($error); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Form -->
                        <?= form_open(route_to('auth.loginSubmit')) ?>
                        <?= csrf_field() ?>

                        <!-- Field email -->
                        <div class="mb-3">
                            <?= form_label('Email :', 'email', ['class' => 'form-label']) ?>
                            <?= form_input([
                                'name'  => 'email',
                                'id'    => 'email',
                                'type'  => 'text',
                                'class' => 'form-control ' . (isset($validation) && $validation->hasError('email') ? 'is-invalid' : ''),
                                'value' => set_value('email'),
                                'placeholder' => 'Enter your email',
                            ]) ?>
                            <?php if (isset($validation) && $validation->hasError('email')) : ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('email') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Password field -->
                        <div class="mb-3">
                            <?= form_label('Password :', 'password', ['class' => 'form-label']) ?>
                            <?= form_password([
                                'name'  => 'password',
                                'id'    => 'password',
                                'class' => 'form-control ' . (isset($validation) && $validation->hasError('password') ? 'is-invalid' : ''),
                                'placeholder' => 'Enter your password',
                            ]) ?>
                            <?php if (isset($validation) && $validation->hasError('password')) : ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('password') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Submission button -->
                        <div class="mb-3">
                            <?= form_submit('submit', 'Connexion', ['class' => 'btn btn-primary w-100']) ?>
                        </div>

                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection() ?>