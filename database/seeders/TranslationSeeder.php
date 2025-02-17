<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locale_en_id = DB::table('locales')->insertGetId([
            'locale' => 'en',
            'logo' => 'en.png',
            'translations' => 'en',
        ]);

        $locale_kh_id = DB::table('locales')->insertGetId([
            'locale' => 'kh',
            'logo' => 'kh.png',
            'translations' => 'kh',
        ]);

        $translationsEn = [
            'site_name' => 'Dashboard',
            'en' => 'English',
            'kh' => 'Khmer',
            'action' => 'Action',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'email' => 'Email',
            'name' => 'Name',
            'no.' => 'No.',
            'order' => 'Order',
            'password' => 'Password',
            'confirm_password' => 'Confirm Password',
            'remember_me' => 'Remember Me',
            'reset_password' => 'Reset Password',
            'search' => 'Search',
            'show' => 'Show',
            'user' => 'User',
            'username' => 'Username',
            'yes' => 'Yes',

            'name' => 'Name',
            'name_en' => 'Name (English)',
            'name_kh' => 'Name (Khmer)',
            'soft_delete' => 'Delete',
            'please_select' => 'Please Select',
            'deleted' => 'Deleted',
            'all_records' => 'All Records',
            'active' => 'Active',
            'active_yes' => 'Yes',
            'active_no' => 'No',
            'search' => 'Search',
            // modules
            'icon' => 'Icon',
            'module' => 'Module',
            'module_name_en' => 'Module Name (English)',
            'module_name_kh' => 'Module Name (Khmer)',
            'enter_' => 'Enter ',
            'enter_icon' => 'fas fa-folder',

            'table' => 'Table',
            'page' => 'Page',

            // page
            'page_name_en' => 'Page Name (English)',
            'page_name_kh' => 'Page Name (Khmer)',
            // action
            'action_list' => 'តារាងសកម្មភាព',
            'action_name_en' => 'Action Name (Latin)',
            'action_name_kh' => 'Action Name (Khmer)',
            'action_route' => 'Route Name',
            'action_type' => 'Type',
            'action_position' => 'Position',
            'action_icon' => 'Icon',
            'new_action' => 'New Action',
            'delete' => 'Delete',
            'restore' => 'Restore',
            'export' => 'Export',
            'create' => 'Create',
            'edit' => 'Edit',
            'cancel' => 'Cancel',
            'save' => 'Save',
            'submit' => 'Submit',
            'save-new' => 'Save New',
            'save-close' => 'Save Close',
            'close' => 'Close',

            // activity log
            'show_log_detail' => 'Show Log Detail',

            // roles
            'role' => 'Role',
            'role_name_en' => 'Role Name (English)',
            'role_name_kh' => 'Role Name (Khmer)',
            'slug' => 'Slug',
            'full_name' => 'Full Name',
            'username' => 'Username',
            'email' => 'Email',
            'phone' => 'Phone',

            // translation
            'translations' => 'Translation',
            'key' => 'Key',
            'value' => 'Value',
            'value_en' => 'Value (English)',
            'value_kh' => 'Value (Khmer)',
            'attachment' => 'Attachment',
            'attachment_image' => 'Attachment Image',
            'attachment_preview' => 'Attachment Preview',
            // messages
            'delete_success' => 'Delete Success',
            'delete_success_message' => 'Record has been deleted successfully!',
            'delete_error' => 'Delete Error',
            'delete_error_message' => 'Record can not be deleted!',
            'delete_error_dashboard_message' => 'Dashboard can not be deleted!',
            'restore_success' => 'Restore Success',
            'restore_success_message' => 'Record has been restored successfully!',
            'restore_error' => 'Restore Error',
            'restore_error_message' => 'Record can not be restored!',
            'export_success' => 'Export Success',
            'export_success_message' => 'Record has been exported successfully!',
            'export_error' => 'Export Error',
            'export_error_message' => 'Record can not be exported!',
            'create_success' => 'Create Success',
            'create_success_message' => 'Record has been created successfully!',
            'create_error' => 'Create Error',
            'create_error_message' => 'Record can not be created!',
            'edit_success' => 'Edit Success',
            'edit_success_message' => 'Record has been edited successfully!',
            'edit_error' => 'Edit Error',
            'edit_error_message' => 'Record can not be edited!',
            'change_password_success' => 'Change Password Success',
            'change_password_success_message' => 'Password has been changed successfully!',
            'change_password_error' => 'Change Password Error',
            'change_password_error_message' => 'Password can not be changed!',
            'change_password_error_old' => 'Old Password is incorrect!',
            'change_password_error_new' => 'New Password is incorrect!',
            'sync_success' => 'Sync Success',
            'sync_success_message' => 'Record has been synced successfully!',
            'sync_error' => 'Sync Error',
            'sync_error_message' => 'Record can not be synced!',
            'no_role_access' => 'You do not have permission to access this page.',
        ];

        $translationsKh = [
            'site_name' => 'ផ្ទាំងគ្រប់គ្រង',
            'en' => 'អង់គ្លេស',
            'kh' => 'ភាសាខ្មែរ',
            'action' => 'សកម្មភាព',
            'created_at' => 'កាលបរិច្ឆេទបង្កើត',
            'deleted_at' => 'កាលបរិច្ឆេទលុប',
            'email' => 'អាសយដ្ឋាន',
            'name' => 'ឈ្មោះ',
            'no.' => 'ល.រ',
            'order' => 'លំដាប់',
            'password' => 'ពាក្យសម្ងាត់',
            'confirm_password' => 'បញ្ជាក់ពាក្យសម្ងាត់',
            'remember_me' => 'ចងចាំងទៀត',
            'reset_password' => 'កំណត់ពាក្យសម្ងាត់',
            'search' => 'ស្វែងរក',
            'show' => 'បង្ហាញ',
            'user' => 'អ្នកប្រើប្រាស់',
            'username' => 'ឈ្មោះអ្នកប្រើប្រាស់',
            'yes' => 'បាទ/ចាស',

            'name' => 'ឈ្មោះ',
            'name_en' => 'ឈ្មោះឡាតាំង',
            'name_kh' => 'ឈ្មោះខ្មែរ',
            'soft_delete' => 'លុប',
            'please_select' => 'សូមជ្រើសរើស',
            'deleted' => 'បានលុប',
            'all_records' => 'កំណត់ត្រាទាំងអស់',
            'active' => 'ដំណើរការ',
            'active_yes' => 'បាទ/ចាស',
            'active_no' => 'ទេ',
            'search' => 'ស្វែងរក',

            // modules
            'icon' => 'រូបតំណាង',
            'module' => 'ម៉ូឌុល',
            'module_name_en' => 'ឈ្មោះម៉ូឌុល (អង់គ្លេស)',
            'module_name_kh' => 'ឈ្មោះម៉ូឌុល (ភាសាខ្មែរ)',
            'attachment' => 'ឯកសារភ្ជាប់',
            'attachment_image' => 'ភ្ជាប់រូបភាព',
            'attachment_preview' => 'រូបភាពចាស់',
            'enter_' => 'បញ្ចូល',
            'enter_icon' => 'fas fa-folder',

            // translation
            'translations' => 'ការបកប្រែ',
            'key' => 'កូនសោ',
            'value' => 'តម្លៃ',
            'value_en' => 'តម្លៃ (អង់គ្លេស)',
            'value_kh' => 'តម្លៃ (ភាសាខ្មែរ)',

            'table' => 'ក្រាល',
            'page' => 'ទំព័រ',

            // page
            'page_name_en' => 'ឈ្មោះទំព័រឡាតាំង',
            'page_name_kh' => 'ឈ្មោះទំព័រខ្មែរ',

            // action
            'action_list' => 'តារាងសកម្មភាព',
            'action_name_en' => 'ឈ្មោះសកម្មភាពឡាតាំង',
            'action_name_kh' => 'ឈ្មោះសកម្មភាពខ្មែរ',
            'action_route' => 'ឈ្មោះ Route',
            'action_type' => 'ប្រភេទ',
            'action_position' => 'តួនាទី',
            'action_icon' => 'រូបតំណាង',
            'new_action' => 'បង្កើតសកម្មភាព',
            'delete' => 'លុប',
            'restore' => 'ស្ដារការចងចាស់',
            'export' => 'នាំចេញ',
            'create' => 'បង្កើត',
            'edit' => 'កែប្រែ',
            'cancel' => 'បោះបង់',
            'save' => 'រក្សាទុក',
            'submit' => 'ដាក់ស្នើ',
            'save-new' => 'រក្សាទុកនិងបង្កើតថ្មី',
            'save-close' => 'រក្សាទុកបិទ',
            'close' => 'បិទ',

            // activity log
            'show_log_detail' => 'បង្ហាញសេចក្ដីលម្អិត',

            // roles
            'role' => 'តួនាទី',
            'role_name_en' => 'ឈ្មោះតួនាទី (អង់គ្លេស)',
            'role_name_kh' => 'ឈ្មោះតួនាទី (ភាសាខ្មែរ)',
            'slug' => 'Slug',
            'full_name' => 'ឈ្មោះពេញ',
            'username' => 'ឈ្មោះអ្នកប្រើប្រាស់',
            'email' => 'អ៊ីមែល',
            'phone' => 'លេខទូរស័ព្ទ',

            // messages
            'delete_success' => 'បានលុបចោល',
            'delete_success_message' => 'ការលុបចោលទំព័រដោយជោគជ័យចង់ពីត្រូវបានទុកហើយយ៉ាង។',
            'delete_error' => 'កំហុសលុបចោល',
            'delete_error_message' => 'ការលុបចោលទំព័រមិនត្រូវបានទុកហើយយ៉ាង។',
            'delete_error_dashboard_message' => 'ផ្ទៃការមិនអាចលុបចោលបានទេ!',
            'restore_success' => 'បានស្ដារការចងចាស់',
            'restore_success_message' => 'ការចងចាស់ទំព័រដោយជោគជ័យចង់ពីត្រូវបានទុកហើយយ៉ាង។',
            'restore_error' => 'កំហុសចងចាស់',
            'restore_error_message' => 'ការចងចាស់ទំព័រមិនត្រូវបានទុកហើយយ៉ាង។',
            'export_success' => 'បាននាំចេញចោល',
            'export_success_message' => 'ការនាំចេញចោលទំព័រដោយជោគជ័យចង់ពីត្រូវបានទុកហើយយ៉ាង។',
            'export_error' => 'កំហុសនាំចេញចោល',
            'create_success' => 'បានបង្កើតចោល',
            'create_success_message' => 'ការបង្កើតចោលទំព័រដោយជោគជ័យចង់ពីត្រូវបានទុកហើយយ៉ាង។',
            'create_error' => 'កំហុសបង្កើតចោល',
            'create_error_message' => 'ការបង្កើតចោលទំព័រមិនត្រូវបានទុកហើយយ៉ាង។',
            'edit_success' => 'បានកែប្រែចោល',
            'edit_success_message' => 'ការកែប្រែចោលទំព័រដោយជោគជ័យចង់ពីត្រូវបានទុកហើយយ៉ាង។',
            'edit_error' => 'កំហុសកែប្រែចោល',
            'edit_error_message' => 'ការកែប្រែចោលទំព័រមិនត្រូវបានទុកហើយយ៉ាង។',
            'change_password_success' => 'បានផ្លាស់ប្តូពាក្យសម្ងាត់ចោល',
            'change_password_success_message' => 'ពាក្យសម្ងាត់ដោយជោគជ័យចង់ពីត្រូវបានទុកហើយយ៉ាង។',
            'change_password_error' => 'កំហុសផ្លាស់ប្តូពាក្យសម្ងាត់',
            'change_password_error_message' => 'ពាក្យសម្ងាត់មិនត្រូវបានទុកហើយយ៉ាង។',
            'change_password_error_old' => 'ពាក្យសម្ងាត់ចាស់សម្ងាត់មិនត្រឹមត្រូវបានទុកហើយយ៉ាង។',
            'change_password_error_new' => 'ពាក្យសម្ងាត់ចាស់សម្ងាត់មិនត្រឹមត្រូវបានទុកហើយយ៉ាង។',
            'sync_success' => 'បានបង្កើតចោល',
            'sync_success_message' => 'ការបង្កើតចោលទំព័រដោយជោគជ័យចង់ពីត្រូវបានទុកហើយយ៉ាង។',
            'sync_error' => 'កំហុសបង្កើតចោល',
            'sync_error_message' => 'ការបង្កើតចោលទំព័រមិនត្រូវបានទុកហើយយ៉ាង។',
            'no_role_access' => 'អ្នកមិនមានសិទ្ធិចូលប្រើទំព័រនេះទេ។.',
        ];

        foreach ($translationsEn as $key => $value) {
            DB::table('translations')->updateOrInsert(
                ['locale_id' => $locale_en_id, 'key' => $key],
                ['value' => $value]
            );
        }

        foreach ($translationsKh as $key => $value) {
            DB::table('translations')->updateOrInsert(
                ['locale_id' => $locale_kh_id, 'key' => $key],
                ['value' => $value]
            );
        }
    }
}
