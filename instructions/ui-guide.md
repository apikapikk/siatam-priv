# Panduan Sistem Desain & Token UI — Siatama Privat
**Tema Desain:** Sage & Harvest (Minimalis, Hangat, Ramah Pengguna & Elegan)

---

## 🎨 1. Palet Warna (Color Palette)

| Kategori | Token / Nama | Nilai HEX / RGBA | Representasi | Penggunaan Utama |
| :--- | :--- | :--- | :--- | :--- |
| **Primary (Brand)** | `forest-deep` | `#1B4332` / `#1E3F33` | 🟢 Hijau Gelap / Forest Green | Tombol utama, header aktif, icon aktif, brand text |
| **Primary Hover/Focus** | `forest-dark` | `#143326` | 🟢 Hijau Hutan Tua | State hover/active pada tombol utama |
| **Primary Container** | `sage-light` | `#E8F0EC` | 🟢 Hijau Sage Lembut | Background badge aktif, container icon, highlight tab |
| **Secondary Accent** | `harvest-gold` | `#D4A373` / `#C59B27` | 🟡 Emas / Harvest Sand | Badge kategori, aksen bintang, highlight prestasi |
| **Background Main** | `surface-warm` | `#FAF9F7` / `#F8F7F4` | ⚪ Krem Sangat Muda (Off-White) | Latar belakang seluruh halaman & aplikasi |
| **Surface / Card** | `surface-card` | `#FFFFFF` | ⚪ Putih Murni | Latar kartu informasi, kontainer form, modal popup |
| **Surface Subtle** | `surface-muted`| `#F3F1EC` | 🔘 Krem Netral Abu | Latar input form, placeholder box, striping baris |
| **Border / Divider** | `outline-soft` | `#E5E7EB` / `#E2DFD8` | 🔘 Abu-abu Halus | Garis pembatas kartu, separator list, outline tombol |
| **Text Primary** | `text-primary` | `#1F2937` / `#19201D` | ⚫ Abu-abu Gelap (Charcoal) | Judul halaman, label tebal, teks isi utama |
| **Text Secondary** | `text-secondary`| `#6B7280` / `#5C6460` | 🔘 Abu-abu Sedang (Muted) | Deskripsi pendukung, tanggal, sub-teks, placeholder |
| **Text Inverted** | `text-light` | `#FFFFFF` | ⚪ Putih Bersih | Teks di atas tombol hijau tua / badge solid |
| **Status - Sukses** | `status-success`| `#2D6A4F` / `#10B981` | 🟢 Emerald Green | Badge "Hadir", status "Aktif", notifikasi sukses |
| **Status - Peringatan**| `status-warning`| `#F59E0B` / `#D97706` | 🟠 Amber / Orange | Badge "Sakit", status "Pending", perhatian |
| **Status - Izin** | `status-info` | `#3B82F6` / `#2563EB` | 🔵 Biru Lembut | Badge "Izin" pada absensi siswa |
| **Status - Bahaya** | `status-danger` | `#DC2626` / `#EF4444` | 🔴 Merah Tegas | Tombol "Hapus", status "Alfa", nonaktifkan akun |

---

## 🔤 2. Tipografi & Font (Typography System)

### Jenis Huruf (Font Family)
* **Font Utama (System & Modern UI):** `'Inter', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif`
* **Karakter Tipografi:** Bersih, keterbacaan tinggi untuk orang tua murid, proporsional pada mobile & desktop.

| Peran Tipografi | Ukuran (Size) | Ketebalan (Weight) | Line Height | Contoh Penggunaan |
| :--- | :--- | :--- | :--- | :--- |
| **Display / Welcome** | `24px - 28px` (`text-2xl`) | Bold (`700`) | `1.25` | Salam selamat datang di dashboard |
| **Page Title (Header)** | `18px - 20px` (`text-lg` / `xl`) | SemiBold / Bold (`600`/`700`) | `1.3` | Judul layar di app bar (misal: "Absensi Siswa") |
| **Card Header / Subtitle**| `15px - 16px` (`text-base`) | SemiBold (`600`) | `1.4` | Nama siswa di kartu, judul section ("Daftar Siswa") |
| **Body (Teks Utama)** | `14px` (`text-sm`) | Regular / Medium (`400`/`500`)| `1.5` | Deskripsi biodata, teks formulir, label input |
| **Secondary / Meta Text** | `12px - 13px` (`text-xs`) | Regular (`400`) | `1.4` | Tanggal posting berita, nama sekolah pendukung |
| **Button Text** | `14px - 15px` (`text-sm`/`base`)| SemiBold (`600`) | `1.0` | Label tombol "Simpan Perubahan", "Masuk", "Kembali" |
| **Badge / Tag / Nav Label**| `10px - 11px` (`text-[11px]`)| Medium (`500`) | `1.2` | Label icon navigasi bawah (Beranda, Kelas, Tentor) |

---

## 📐 3. Sudut & Bayangan (Border Radius & Elevation)

| Token | Nilai | Penggunaan |
| :--- | :--- | :--- |
| **Radius - Small** | `8px` (`rounded-lg`) | Dropdown pilihan, badge pill kecil, input text ringkas |
| **Radius - Medium** | `12px - 14px` (`rounded-xl`)| Tombol CTA utama, thumbnail foto, pop-up dialog |
| **Radius - Large** | `16px - 20px` (`rounded-2xl`)| Kartu profil siswa, kartu jadwal, container utama |
| **Radius - Full** | `9999px` (`rounded-full`)| Avatar foto bundar, pill kategori ("Semua", "Bakti Sosial") |
| **Elevation - Soft Card** | `0 2px 8px rgba(27,67,50, 0.05)` | Kartu profil siswa dan kartu berita agar tampak mengambang lembut |
| **Elevation - Bottom Nav** | `0 -3px 12px rgba(0,0,0, 0.04)` | Bar navigasi bawah agar terpisah jelas dari konten scroll |

---

## 🧩 4. Panduan Komponen Konsisten (Component Rules)

1. **Header:** Selalu memiliki tombol kembali melingkar di sisi kiri, judul tengah yang tegas, dan background putih bersih/krem.
2. **Bottom Navigation:** Terdiri dari 4 ikon navigasi berjarak seimbang dengan label teks di bawahnya. Ikon aktif menggunakan pill hijau sage dan teks forest green.
3. **Formulir Input:** Menggunakan label jelas di atas kolom, sudut membulat 12px, border halus `#E5E7EB`, dan transisi fokus ke warna `#1B4332`.
4. **Tombol Aksi (Button):** 
   - *Primary:* Background `#1B4332`, teks putih, font semibold, full width di mobile.
   - *Secondary / Batal:* Background transparan/putih dengan border halus `#E5E7EB` atau teks abu-abu.