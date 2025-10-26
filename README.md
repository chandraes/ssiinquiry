# 🔬 SSI Inquiry

Selamat datang di repositori SSI Inquiry! Ini adalah aplikasi web dinamis yang dibangun dengan **Laravel**, **Tailwind CSS**, dan **Alpine.js** yang dirancang untuk mengelola, menganalisis, dan membagikan data. Berdasarkan alur "How it Works" (Eksperimen ➔ Unggah ➔ Putar Video ➔ Kesimpulan), platform ini sangat ideal untuk lingkungan akademik atau penelitian di mana pengguna dapat mengirimkan dan meninjau hasil eksperimen.

<p align="center">
  <a href="https://ssiinquiry.com/" target="_blank">
    <img src="https://image.thum.io/get/width/1200/crop/630/https://ssiinquiry.com/" alt="Pratinjau Live SSI Inquiry">
  </a>
</p>

<p align="center">
  <a href="https://ssiinquiry.com/" target="_blank"><img src="https://img.shields.io/badge/Website-ssiinquiry.com-blue.svg?style=for-the-badge" alt="Live Demo"></a>
  <a href="https://github.com/chandraes/ssiinquiry/blob/main/LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue.svg?style=for-the-badge" alt="License"></a>
  <a href="https://github.com/chandraes/ssiinquiry/issues"><img src="https://img.shields.io/github/issues/chandraes/ssiinquiry?style=for-the-badge" alt="Issues"></a>
  <br>
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=black" alt="Alpine.js">
</p>

---

## 🌟 Fitur Utama

Proyek ini bukan sekadar landing page statis; ini adalah aplikasi Laravel yang lengkap dengan fitur-fitur canggih:

* **🌍 Dukungan Multi-Bahasa:** Antarmuka yang sepenuhnya bilingual (Indonesia 🇮🇩 & Inggris 🇬🇧) dengan *language switcher* dropdown yang elegan menggunakan Alpine.js.
* **⚙️ Konten Dinamis:** Logo dan Favicon tidak di-hardcode. Keduanya diambil secara dinamis dari pengaturan backend (`get_setting('app_logo')`), menyiratkan adanya Panel Admin untuk mengelola situs.
* **🔐 Autentikasi Pengguna:** Sistem autentikasi lengkap dengan halaman `Login` dan `Register` yang sudah siap.
* **📱 Desain Responsif:** Dibuat dengan Tailwind CSS, antarmuka pengguna sepenuhnya responsif dan dilengkapi dengan menu *hamburger* untuk navigasi seluler.
* **🧪 Alur Eksperimen:** Alur pengguna yang jelas dirancang untuk:
    1.  **Melakukan Percobaan** (🧪)
    2.  **Mengunggah Hasil** (⬆️)
    3.  **Memutar Video** (▶️)
    4.  **Menarik Kesimpulan** (💡)
* **🎨 Antarmuka Modern:** UI yang bersih dan modern menggunakan palet warna biru yang konsisten dan font Poppins.

## 🚀 Daftar Isi

* [Fitur Utama](#-fitur-utama)
* [Tumpukan Teknologi (Tech Stack)](#-tumpukan-teknologi-tech-stack)
* [Instalasi & Penyiapan](#%EF%B8%8F-instalasi--penyiapan)
* [Struktur Proyek](#-struktur-proyek)
* [Kontribusi](#-kontribusi)
* [Lisensi](#-lisensi)

## 🛠️ Tumpukan Teknologi (Tech Stack)

* **Backend:** [Laravel](https://laravel.com/) (Framework PHP)
* **Frontend:** [Tailwind CSS](https://tailwindcss.com/), [Alpine.js](https://alpinejs.dev/)
* **Database:** (Dapat dikonfigurasi) MySQL / PostgreSQL / SQLite
* **Server:** (Dapat dikonfigurasi) Nginx / Apache
* **Dependency Manager:** Composer (PHP), NPM/Yarn (JavaScript)

## ⚙️ Instalasi & Penyiapan

Ingin menjalankan SSI Inquiry di mesin lokal Anda? Ikuti langkah-langkah mudah ini.

<details>
<summary><strong>Klik di sini untuk melihat panduan instalasi</strong></summary>

<br>

1.  **Clone Repositori:**
    ```bash
    git clone [https://github.com/chandraes/ssiinquiry.git](https://github.com/chandraes/ssiinquiry.git)
    cd ssiinquiry
    ```

2.  **Instal Dependensi PHP:**
    ```bash
    composer install
    ```

3.  **Instal Dependensi JavaScript:**
    ```bash
    npm install
    ```

4.  **Siapkan File Environment:**
    ```bash
    cp .env.example .env
    ```

5.  **Generate Kunci Aplikasi:**
    ```bash
    php artisan key:generate
    ```

6.  **Konfigurasi `.env` Anda:**
    Buka file `.env` dan atur koneksi database Anda (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD).

7.  **Jalankan Migrasi Database:**
    *(Asumsi Anda memiliki database yang sesuai untuk proyek ini, misal: `ssi_inquiry`)*
    ```bash
    php artisan migrate
    ```
    *(Opsional, jika Anda memiliki Seeder):*
    ```bash
    php artisan migrate --seed
    ```

8.  **Buat Symbolic Link untuk Storage:**
    Ini **sangat penting** agar logo dan favicon yang diunggah dari admin panel dapat diakses secara publik. (Situs live Anda menggunakannya!)
    ```bash
    php artisan storage:link
    ```

9.  **Kompilasi Aset Frontend (jika diperlukan):**
    Meskipun *landing page* menggunakan CDN, bagian *backend* kemungkinan memerlukan kompilasi aset.
    ```bash
    npm run dev
    ```

10. **Jalankan Server Development:**
    ```bash
    php artisan serve
    ```

Selesai! Aplikasi Anda sekarang seharusnya berjalan di `http://127.0.0.1:8000`.

</details>

## 📂 Struktur Proyek

Berikut adalah gambaran singkat tentang beberapa file kunci dalam proyek ini:

 ssiinquiry/

├── app/

│ ├── Http/

│ │ ├── Controllers/

│ │ │ └── LanguageController.php # (Kemungkinan) Mengatur logika pindah bahasa

│ │ └── Helpers/

│ │ └── SettingsHelper.php # (Kemungkinan) Berisi fungsi get_setting()

├── config/

│ └── app.php # Mengatur locale dan fallback_locale

├── resources/

│ ├── lang/

│ │ ├── en/

│ │ │ └── landing.php # File terjemahan Bahasa Inggris

│ │ └── id/

│ │ └── landing.php # File terjemahan Bahasa Indonesia

│ └── views/

│ └── welcome.blade.php # File landing page yang Anda lihat

├── routes/

│ └── web.php # Mendefinisikan route('language.switch', ...)

├── public/

│ ├── storage/ # (Symlink) Tempat logo & favicon disimpan

│ └── assets/ # Aset default

└── ... (berkas dan folder Laravel standar lainnya)

## 🤝 Kontribusi

Kami sangat terbuka untuk kontribusi! Baik itu melaporkan bug, menyarankan fitur baru, atau mengirimkan *Pull Request*, semua bantuan Anda sangat kami hargai.

1.  **Fork** repositori ini.
2.  Buat *branch* fitur baru (`git checkout -b fitur/nama-fitur-keren`).
3.  *Commit* perubahan Anda (`git commit -m 'Menambahkan fitur keren'`).
4.  *Push* ke *branch* Anda (`git push origin fitur/nama-fitur-keren`).
5.  Buka **Pull Request**.

Silakan periksa halaman [Issues](https://github.com/chandraes/ssiinquiry/issues) untuk melihat apa yang perlu dibantu.

## 📜 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).
