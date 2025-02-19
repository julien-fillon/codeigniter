<?= $this->extend('./layout/layout') ?>

<?= $this->section('content') ?>

<main class="container my-4">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h2>Connexion</h2>
                    </div>
                    <div class="card-body">
                        <!-- Validation errors display -->
                        <?php if (isset($validation)): ?>
                            <div class="alert alert-danger">
                                <?= $validation->listErrors() ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger">
                                <?= $error; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Form -->
                        <form action="<?= site_url('auth/loginSubmit') ?>" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email :</label>
                                <input type="text" name="email" id="email" class="form-control" value="<?= set_value('email') ?>" placeholder="Entrez votre email">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe :</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Entrez votre mot de passe">
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Connexion</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <small class="text-muted">Pas encore de compte ? <a href="<?= site_url('auth/register') ?>">Inscrivez-vous ici</a>.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection() ?>