<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configurations = [
            // String values
            ['key' => 'datatable_card', 'value' => 'table text-nowrap datatables no-footer', 'datatype' => 'string'],
            ['key' => 'date_format', 'value' => 'd-M-Y', 'datatype' => 'string'],
            ['key' => 'time_format', 'value' => 'H:i A', 'datatype' => 'string'],
            ['key' => 'datetime_format', 'value' => 'd-M-Y H:i A', 'datatype' => 'string'],
            ['key' => 'datetime_format_24h', 'value' => 'd-M-Y H:i:s', 'datatype' => 'string'],
            ['key' => 'time_start', 'value' => '00:00:00', 'datatype' => 'string'],
            ['key' => 'time_end', 'value' => '23:59:59', 'datatype' => 'string'],
            ['key' => 'disk_name', 'value' => 'public_upload', 'datatype' => 'string'],
            ['key' => 'upload_path', 'value' => 'uploads/', 'datatype' => 'string'],
            ['key' => 'upload_lang_path', 'value' => '/lang', 'datatype' => 'string'],
            ['key' => 'image_default_value', 'value' => 'no-avatar.png', 'datatype' => 'string'],
            ['key' => 'form_group_class', 'value' => 'form-group row align-items-center', 'datatype' => 'string'],
            ['key' => 'form_label_class', 'value' => 'col-sm-2 col-form-label text-end', 'datatype' => 'string'],
            ['key' => 'form_div_input_class', 'value' => 'col-sm-10', 'datatype' => 'string'],
            ['key' => 'form_input_class', 'value' => 'form-control', 'datatype' => 'string'],
            ['key' => 'button_search_class', 'value' => 'btn btn-primary', 'datatype' => 'string'],
            ['key' => 'button_back_class', 'value' => 'btn btn-danger', 'datatype' => 'string'],
            ['key' => 'button_save_class', 'value' => 'btn btn-primary', 'datatype' => 'string'],
            ['key' => 'button_save_new_class', 'value' => 'btn btn-info', 'datatype' => 'string'],
            ['key' => 'icon_save', 'value' => 'fas fa-save', 'datatype' => 'string'],
            ['key' => 'icon_save_new', 'value' => 'fas fa-save', 'datatype' => 'string'],
            ['key' => 'icon_back', 'value' => 'fas fa-arrow-left', 'datatype' => 'string'],
            ['key' => 'badge_count', 'value' => 'badge badge-count', 'datatype' => 'string'],
            ['key' => 'badge_success', 'value' => 'badge badge-success', 'datatype' => 'string'],
            ['key' => 'badge_danger', 'value' => 'badge badge-danger', 'datatype' => 'string'],
            ['key' => 'badge_warning', 'value' => 'badge badge-warning', 'datatype' => 'string'],
            ['key' => 'badge_info', 'value' => 'badge badge-info', 'datatype' => 'string'],
            ['key' => 'badge_primary', 'value' => 'badge badge-primary', 'datatype' => 'string'],
            ['key' => 'badge_default', 'value' => 'badge badge-black', 'datatype' => 'string'],
            // Number values
            ['key' => 'upload_max_size', 'value' => (1024 * 1024 * 5), 'datatype' => 'number'],
            ['key' => 'upload_width', 'value' => 200, 'datatype' => 'number'],
            ['key' => 'upload_height', 'value' => 200, 'datatype' => 'number'],
            ['key' => 'lang_width', 'value' => 256, 'datatype' => 'number'],
            ['key' => 'lang_height', 'value' => 164, 'datatype' => 'number'],
            ['key' => 'lang_quality', 'value' => 100, 'datatype' => 'number'],
            ['key' => 'avatar_width', 'value' => 1024, 'datatype' => 'number'],
            ['key' => 'avatar_height', 'value' => 1024, 'datatype' => 'number'],
            ['key' => 'avatar_quality', 'value' => 100, 'datatype' => 'number'],
        ];

        foreach ($configurations as $config) {
            DB::table('configurations')->updateOrInsert(
                ['key' => $config['key']],
                ['value' => $config['value'], 'datatype' => $config['datatype']]
            );
        }
    }
}
