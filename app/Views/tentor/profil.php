<div class="flex flex-col gap-5 w-full max-w-2xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-[#2D3E39]">Profil</h1>
            <p class="text-xs text-gray-500 mt-0.5">Kelola data profil dan keamanan akun.</p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#e8f0ec] text-[#324f47] text-xs font-semibold">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Sistem Aktif
        </span>
    </div>

    <section class="flex flex-col items-center text-center py-2">
        <div class="w-28 h-28 rounded-full p-1 bg-[#e8f0ec] shadow-sm flex items-center justify-center">
            <?php if (!empty($profil['foto'])): ?>
                <img src="<?= e($profil['foto']) ?>" alt="Foto <?= e($profil['nama_lengkap']) ?>" class="w-full h-full rounded-full object-cover">
            <?php else: ?>
                <div class="w-full h-full rounded-full bg-[#324f47] text-white flex items-center justify-center text-4xl font-bold">
                    <?= e(strtoupper(substr($profil['nama_lengkap'], 0, 1))) ?>
                </div>
            <?php endif; ?>
        </div>
        <h2 class="text-2xl font-bold text-[#2D3E39] mt-3"><?= e($profil['nama_lengkap']) ?></h2>
        <p class="text-sm text-gray-500 mt-1">Tentor Aktif · Siatama Privat</p>
    </section>

    <form action="/tentor/profil/update" method="POST" class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm flex flex-col gap-4">
        <label class="text-xs font-semibold text-[#2D3E39]">
            Nama Lengkap
            <input required type="text" name="nama_lengkap" value="<?= e($profil['nama_lengkap']) ?>" class="mt-1.5 w-full rounded-xl border-gray-200 bg-[#F2EFE9] text-sm">
            <?php if (!empty($errors['nama_lengkap'])): ?><span class="block mt-1 text-xs text-red-600"><?= e($errors['nama_lengkap']) ?></span><?php endif; ?>
        </label>
        <label class="text-xs font-semibold text-[#2D3E39]">
            Asal Universitas
            <input required type="text" name="asal_universitas" value="<?= e($profil['asal_universitas']) ?>" class="mt-1.5 w-full rounded-xl border-gray-200 bg-[#F2EFE9] text-sm">
            <?php if (!empty($errors['asal_universitas'])): ?><span class="block mt-1 text-xs text-red-600"><?= e($errors['asal_universitas']) ?></span><?php endif; ?>
        </label>
        <label class="text-xs font-semibold text-[#2D3E39]">
            Username Akun
            <input required type="text" name="username" value="<?= e($profil['username']) ?>" class="mt-1.5 w-full rounded-xl border-gray-200 bg-[#F2EFE9] text-sm">
            <?php if (!empty($errors['username'])): ?><span class="block mt-1 text-xs text-red-600"><?= e($errors['username']) ?></span><?php endif; ?>
        </label>

        <div class="flex items-center gap-3 pt-2"><span class="h-px bg-gray-200 flex-1"></span><span class="text-[11px] text-gray-500 uppercase tracking-wider">Keamanan Akun</span><span class="h-px bg-gray-200 flex-1"></span></div>
        <label class="text-xs font-semibold text-[#2D3E39]">
            Password Baru
            <input type="password" name="password_baru" placeholder="Kosongkan jika tidak ingin mengubah" class="mt-1.5 w-full rounded-xl border-gray-200 bg-[#F2EFE9] text-sm">
        </label>
        <label class="text-xs font-semibold text-[#2D3E39]">
            Konfirmasi Password Baru
            <input type="password" name="konfirmasi_password" placeholder="Ulangi password baru" class="mt-1.5 w-full rounded-xl border-gray-200 bg-[#F2EFE9] text-sm">
            <?php if (!empty($errors['password'])): ?><span class="block mt-1 text-xs text-red-600"><?= e($errors['password']) ?></span><?php endif; ?>
        </label>

        <button type="submit" class="w-full py-3 rounded-xl bg-[#324f47] text-white text-sm font-semibold flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[18px]">save</span>
            Simpan Perubahan
        </button>
    </form>

    <div class="bg-[#F2EFE9] rounded-2xl p-4 flex items-start gap-3">
        <span class="material-symbols-outlined text-[#8C711C]">info</span>
        <p class="text-xs text-gray-600 leading-relaxed">Pastikan data diri dan akun tetap sesuai untuk kelancaran laporan mengajar.</p>
    </div>
</div>
