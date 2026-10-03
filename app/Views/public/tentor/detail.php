<div class="max-w-2xl mx-auto w-full flex flex-col gap-6">
    <a href="/profil-tentor" class="inline-flex items-center gap-2 text-sm font-semibold text-[#324f47]">
        <span class="material-symbols-outlined text-lg">arrow_back</span>
        Kembali ke Profil Tentor
    </a>

    <section class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-200/80 text-center">
        <div class="w-28 h-28 mx-auto rounded-full overflow-hidden bg-[#e8f0ec] text-[#324f47] flex items-center justify-center font-bold text-4xl border-4 border-white shadow-md">
            <?php if (!empty($tentor['foto'])): ?>
                <img src="<?= e($tentor['foto']) ?>" alt="<?= e($tentor['nama_lengkap']) ?>" class="w-full h-full object-cover">
            <?php else: ?>
                <?= e(strtoupper(substr($tentor['nama_lengkap'], 0, 1))) ?>
            <?php endif; ?>
        </div>
        <h1 class="mt-5 text-2xl font-bold text-[#2D3E39]"><?= e($tentor['nama_lengkap']) ?></h1>
        <p class="mt-2 text-sm text-gray-500 flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-lg text-[#324f47]">school</span>
            <?= e($tentor['asal_universitas'] ?: 'Tentor Siatama Privat') ?>
        </p>
    </section>

    <section class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/80">
        <h2 class="text-lg font-bold text-[#2D3E39]">Tentang Tentor</h2>
        <p class="mt-3 text-sm leading-7 text-gray-600">
            <?= nl2br(e($tentor['bio'] ?: 'Tentor profesional Siatama Privat yang siap mendampingi proses belajar siswa dengan pendekatan personal dan menyenangkan.')) ?>
        </p>
    </section>

    <section class="bg-[#e8f0ec] rounded-2xl p-5 flex items-center gap-3">
        <span class="material-symbols-outlined text-[#324f47] text-3xl">verified</span>
        <p class="text-sm text-[#2D3E39]">Tentor terverifikasi Siatama Privat.</p>
    </section>
</div>
