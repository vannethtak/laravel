<?php

namespace Modules\Settings\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Settings\App\DataTables\ActivityLogDataTable;
use Modules\Settings\App\Models\SystemLog;

class ActivityLogController extends Controller
{
    private $module = 'settings';

    private $page_name = 'activity-log.';

    private $table_name = 'activity_log';

    private $load_page;

    private $prefix_route;

    private $data;

    public function __construct()
    {
        $this->load_page = $this->module.'::'.$this->page_name.'.';
        $this->prefix_route = $this->module.'.'.$this->page_name;
        $this->data['prefix_route'] = $this->prefix_route;
        $this->data['module'] = $this->module;
        $this->data['page_name'] = $this->page_name;
        $this->data['table_name'] = $this->table_name;
        $this->data['load_page'] = $this->load_page;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ActivityLogDataTable $dataTable)
    {
        $data = $this->data;

        return $dataTable->render($this->load_page.'index', compact('data'));
    }

    public function showDetail(Request $request)
    {
        $logs = SystemLog::where('id', $request->log_id)->select('properties', 'event', 'description', 'created_at')->first();
        $properties = json_decode($logs->properties, true);
        $trs = '';
        $text = '';

        if ($logs->description == 'updated') {
            $trs .= '<tr class=" bg-info bg-opacity-30">
                <th colspan="2">CURRENT</th>
                <th colspan="2">OLD</th>
            </tr>';
        } elseif ($logs->description == 'logined') {
            $trs .= '<p class="bg-info bg-opacity-30">
            Logged at '.$logs->created_at.'</p>';
        } else {
            $trs .= '<tr class="bg-info bg-opacity-30">
                <th colspan="2">CURRENT</th>
            </tr>';
        }

        if (! empty($properties['attributes'])) {
            foreach ($properties['attributes'] as $title => $value) {
                $old = '';
                if (! empty($properties['old'])) {
                    $old = $properties['old'][$title];
                }
                $text = $title;
                $tesxt = 'Short_name';
                $pos = strpos($tesxt, '_');
                $titleArr = [];
                if ($pos) {
                    $titleArr = explode('_', $title);
                    $text = '';
                    foreach ($titleArr as $a) {
                        $text .= ''.$a.' ';
                    }
                }
                $trs .= '<tr>
                    <td class="">'.ucfirst($text).' </td>
                    <td class="">'.': '.$value.'</td>';
                if ($logs->description == 'updated') {
                    $trs .= '<td class="text-danger">   '.ucfirst($text).' </td>
                            <td class="text-danger">: '.$old.'</td>';
                }
                $trs .= '</tr>';
            }
        }

        $data['trs'] = $trs;
        $data['event'] = ucfirst($logs->description);

        return response()->json($data);
    }
}
