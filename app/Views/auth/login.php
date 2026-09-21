<div class="max-w-md mx-auto py-6 px-4">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/80">
        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-2xl bg-[#324f47] text-white flex items-center justify-center mx-auto mb-3 shadow-xs">
                <span class="material-symbols-outlined text-2xl">school</span>
            </div>
            <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight">Siatama Privat</h1>
            <p class="text-xs text-gray-500 mt-1">Silakan masuk ke akun Admin / Tentor Anda</p>
        </div>

        <?php if (isset($errors['general'])): ?>
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-4 text-xs flex items-center gap-2">
                <span class="material-symbols-outlined text-red-600 text-sm">error</span>
                <div><?= e($errors['general']) ?></div>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST" class="flex flex-col gap-4">
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700 px-0.5">Username</label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">person</span>
                    <input type="text" name="username" value="<?= e($username ?? '') ?>" placeholder="Masukkan username" required class="w-full pl-10 pr-4 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all">
                </div>
                <?php if (isset($errors['username'])): ?>
                    <small class="text-xs text-red-600 mt-0.5 px-0.5"><?= e($errors['username']) ?></small>
                <?php endif; ?>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700 px-0.5">Password</label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">lock</span>
                    <input type="password" name="password" placeholder="Masukkan password" required class="w-full pl-10 pr-4 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all">
                </div>
                <?php if (isset($errors['password'])): ?>
                    <small class="text-xs text-red-600 mt-0.5 px-0.5"><?= e($errors['password']) ?></small>
                <?php endif; ?>
            </div>

            <button type="submit" class="w-full mt-2 py-3 px-4 bg-[#324f47] hover:bg-[#2D3E39] active:scale-[0.99] text-white font-semibold text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[18px]">login</span>
                Masuk ke Portal
            </button>
        </form>
    </div>
</div>
