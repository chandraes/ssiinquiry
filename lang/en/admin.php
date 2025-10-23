<?php

return [
    'loader_alt' => 'Loader',
    'logo_alt' => 'Logo',

    'footer_copyright' => 'Copyright © :year by <a href="javascript:void(0);"> SSI Inquiry </a> All rights reserved',

    'sidebar' => [
        'main' => 'Main',
        'dashboard' => 'Dashboard',
        'user' => 'User',
        'modules_classes' => 'Modules & Classes',
        'no_class' => 'No Class Yet',
        'settings' => 'Settings',
        'application' => 'Application',
    ],

    'lang' => [
        'title' => 'Language',
        'id' => 'Indonesia',
        'en' => 'English',
    ],

    'profile' => [
        'alt' => 'Profile Picture',
        'title' => 'Profile',
        'settings' => 'Settings',
        'sign_out' => 'Sign out',
    ],

    'dashboard' => [
        'title' => 'Dashboard',
        'header' => 'Dashboard',
        'module_title' => 'Module',
        'create_module' => 'Create New Module',
        'total_modules' => 'Total Modules',
        'total_owners' => 'Total Owners',
        'class_title' => 'Class',
        'create_class' => 'Create New Class',
        'total_classes' => 'Total Classes',
        'total_participants' => 'Total Participants',
    ],

    'placeholders' => [
        'select_phyphox' => 'Select Measuring Tool...',
        'select_owner' => 'Select owner...',
        'select_teacher' => 'Select Teacher...',
    ],

    'swal' => [
        'save_title' => 'Save Data?',
        'save_text' => 'Please ensure all data is correct!',
        'save_confirm' => 'Yes, Save!',
        'cancel' => 'Cancel',
        'update_title' => 'Save Changes?',
        'update_text' => 'Are you sure you want to save the changes for this class?',
        'update_confirm' => 'Yes, Save',
        'delete_title' => 'Delete Data?',
        'delete_text' => 'Are you sure you want to delete this data?',
        'delete_confirm' => 'Continue',
    ],

    'kelas_modal' => [
        'add_title' => 'Add Class Data',
        'edit_title' => 'Edit Class Data',
        'select_module' => 'Select Module',
        'module_placeholder' => '-- Select Module --',
        'class_name_label' => 'Class Name', // General label
        'class_name_id' => 'Class Name (ID)', // Label in tab
        'class_name_en' => 'Class Name (EN)', // Label in tab
        'select_teacher' => 'Select Teacher',
        'close' => 'Close',
        'save' => 'Save',
        'save_changes' => 'Save Changes',
    ],

    'kelas' => [
        'title' => 'Class',
        'list_title' => 'Class List',
        'add_button' => 'Add Data',
        'table_no' => 'No',
        'table_module' => 'Module',
        'table_class' => 'Class',
        'table_participants' => 'Participants',
        'table_teacher' => 'Teacher',
        'table_action' => 'Action',
        'add_participant_title' => 'Add Participants',
        'add_participant_text' => 'Add Participants',
        'view_participant_title' => 'View Participants',
        'edit_title' => 'Edit Data',
        'delete_title' => 'Delete Data',
    ],

    'modul_detail' => [
        'title' => 'Module Detail',
        'module_info' => 'Module Information',
        'submodule_list' => 'Sub Module List',
        'add_submodule' => 'Add Sub Module',
        'no_submodule' => 'No sub modules found for this module.',
        'edit' => 'Edit',
        'delete' => 'Delete',
    ],

    'submodul_modal' => [
        'add_title' => 'Add New Sub Module',
        'edit_title' => 'Edit Sub Module',
        'tab_id' => 'Indonesia (ID)',
        'tab_en' => 'English (EN)',
        'title_id_label' => 'Sub Module Title (ID)',
        'title_en_label' => 'Sub Module Title (EN)',
        'desc_id_label' => 'Description (ID)',
        'desc_en_label' => 'Description (EN)',
        'order_label' => 'Order Number',
        'order_help' => 'To sort the sub modules.',
        'close' => 'Close',
        'save' => 'Save',
        'save_changes' => 'Save Changes',
    ],

    'modul_list' => [
        'page_title' => 'Module Management',
        'card_title' => 'Module List',
        'add_new' => 'Create New Module',
        'view_details' => 'View Details',
        'no_modules' => 'No modules have been created yet.',
        'edit' => 'Edit',
        'delete' => 'Delete',
    ],

   'material_modal' => [
        'add_video' => 'Tambah Materi Video',
        'add_article' => 'Tambah Materi Artikel',
        'add_infographic' => 'Tambah Infografis (Gambar)',
        'edit_title' => 'Change Material',
        'add_regulation' => 'Tambah Regulasi (PDF)',

        // Key generik baru
        'url_label' => 'URL Materi',
        'url_placeholder' => 'https://example.com/...',

        'add_rich_text' => 'Add Rich Text Material', // <-- NEW

        // ... (key url_label)
        'rich_text_content_id' => 'Text Content (ID)', // <-- NEW
        'rich_text_content_en' => 'Text Content (EN)',
    ],

    'reflection_modal' => [
        'add_title' => 'Add Reflection Question',
        'edit_title' => 'Edit Reflection Question',
        'question_text_id' => 'Question Text (ID)',
        'question_text_en' => 'Question Text (EN)',
        'order_label' => 'Order Number',
    ],

    'practicum' => [
        'title' => 'Practicum Setup',
        'instructions' => 'Practicum Instructions',
        'instructions_desc' => 'These are the instructions students will see (Intro, Objectives, Part A, B, C). You can only have ONE instruction set per sub-module.',
        'add_instruction' => 'Create Instructions',
        'edit_instruction' => 'Edit Instructions',
        'upload_slots' => 'Data Upload Slots (Part D)',
        'upload_slots_desc' => 'This is the form students will fill. Create one slot for each CSV file they must upload.',
        'add_slot' => 'Add Upload Slot',
        'slot_label' => 'Slot Label',
        'slot_desc' => 'Description/Filename',
        'slot_group' => 'Experiment Group',
    ],

    'forum_settings' => [
        'title' => 'Debate Forum Settings',
        'general_settings' => 'General Forum Settings',
        'update_settings' => 'Save Settings',
        'debate_topic' => 'Debate Topic',
        'debate_rules' => 'Debate Rules',
        'start_time' => 'Start Time',
        'end_time' => 'End Time',
        'phase1_end' => 'End of Phase 1 (Opening)',
        'phase2_end' => 'End of Phase 2 (Rebuttals)',
        'team_management' => 'Team Management',
        'team_pro' => 'Pro Team',
        'team_con' => 'Con Team',
        'unassigned_students' => 'Unassigned Students',
        'assign_pro' => 'Assign to Pro',
        'assign_con' => 'Assign to Con',
        'remove_from_team' => 'Remove from Team',
        'no_students_in_class' => 'No students in related classes.', // Adjust message
        'no_members' => 'No members yet.',
    ],
];
