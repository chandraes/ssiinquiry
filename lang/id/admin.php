<?php

return [
    'loader_alt' => 'Pemuat',
    'logo_alt' => 'Logo',

    'footer_copyright' => 'Hak Cipta © :year oleh <a href="javascript:void(0);"> SSI Inquiry </a> Semua hak dilindungi',

    'sidebar' => [
        'main' => 'Utama',
        'management' => 'Manajemen',
        'akun' => 'Akun',
        'settings' => 'Pengaturan',
        'dashboard' => 'Dasbor',
        'management_class' => 'Manajemen Kelas',
        'management_module' => 'Manajemen Modul',
        'user' => 'Pengguna',
        'my_profile' => 'Profil Saya',
        'application' => 'Aplikasi',
        'students'=>[
            'learning'=>'Pembelajaran',
            'my_class'=>'Kelas Saya'
        ]
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
        'header' => 'Modul dan Kelas',
        'module_title' => 'Modul',
        'add_modules' => 'Tambah Modul',
        'no_modules' => 'Tidak ada modul tersedia. Silakan tambahkan modul baru.',
        'add_class' => 'Tambah Kelas',
        'description' => 'Deskripsi',
        'tools' => 'Alat',
        'no_phyphox' => 'Tidak ada alat Phyphox terkait',
        'no_classes' =>  'Belum ada kelas untuk modul ini',
        'list_class' => 'Daftar Kelas',
        'total_participants' => 'Total Peserta',
        'students'=>[
            'title' => 'Dasbor Siswa',
            'header' => 'Dasbor',
            'welcome' => 'Selamat Datang',
            'choose_class' => 'Pilih kelas di bawah ini untuk memulai pembelajaran',
            'my_class' => 'Kelas Saya',
            'class_name' => 'Nama Kelas',
            'enter_class' => 'Masuk Kelas',
            'join_class' => 'Masuk Kelas',
            'unregistered_student' => 'Anda belum terdaftar di kelas manapun',
            'explore_modules' => 'Jelajahi Modul Lain',
            'no_modules_available' => 'Tidak ada modul lain yang tersedia saat ini',
        ]
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
        'add_button' => 'Tambah Kelas',
        'table_no' => 'No',
        'table_module' => 'Modul',
        'table_class' => 'Kelas',
        'table_participants' => 'Peserta',
        'table_teacher' => 'Guru Pengajar',
        'table_action' => 'Aksi',
        'add_participant_title' => 'Tambah Peserta',
        'add_participant_text' => 'Tambah Peserta',
        'view_participant_title' => 'Lihat Peserta',
        'edit_title' => 'Edit Kelas',
        'delete_title' => 'Hapus Kelas',
        'show'=>[
            'header' => 'Detail Kelas',
            'module' => 'Modul',
            'teacher' => 'Guru Pengajar',
            'num_participants' => 'Jumlah Peserta',
            'resume_management' => 'Ringkasan & Manajemen',
            'gradebook' => 'Buku Nilai',
            'join_code' => 'Kode Gabung Kelas',
            'instruction_join_code' => 'Bagikan kode ini kepada siswa untuk bergabung ke kelas ini.',
            'copy' => 'Salin',
            
            //PARTICIPANT MANAGEMENT
            'manage_participants' => 'Manajemen Peserta',
            'add_delete_participants' => 'Tambah/Hapus Peserta',
            'button_manage_participant' => 'Kelola Peserta',

            //FORUM MANAGEMENT
            'manage_forum' => 'Manajemen Forum',
            'setup_teams' => 'Kelola Tim Pro dan Kontra untuk Kelas Ini',
            'button_manage_forum' => 'Kelola Forum',
            //TABLE
            'participant_table_name' => 'Nama Peserta',
            'participant_table_score' => 'Total Skor',
            'participant_table_point' => 'Point',
            'participant_table_grade' => 'Beri Nilai',
            'participant_table_finish' => 'Selesai',
            'no_participants' => 'Belum ada peserta di kelas ini.',
        ],
        'create'=>[
            'header' => 'Tambah Kelas Baru',
            'module' => 'Modul',
            'choose_module' => 'Pilih Modul',
            'class_name' => 'Nama Kelas',
            'teacher' => 'Guru Pengajar',
        ],
        'peserta' => [
            'title' => 'Peserta Kelas',
            'header' => 'Daftar Peserta Kelas',
            'add_participant' => 'Tambah Peserta',
            'no_participants' => 'Belum ada peserta di kelas ini.',
            'join_class' => 'Gabung Kelas',
            'table_name' => 'Nama Peserta',
            'table_action' => 'Aksi',
            'remove_title' => 'Hapus Peserta',
            'create' => [
                'header' => 'Tambah Peserta Kelas',
                'choose_participant' => 'Pilih Peserta',
            ],
            'join'=>[
                'header'=>'Gabung Kelas',
                'class' => 'Anda akan bergabung ke Kelas',
                'instruction'=>'Masukkan Kode Kelas (Kode Join)',
                'placeholder'=>'Contoh: KLS1234',
                'join_button'=>'Gabung Kelas',
            ],
            'swal' => [
                'create' => [
                    'title' => 'Simpan Data?',
                    'text' => 'Pastikan data peserta sudah benar!',
                    'confirm' => 'Ya, Simpan!',
                    'cancel' => 'Batal',
                ],
                'delete' => [
                    'title' => 'Hapus Peserta?',
                    'text' => 'Peserta akan dihapus dari kelas ini!',
                    'confirm' => 'Ya, Hapus!',
                    'cancel' => 'Batal',
                ],
            ],
        ]
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
        'edit_title' => 'Ubah Materi',
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

    'practicum' => [
        'title' => 'Pengaturan Praktikum',
        'instructions' => 'Petunjuk Praktikum',
        'instructions_desc' => 'Ini adalah petunjuk yang akan dilihat siswa (Pendahuluan, Tujuan, Bagian A, B, C). Anda hanya dapat memiliki SATU petunjuk per sub-modul.',
        'add_instruction' => 'Buat Petunjuk',
        'edit_instruction' => 'Ubah Petunjuk',
        'upload_slots' => 'Slot Unggahan Data (Bagian D)',
        'upload_slots_desc' => 'Ini adalah form yang akan diisi siswa. Buat satu slot untuk setiap file CSV yang harus mereka unggah.',
        'add_slot' => 'Tambah Slot Unggahan',
        'slot_label' => 'Label Slot',
        'slot_desc' => 'Deskripsi/Nama File',
        'slot_group' => 'Grup Eksperimen',
    ],

    'forum' => [ 
        'title' => 'Manajemen Forum',
        'sub_title' => 'Manajemen Tim Forum',
        'class' => 'Kelas',
        'instruction' => 'Pilih salah satu sub-modul forum di bawah ini untuk memulai pengaturan tim Pro dan Kontra untuk kelas ini.',
        'forum_available' => 'Forum Tersedia dari Modul:',
        'set_team' => 'Atur Tim',
        'no_submodul' => 'Tidak ada sub-modul forum yang ditemukan untuk modul ini.',
    ],

    'forum_settings' => [
        // 'title' => 'Pengaturan Forum Debat',
        'title' => 'Atur Tim : ',
        'setting_team_for_class' => 'Anda sedang mengatur tim untuk Kelas: ',
        'back_text' => 'Kembali ke Daftar Forum',
        'general_settings' => 'Pengaturan Umum Forum',
        'update_settings' => 'Simpan Pengaturan',
        'debate_topic' => 'Topik Debat',
        'debate_rules' => 'Aturan Debat',
        'start_time' => 'Waktu Mulai',
        'end_time' => 'Waktu Selesai',
        'phase1_end' => 'Akhir Fase 1 (Pembukaan)',
        'phase2_end' => 'Akhir Fase 2 (Sanggahan)',
        'team_management' => 'Manajemen Tim',
        'team_pro' => 'Tim Pro',
        'team_con' => 'Tim Kontra',
        'unassigned_students' => 'Siswa Belum Ditugaskan',
        'assign_pro' => 'Jadikan Pro',
        'assign_con' => 'Jadikan Kontra',
        'remove_from_team' => 'Keluarkan dari Tim',
        'no_students_in_class' => 'Tidak ada siswa di kelas terkait.', // Sesuaikan pesan ini
        'no_members' => 'Belum ada anggota.',
    ],

    'settings' => [
        'title' => 'Pengaturan',
        'application' => 'Pengaturan Aplikasi',
        'update_settings' => 'Perbarui Pengaturan',
        'app_name' => 'Nama Aplikasi',
        'app_email' => 'Email Aplikasi',
        'app_logo' => 'Logo Aplikasi',
        'app_logo_help' => 'Ukuran yang disarankan: 200x50 piksel. Ukuran maksimal: 2MB.',
        'app_icon_help' => 'Ukuran maksimal: 50KB.',
        'save_settings' => 'Simpan Pengaturan',
        'swal' => [
            'text' => 'Yakin ingin menyimpan pengaturan?',
            'default' => 'Seret dan lepas file di sini atau klik',
            'replace' => 'Seret dan lepas atau klik untuk mengganti',
            'remove' =>  'Hapus',
            'error' =>   'Terjadi kesalahan.',
        ],
    ],

    'button'=>[
        'save'=>'Simpan',
        'close'=>'Tutup',
        'cancel'=>'Batal',
        'forward'=>'Lanjutkan',
    ]
];
