<?php

return [
    'loader_alt' => 'Loader',
    'logo_alt' => 'Logo',

    'footer_copyright' => 'Copyright © :year by <a href="javascript:void(0);"> SSI Inquiry </a> All rights reserved',

    'sidebar' => [
        'main' => 'Main',
        'management' => 'Management',
        'account' => 'Account',
        'settings' => 'Settings',
        'dashboard' => 'Dashboard',
        'management_class' => 'Class Management',
        'management_module' => 'Module Management',
        'user' => 'User',
        'my_profile' => 'My Profile',
        'application' => 'Application',
        'students' =>[
            'learning' => 'Learning',
            'my_class' => 'My Class'
        ]
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
        'header' => 'Modules and Classes',
        'module_title' => 'Modules',
        'add_modules' => 'Add Modules',
        'no_modules' => 'No modules available. Please add a new module.',
        'add_class' => 'Add Class',
        'description' => 'Description',
        'tools' => 'Tools',
        'no_phyphox' => 'No associated Phyphox tools',
        'no_classes' => 'There are no classes for this module yet',
        'list_class' => 'List of Classes',
        'total_participants' => 'Total Participants',
        'students' => [
            'title' => 'Student Dashboard',
            'header' => 'Dashboard',
            'welcome' => 'Welcome',
            'choose_class' => 'Choose a class below to start learning',
            'my_class' => 'My Class',
            'class_name' => 'Class Name',
            'enter_class' => 'Enter Class',
            'join_class' => 'Join Class',
            'unregistered_student' => 'You are not yet enrolled in any classes',
            'explore_modules' => 'Explore Other Modules',
            'no_modules_available' => 'No other modules are currently available',
        ]
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
        'add_button' => 'Add Class',
        'table_no' => 'No',
        'table_module' => 'Module',
        'table_class' => 'Class',
        'table_participants' => 'Participants',
        'table_teacher' => 'Instructor',
        'table_action' => 'Action',
        'add_participant_title' => 'Add Participant',
        'add_participant_text' => 'Add Participant',
        'view_participant_title' => 'View Participants',
        'edit_title' => 'Edit Class',
        'delete_title' => 'Delete Class',
        'show'=>[
            'header' => 'Class Details',
            'module' => 'Module',
            'teacher' => 'Teacher',
            'num_participants' => 'Number of Participants',
            'resume_management' => 'Summary & Management',
            'gradebook' => 'Gradebook',
            'join_code' => 'Class Join Code',
            'instruction_join_code' => 'Share this code with students to join this class.',
            'copy' => 'Copy',

            //PARTICIPANT MANAGEMENT
            'manage_participants' => 'Participant Management',
            'add_delete_participants' => 'Add/Delete Participants',
            'button_manage_participant' => 'Manage Participants',

            //FORUM MANAGEMENT
            'manage_forum' => 'Forum Management',
            'setup_teams' => 'Manage Pro and Con Teams for This Class',
            'button_manage_forum' => 'Manage Forum',
            //TABLE
            'participant_table_name' => 'Participant Name',
            'participant_table_score' => 'Total Score',
            'participant_table_point' => 'Points',
            'participant_table_grade' => 'Give Grade',
            'participant_table_finish' => 'Finish',
            'no_participants' => 'There are no participants in this class yet.',
        ],
        'create' => [
            'header' => 'Add New Class',
            'module' => 'Module',
            'choose_module' => 'Select Module',
            'class_name' => 'Class Name',
            'teacher' => 'Instructor',
        ],
        'peserta' => [
            'title' => 'Class Participants',
            'header' => 'Class Participant List',
            'add_participant' => 'Add Participant',
            'no_participants' => 'No participants have joined this class yet.',
            'join_class' => 'Join Class',
            'table_name' => 'Participant Name',
            'table_action' => 'Action',
            'remove_title' => 'Remove Participant',
            'create' => [
                'header' => 'Add Class Participant',
                'choose_participant' => 'Select Participant',
            ],
            'join' => [
                'header' => 'Join Class',
                'class' => 'You are about to join the class',
                'instruction' => 'Enter Class Code (Join Code)',
                'placeholder' => 'Example: CLS1234',
                'join_button' => 'Join Class',
            ],
            'swal' => [
                'create' => [
                    'title' => 'Save Data?',
                    'text' => 'Make sure the participant data is correct!',
                    'confirm' => 'Yes, Save!',
                    'cancel' => 'Cancel',
                ],
                'delete' => [
                    'title' => 'Remove Participant?',
                    'text' => 'The participant will be removed from this class!',
                    'confirm' => 'Yes, Remove!',
                    'cancel' => 'Cancel',
                ],
            ],
        ],
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

    'forum' => [ 
        'title' => 'Forum Management',
        'sub_title' => 'Forum Team Management',
        'class' => 'Class',
        'instruction' => 'Select one of the forum submodules below to start setting up the Pro and Contra teams for this class.',
        'forum_available' => 'Available Forums from Module:',
        'set_team' => 'Set Team',
        'no_submodul' => 'No forum submodules found for this module.',
    ],

    'forum_settings' => [
        // 'title' => 'Debate Forum Settings',
        'title' => 'Set Team: ',
        'setting_team_for_class' => 'You are setting up a team for Class: ',
        'back_text' => 'Back to Forum List',
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

    'settings' => [
        'title' => 'Settings',
        'application' => 'Application Settings',
        'update_settings' => 'Update Settings',
        'app_name' => 'Application Name',
        'app_email' => 'Application Email',
        'app_logo' => 'Application Logo',
        'app_logo_help' => 'Recommended size: 200x50 pixels. Max size: 2MB.',
        'app_icon_help' => 'Max size: 50KB.',
        'save_settings' => 'Save Settings',
        'swal' => [
            'text' => 'Are you sure you want to save the settings?',
            'default' => 'Drag and drop files here or click',
            'replace' => 'Drag and drop or click to replace',
            'remove' => 'Delete',
            'error' => 'An error occurred.',
        ],
    ],

    'button'=>[
        'save'=>'Save',
        'close'=>'Close',
        'cancel'=>'Cancel',
        'forward'=>'Forward',
    ]
];
