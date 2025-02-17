<?php

use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Modules\Settings\App\Models\Locale;

if (! function_exists('custom_asset')) {
    function custom_asset($p)
    {
        return asset('assets/'.ltrim($p, '/'));
    }
}
// if (!function_exists('custom_asset')) {
//     function custom_asset($p) {
//         return secure_url('assets/' . ltrim($p, '/'));
//     }
// }
if (! function_exists('notify')) {
    function notify($icon, $title, $message, $type, $delay = null)
    {
        session()->flash('notify', [
            'icon' => $icon,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'placement' => [
                'from' => 'bottom',
                'align' => 'right',
            ],
            'delay' => $delay ?? 1000,
        ]);
    }
}
if (! function_exists('notify_error')) {
    function notify_error($title, $message, $delay = null)
    {
        notify('fas fa-times-circle', $title, $message, 'danger', $delay);
    }
}
if (! function_exists('notify_success')) {
    function notify_success($title, $message, $delay = null)
    {
        notify('fas fa-check-circle', $title, $message, 'success', $delay);
    }
}
if (! function_exists('notify_info')) {
    function notify_info($title, $message, $delay = null)
    {
        notify('fas fa-info-circle', $title, $message, 'info', $delay);
    }
}
if (! function_exists('notify_warning')) {
    function notify_warning($title, $message, $delay = null)
    {
        notify('fas fa-exclamation-circle', $title, $message, 'warning', $delay);
    }
}
if (! function_exists('notify_primary')) {
    function notify_primary($title, $message, $delay = null)
    {
        notify('fa fa-bell', $title, $message, 'primary', $delay);
    }
}
if (! function_exists('notify_secondary')) {
    function notify_secondary($title, $message, $delay = null)
    {
        notify('fa fa-bell', $title, $message, 'secondary', $delay);
    }
}

if (! function_exists('convert_date_to_khmer')) {
    function convert_date_to_khmer($date)
    {
        $khmer_numbers = ['០', '១', '២', '៣', '៤', '៥', '៦', '៧', '៨', '៩'];
        $khmer_months = [
            'មករា', 'កុម្ភៈ', 'មីនា', 'មេសា', 'ឧសភា', 'មិថុនា',
            'កក្កដា', 'សីហា', 'កញ្ញា', 'តុលា', 'វិច្ឆិកា', 'ធ្នូ',
        ];
        $khmer_days = [
            'Mon' => 'ថ្ងៃច័ន្ទ',
            'Tue' => 'ថ្ងៃអង្គារ',
            'Wed' => 'ថ្ងៃពុធ',
            'Thu' => 'ថ្ងៃព្រហស្បតិ៍',
            'Fri' => 'ថ្ងៃសុក្រ',
            'Sat' => 'ថ្ងៃសៅរ៍',
            'Sun' => 'ថ្ងៃអាទិត្យ',
        ];

        $day = date('d', strtotime($date));
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        $weekday = date('D', strtotime($date));

        $day_kh = str_replace(range(0, 9), $khmer_numbers, $day);
        $month_kh = $khmer_months[$month - 1];
        $year_kh = str_replace(range(0, 9), $khmer_numbers, $year);
        $weekday_kh = $khmer_days[$weekday];

        return $weekday_kh.', '.
                ' ថ្ងៃទី'.$day_kh.
                ' ខែ'.$month_kh.
                ' ឆ្នាំ'.$year_kh;
    }
}

if (! function_exists('generateLocales')) {
    function generateLocales(array $locales = ['en'])
    {
        foreach ($locales as $locale) {
            $localizations = DB::table('translations')
                ->where('locale_id', DB::table('locales')
                    ->where('locale', $locale)->value('id'))
                ->where('deleted_at', null)
                ->where('active', 'Y')
                ->pluck('value', 'key');

            $filePath = resource_path("lang/{$locale}.json");
            if (! is_dir(dirname($filePath))) {
                mkdir(dirname($filePath), 0755, true);
            }

            file_put_contents($filePath, json_encode($localizations, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }
}

if (! function_exists('deleteLocales')) {
    function deleteLocales(array $locales = [])
    {
        foreach ($locales as $locale) {
            $filePath = resource_path("lang/{$locale}.json");
            if (file_exists($filePath)) {
                try {
                    unlink($filePath);
                    echo "Deleted '{$locale}.json' successfully.\n";
                } catch (Exception $e) {
                    echo "Failed to delete '{$locale}.json': ".$e->getMessage()."\n";
                }
            } else {
                echo "File '{$locale}.json' does not exist.\n";
            }
        }
    }
}

if (! function_exists('generateConfig')) {
    function generateConfig()
    {
        $configs = DB::table('configurations')
            ->whereNull('deleted_at')
            ->where('active', 'Y')
            ->get(['key', 'value', 'datatype', 'is_json']);

        $configArray = [];

        foreach ($configs as $config) {
            if ($config->is_json === 'Y') {
                $configArray[$config->key] = json_decode($config->value, true) ?? [];
            } elseif ($config->datatype === 'number') {
                $configArray[$config->key] = strpos($config->value, '.') !== false ? (float) $config->value : (int) $config->value;
            } else {
                $configArray[$config->key] = (string) $config->value;
            }
        }

        $configContent = "<?php\nreturn ".var_export($configArray, true).";\n";

        $filePath = base_path('config/setting.php');

        if (! is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }

        file_put_contents($filePath, $configContent);
    }
}

if (! function_exists('makeDirectory')) {
    function makeDirectory($diskName = '', $storePath = '')
    {
        $folder = '';
        if (! file_exists($storePath)) {
            $folder = Storage::disk($diskName)->makeDirectory($storePath);
        }

        return $folder;
    }
}

if (! function_exists('deleteDirectory')) {
    function deleteDirectory($diskName = '', $storePath = '')
    {
        $folder = '';
        if (file_exists($storePath)) {
            $folder = Storage::disk($diskName)->deleteDirectory($storePath);
        }

        return $folder;
    }
}

if (! function_exists('deleteFile')) {
    function deleteFile($diskName = '', $req_path = '')
    {
        $path_file = '';
        if (Storage::disk($diskName)->exists($req_path)) {
            $path_file = Storage::disk($diskName)->delete($req_path);
        }
        $result = ['deleteStatus' => $path_file];

        return $result;
    }
}

if (! function_exists('assetFile')) {
    function assetFile($diskName = '', $storePath = '')
    {
        $attachment = null;
        if (Storage::disk($diskName)->exists($storePath)) {
            $attachment = url(Storage::disk($diskName)->url($storePath));
        }

        return $attachment;
    }
}

if (! function_exists('selectedObject')) {
    function selectedObject($value = '', $table = '', $options = [])
    {
        $lang = app()->getLocale();
        $field = $options['field'] ?? 'id';
        $textFields = $options['textFields'] ?? ["name_{$lang}"];
        $select = array_merge([$field], array_map(function ($textField) {
            return DB::raw("CONCAT($textField) AS $textField");
        }, $textFields));

        $result = DB::table($table)
            ->where($field, $value)
            ->select($select)
            ->first();

        if ($result) {
            $text = implode(' ', array_map(function ($textField) use ($result) {
                return $result->{$textField};
            }, $textFields));

            return ['id' => $result->{$field}, 'text' => $text];
        }

        return null;
    }
}
if (! function_exists('uploadFile')) {
    function uploadFile($diskName = '', $storePath = '', $req_file = '', $fileName = null)
    {
        if ($req_file) {
            $path_file = $fileName
                ? $storePath.'/'.$fileName
                : Storage::disk($diskName)->put($storePath, $req_file);

            if ($fileName) {
                Storage::disk($diskName)->putFileAs($storePath, $req_file, $fileName);
            }

            return [
                'pathFile' => $path_file,
                'fileUrl' => Storage::disk($diskName)->url($path_file),
            ];
        }

        return ['pathFile' => '', 'fileUrl' => ''];
    }
}
if (! function_exists('resizeAndUploadImage')) {
    function resizeAndUploadImage($attachment = '', $storePath = '', $fileName = null, $fileExtension = 'jpg', $resizeOptions = [])
    {
        if ($attachment) {
            try {
                $manager = new ImageManager(new Driver);
                $image = $manager->read($attachment);

                if (! file_exists($storePath)) {
                    makeDirectory(config('setting.disk_name'), $storePath);
                }

                if ($resizeOptions['resize'] == 'Y') {
                    $image->resize($resizeOptions['width'], $resizeOptions['height']);
                }
                if ($resizeOptions['crop'] == 'Y') {
                    $image->crop($resizeOptions['width'], $resizeOptions['height'], $resizeOptions['x'], $resizeOptions['y']);
                }

                switch ($fileExtension) {
                    case 'png':
                        $image->toPng($resizeOptions['quality']);
                        break;
                    case 'gif':
                        $image->toGif($resizeOptions['quality']);
                        break;
                    case 'webp':
                        $image->toWebp($resizeOptions['quality']);
                        break;
                    default:
                        $image->toJpeg($resizeOptions['quality']);
                        break;
                }

                $tempPath = sys_get_temp_dir().'/'.uniqid().'.'.$fileExtension;
                $image->save($tempPath);
                $fileName = $fileName ? $fileName.'.'.$fileExtension : $fileName;
                $savedAttachment = uploadFile(config('setting.disk_name'), $storePath, new \Illuminate\Http\File($tempPath), $fileName);

                if (file_exists($tempPath)) {
                    unlink($tempPath);
                }

                return $savedAttachment;
            } catch (\Exception $e) {
                DB::rollBack();

                return null;
            }
        }

        return null;
    }
}

if (! function_exists('resizeLogo')) {
    function resizeLogo()
    {
        return [
            'resize' => 'Y',
            'width' => config('setting.lang_width'),
            'height' => config('setting.lang_height'),
            'quality' => config('setting.lang_quality'),
            'crop' => 'N',
            'x' => 0,
            'y' => 0,
        ];
    }
}
if (! function_exists('resizeAvatar')) {
    function resizeAvatar()
    {
        return [
            'resize' => 'N',
            'width' => config('setting.avatar_width'),
            'height' => config('setting.avatar_height'),
            'quality' => config('setting.avatar_quality'),
            'crop' => 'N',
            'x' => 1024,
            'y' => 1024,
        ];
    }
}

if (! function_exists('getFlag')) {
    function getFlag()
    {
        $locales = Locale::where('active', 'Y')->get();
        if ($locales->isEmpty()) {
            return [];
        }

        $flags = [];
        foreach ($locales as $value) {
            $logo = assetFile(config('setting.disk_name'), $value->logo);
            $flags[$value->locale] = $logo ?? custom_asset('image/lang/'.$value->locale.'.png');
        }

        $default = $locales->firstWhere('locale', App::getLocale());
        if ($default == null) {
            $user = User::find(Auth::id());
            $user->locale = 'en';
            $user->save();
        }

        $defaultFlag = $default ? [$default->locale => assetFile(config('setting.disk_name'), $default->logo) ?? custom_asset('image/lang/'.app()->getLocale().'.png')] : [App::getLocale() => custom_asset('image/lang/no.png')];

        $isSelect = 'Y';
        if ($locales->count() <= 1 && ! empty($default)) {
            $isSelect = 'N';
        }

        return [
            'flags' => $flags,
            'default' => $defaultFlag,
            'isSelect' => $isSelect,
        ];
    }
}

if (! function_exists('getFlagByLocale')) {
    function getFlagByLocale($locale)
    {
        $flag = Locale::where('locale', $locale)->where('active', 'Y')->first();

        return assetFile(config('setting.disk_name'), $flag->logo);
    }
}

if (! function_exists('slug')) {
    function slug($value = '')
    {
        return strtolower(preg_replace('![^a-z0-9]+!i', '-', $value));
    }
}
