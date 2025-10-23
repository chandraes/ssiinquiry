<?php

return [
    'loader_alt' => 'Pemuat',
    'logo_alt' => 'Logo',

    'footer_copyright' => 'Hak Cipta © :year oleh <a href="javascript:void(0);"> SSI Inquiry </a> Semua hak dilindungi',

    'sidebar' => [
        'main' => 'Utama',
        'dashboard' => 'Dasbor',
        'user' => 'Pengguna',
        'modules_classes' => 'Modul dan Kelas',
        'no_class' => 'Belum Ada Kelas',
        'settings' => 'Pengaturan',
        'application' => 'Aplikasi',
    ],

    'lang' => [
        'title' => 'Bahasa',
        'id' => 'Indonesia',
        'en' => 'English',
    ],

    'profile' => [
        'alt' => 'Foto Profil',
        'title' => 'Profil',
        'settings' => 'Pengaturan',
        'sign_out' => 'Keluar',
    ],

    'dashboard' => [
        'title' => 'Dasbor',
        'header' => 'Dasbor',
        'module_title' => 'Modul',
        'create_module' => 'Buat Modul Baru',
        'total_modules' => 'Total Modul',
        'total_owners' => 'Total Owner',
        'class_title' => 'Kelas',
        'create_class' => 'Buat Kelas Baru',
        'total_classes' => 'Total Kelas',
        'total_participants' => 'Total Peserta',
    ],

    'placeholders' => [
        'select_phyphox' => 'Pilih Alat Ukur...',
        'select_owner' => 'Pilih owner...',
        'select_teacher' => 'Pilih Guru Pengajar...',
    ],

    'swal' => [
        'save_title' => 'Simpan Data?',
        'save_text' => 'Pastikan semua data sudah benar!',
        'save_confirm' => 'Ya, Simpan!',
        'cancel' => 'Batal',
        'update_title' => 'Simpan Perubahan?',
        'update_text' => 'Apakah Anda yakin ingin menyimpan perubahan pada kelas ini?',
        'update_confirm' => 'Ya, Simpan',
        'delete_title' => 'Hapus Data?',
        'delete_text' => 'Apakah anda yakin ingin menghapus data?',
        'delete_confirm' => 'Lanjutkan',
    ],

    'kelas_modal' => [
        'add_title' => 'Tambah Data Kelas',
        'edit_title' => 'Ubah Data Kelas',
        'select_module' => 'Pilih Modul',
        'module_placeholder' => '-- Pilih Modul --',
        'class_name_label' => 'Nama Kelas', // Label umum
        'class_name_id' => 'Nama Kelas (ID)', // Label di tab
        'class_name_en' => 'Class Name (EN)', // Label di tab
        'select_teacher' => 'Pilih Guru Pengajar',
        'close' => 'Tutup',
        'save' => 'Simpan',
        'save_changes' => 'Simpan Perubahan',
    ],

    'kelas' => [
        'title' => 'Kelas',
        'list_title' => 'Daftar Kelas',
        'add_button' => 'Tambah Data',
        'table_no' => 'No',
        'table_module' => 'Modul',
        'table_class' => 'Kelas',
        'table_participants' => 'Peserta',
        'table_teacher' => 'Guru Pengajar',
        'table_action' => 'Aksi',
        'add_participant_title' => 'Tambah Peserta',
        'add_participant_text' => 'Tambah Peserta',
        'view_participant_title' => 'Lihat Peserta',
        'edit_title' => 'Edit Data',
        'delete_title' => 'Hapus Data',
    ],

    'modul_detail' => [
        'title' => 'Detail Modul',
        'module_info' => 'Informasi Modul',
        'submodule_list' => 'Daftar Sub Modul',
        'add_submodule' => 'Tambah Sub Modul',
        'no_submodule' => 'Belum ada sub modul untuk modul ini.',
        'edit' => 'Ubah',
        'delete' => 'Hapus',
    ],

    'submodul_modal' => [
        'add_title' => 'Tambah Sub Modul Baru',
        'edit_title' => 'Ubah Sub Modul',
        'tab_id' => 'Indonesia (ID)',
        'tab_en' => 'English (EN)',
        'title_id_label' => 'Judul Sub Modul (ID)',
        'title_en_label' => 'Sub Module Title (EN)',
        'desc_id_label' => 'Deskripsi (ID)',
        'desc_en_label' => 'Description (EN)',
        'order_label' => 'Nomor Urut',
        'order_help' => 'Untuk mengurutkan sub modul.',
        'close' => 'Tutup',
        'save' => 'Simpan',
        'save_changes' => 'Simpan Perubahan',
    ],

    'modul_list' => [
        'page_title' => 'Manajemen Modul',
        'card_title' => 'Daftar Modul',
        'add_new' => 'Buat Modul Baru',
        'view_details' => 'Lihat Detail',
        'no_modules' => 'Belum ada modul yang dibuat.',
        'edit' => 'Ubah',
        'delete' => 'Hapus',
    ],

   'material_modal' => [
        'add_video' => 'Add Video Material',
        'add_article' => 'Add Article Material',
        'add_infographic' => 'Add Infographic (Image)',
        'add_regulation' => 'Add Regulation (PDF)',

        // New generic key
        'url_label' => 'Material URL',
        'url_placeholder' => 'https://example.com/...',

        'add_rich_text' => 'Tambah Materi Teks (Rich Text)', // <-- BARU

        // ... (key url_label)
        'rich_text_content_id' => 'Konten Teks (ID)', // <-- BARU
        'rich_text_content_en' => 'Text Content (EN)',
    ],

    'reflection_modal' => [
        'add_title' => 'Tambah Pertanyaan Refleksi',
        'edit_title' => 'Ubah Pertanyaan Refleksi',
        'question_text_id' => 'Teks Pertanyaan (ID)',
        'question_text_en' => 'Question Text (EN)',
        'order_label' => 'Nomor Urut',
    ],
];
