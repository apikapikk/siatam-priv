<div class="ui-card" style="padding: 24px; text-align: center;">
    <div style="margin-bottom: 20px;">
        <div style="width: 56px; height: 56px; border-radius: 12px; background: #e8f0ec; display: grid; place-items: center; margin: 0 auto 12px; color: #1b4332; font-weight: 800; font-size: 20px;">
            ST
        </div>
        <h1 style="font-size: 22px; font-weight: 700; margin: 0; color: #1f2937;">Siatama Privat</h1>
        <p style="color: #6b7280; font-size: 13px; margin-top: 4px;">Masuk ke portal Admin / Tentor</p>
    </div>

    <?php if (isset($errors['general'])): ?>
        <div class="soft-alert tone-red" style="background: #fef2f2; color: #991b1b; border-color: #fecaca; margin-bottom: 16px; text-align: left;">
            <?= e($errors['general']) ?>
        </div>
    <?php endif; ?>

    <form action="/login" method="POST" style="text-align: left;">
        <div class="form-group">
            <label class="form-label">Username</label>
            <input type="text" name="username" value="<?= e($username ?? '') ?>" placeholder="Masukkan username" required class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>">
            <?php if (isset($errors['username'])): ?>
                <small class="form-error"><?= e($errors['username']) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>">
            <?php if (isset($errors['password'])): ?>
                <small class="form-error"><?= e($errors['password']) ?></small>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px; padding: 12px;">
            Masuk ke Aplikasi
        </button>
    </form>
</div>
