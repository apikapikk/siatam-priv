<div class="max-w-2xl mx-auto w-full flex flex-col gap-8 pt-2">
    <section class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-200/80 flex flex-col items-center text-center">
        <div class="w-32 h-32 rounded-full bg-[#e8f0ec] flex items-center justify-center mb-4 overflow-hidden">
            <img src="/assets/logo.png" alt="Logo Siatama Privat" class="w-28 h-28 object-contain">
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-[#2D3E39]">Apa itu Siatama Privat?</h1>
        <p class="mt-3 text-base leading-7 text-gray-600 max-w-md">
            Mitra belajar terpercaya Anda. Kami menyediakan layanan bimbingan belajar eksklusif dan personal, dirancang khusus untuk memaksimalkan potensi akademik setiap siswa dengan pendekatan yang ramah dan profesional.
        </p>
        <a href="#kontak-section" class="mt-6 h-[52px] px-8 rounded-lg bg-[#324f47] text-white text-sm font-semibold shadow-sm flex items-center justify-center gap-2 hover:opacity-90">
            HUBUNGI KAMI <span class="material-symbols-outlined">arrow_forward</span>
        </a>
    </section>

    <section class="flex flex-col gap-3">
        <h2 class="text-xl font-bold text-[#2D3E39] px-2 flex items-center gap-2">
            <span class="material-symbols-outlined text-[#d7a84b]">star</span> Review Pelajar
        </h2>
        <div class="flex overflow-x-auto gap-3 pb-2 snap-x snap-mandatory">
            <?php $reviews = [
                ['nama' => 'Nami', 'isi' => 'Pengajaran sangat jelas! Guru sangat sabar membimbing materi yang sulit dipahami.'],
                ['nama' => 'Namu', 'isi' => 'Nilai saya naik pesat! Program belajarnya sangat terstruktur dan mudah diikuti.'],
                ['nama' => 'Dina', 'isi' => 'Sangat merekomendasikan Siatama untuk persiapan ujian.'],
            ]; ?>
            <?php foreach ($reviews as $review): ?>
                <article class="snap-center shrink-0 w-72 bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#e8f0ec] text-[#324f47] flex items-center justify-center font-bold"><?= e(substr($review['nama'], 0, 1)) ?></div>
                        <div>
                            <h3 class="text-sm font-semibold text-[#2D3E39]"><?= e($review['nama']) ?></h3>
                            <div class="flex text-[#d7a84b] text-sm">★★★★★</div>
                        </div>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-gray-600 italic">“<?= e($review['isi']) ?>”</p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="flex flex-col gap-3">
        <h2 class="text-xl font-bold text-[#2D3E39] px-2">Lokasi Kami</h2>
        <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-200/80">
            <div class="h-40 bg-[#e8f0ec] flex items-center justify-center text-[#324f47]">
                <span class="material-symbols-outlined text-6xl">location_on</span>
            </div>
            <div class="p-4 flex items-center justify-between">
                <div><h3 class="font-semibold text-[#2D3E39]">Siatama Privat Pusat</h3><p class="text-sm text-gray-500">Surabaya, Indonesia</p></div>
                <span class="material-symbols-outlined text-[#324f47]">directions</span>
            </div>
        </div>
    </section>

    <section id="kontak-section" class="flex flex-col gap-3">
        <h2 class="text-xl font-bold text-[#2D3E39] px-2">Kontak Kami</h2>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200/80 flex flex-col gap-4">
            <div class="flex items-center gap-4"><span class="material-symbols-outlined text-[#324f47]">call</span><div><p class="font-semibold text-sm">WhatsApp</p><p class="text-sm text-gray-500">+62 812-3456-7890</p></div></div>
            <div class="flex items-center gap-4"><span class="material-symbols-outlined text-[#324f47]">share</span><div><p class="font-semibold text-sm">Instagram</p><p class="text-sm text-gray-500">@siatamaprivat</p></div></div>
        </div>
    </section>
</div>
