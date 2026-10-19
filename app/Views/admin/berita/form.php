<div class="flex flex-col gap-5 w-full">
    <!-- Header Page & Back Button -->
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-3">
            <a href="/admin/berita" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight"><?= $isEdit ? 'Edit Berita Publik' : 'Tulis Berita Baru' ?></h1>
                <p class="text-xs text-gray-500">Kelola artikel atau informasi yang akan tampil pada halaman berita publik</p>
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-gray-200/80">
        <form action="<?= $isEdit ? '/admin/berita/' . $berita['id'] . '/update' : '/admin/berita/simpan' ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
            <!-- Judul Berita -->
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700">Judul Berita <span class="text-red-500">*</span></label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">title</span>
                    <input type="text" name="judul" value="<?= e($berita['judul'] ?? '') ?>" placeholder="Masukkan judul berita yang menarik" required class="w-full pl-10 pr-4 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all <?= isset($errors['judul']) ? '!border-red-500' : '' ?>">
                </div>
                <?php if (isset($errors['judul'])): ?>
                    <small class="text-xs text-red-600"><?= e($errors['judul']) ?></small>
                <?php endif; ?>
            </div>

            <!-- Upload Gambar Utama -->
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700">Gambar Utama / Header Berita <small class="text-gray-400 font-normal">(Opsional)</small></label>
                
                <?php if (!empty($berita['gambar'])): ?>
                    <div class="flex items-center gap-3 p-3 bg-[#FAF9F7] rounded-xl border border-gray-200">
                        <img src="<?= e($berita['gambar']) ?>" alt="Preview gambar" class="w-20 h-16 rounded-lg object-cover border border-gray-200">
                        <div class="text-xs text-gray-500">
                            <span class="font-medium text-gray-700 block">Gambar saat ini</span>
                            Unggah file baru di bawah ini jika ingin menggantinya.
                        </div>
                    </div>
                <?php endif; ?>

                <div class="relative flex items-center">
                    <input type="file" name="gambar" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-gray-700 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#e8f0ec] file:text-[#2d5a4c] hover:file:bg-[#d8e8e0] bg-[#FAF9F7] rounded-xl border border-gray-200 cursor-pointer <?= isset($errors['gambar']) ? '!border-red-500' : '' ?>">
                </div>
                <small class="text-[11px] text-gray-500">Format yang didukung: JPG, PNG, WEBP. Ukuran maksimal 3 MB.</small>
                <?php if (isset($errors['gambar'])): ?>
                    <small class="text-xs text-red-600"><?= e($errors['gambar']) ?></small>
                <?php endif; ?>
            </div>

            <!-- Konten / Isi Berita -->
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700">Konten / Isi Berita <span class="text-red-500">*</span></label>
                <textarea name="isi" rows="9" placeholder="Tuliskan isi berita selengkapnya di sini..." required class="w-full p-4 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all leading-relaxed <?= isset($errors['isi']) ? '!border-red-500' : '' ?>"><?= e($berita['isi'] ?? '') ?></textarea>
                <?php if (isset($errors['isi'])): ?>
                    <small class="text-xs text-red-600"><?= e($errors['isi']) ?></small>
                <?php endif; ?>
            </div>

            <!-- Checkbox Publikasi -->
            <div class="pt-1">
                <label class="inline-flex items-center gap-2.5 text-xs font-semibold text-gray-700 cursor-pointer select-none">
                    <input type="checkbox" name="status_terbit" value="1" <?= (!empty($berita['status_terbit'])) ? 'checked' : '' ?> class="w-4 h-4 rounded text-[#324f47] focus:ring-[#324f47] border-gray-300">
                    <span>Terbitkan Berita Ke Publik (Publish)</span>
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
                <button type="submit" class="flex-1 py-3 px-4 bg-[#324f47] hover:bg-[#2D3E39] text-white font-semibold rounded-xl text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    <?= $isEdit ? 'Simpan Perubahan' : 'Simpan & Publikasikan Berita' ?>
                </button>
                <a href="/admin/berita" class="py-3 px-5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
