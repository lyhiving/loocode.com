<?php
declare(strict_types=1);

namespace App\Http\Controllers\Backend;


use App\Attributes\Route;
use App\Http\Result;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Backend
 */
#[Route(title: "面板", sort: 0, icon: "home", link: "/app/dashboard", home: true)]
class DashboardController extends BackendController
{
    /**
     * @param Request $request
     * @return Result
     */
    public function main(Request $request): Result
    {
        $card = [];
        $card[] = $this->getSystemInfo($request);
        if (($memory = $this->getMemory())) {
            $card[] = $memory;
        }
        $card[] = $this->getPHPInfo($request);
        $card[] = $this->getPHPExtension();
        return Result::ok($card);
    }

    /**
     * @param Request $request
     * @return string[]
     */
    private function getSystemInfo(Request $request)
    {
        $columns = [
            'name' => [
                'filter' => false,
            ],
            'value' => [
                'filter' => false,
            ]
        ];
        $data = [
            ['name' => '主机名', 'value' => php_uname('n')],
            ['name' => '内核', 'value' => php_uname('r')],
            ['name' => 'OS', 'value' => PHP_OS],
        ];
        $process = new Process(['uptime']);
        $process->run();
        if ($process->isSuccessful()) {
            $output = $process->getOutput();
            $data[] = ['name' => 'uptime', 'value' => $output];
        }
        $process = new Process(['cat', '/etc/timezone']);
        $process->run();
        if ($process->isSuccessful()) {
            $output = $process->getOutput();
            $data[] = ['name' => '时区', 'value' => $output];
        }
        $process = Process::fromShellCommandline('top -b -n 1 | grep Tasks');
        $process->run();
         if ($process->isSuccessful()) {
            $output = $process->getOutput();
            $data[] = ['name' => '进程', 'value' => substr($output, 6)];
        }
        $process = Process::fromShellCommandline('cat /proc/cpuinfo | grep "model name"');
        $process->run();
        if ($process->isSuccessful()) {
            $output = $process->getOutput();
            $data[] = ['name' => 'CPU', 'value' => substr(explode("\n", $output)[0], 12)];
        }

        return [
            'class' => 'col-6',
            'title' => '系统信息',
            'body'  => <<<EOF
<ng2-smart-table
  [settings]="settings"
  [source]="source">
</ng2-smart-table>
EOF,
            'type' => 'table',
            'data' => [
                'settings' => [
                    'actions' => [
                        'add' => false,
                        'delete' => false,
                        'edit' => false,
                    ],
                    'columns' => $columns,
                ],
                'data'    => $data,
            ]
        ];
    }

    /**
     * @return array|null
     */
    private function getMemory()
    {
        $process = new Process(['free', '-m']);
        $process->run();
        if ($process->isSuccessful()) {
            $output = $process->getOutput();
            $table = explode("\n", $output);
            $columns = str_split(substr($table[0], 8), 12);
            $trColumns = ['name0' => [
                'title' => '',
                'filter' => false,
            ]];
            foreach ($columns as $key => $column) {
                $trColumns['name' . ($key + 1)] = [
                    'title' => preg_replace("/\s+/", '', $column),
                    'filter' => false,
                ];
            }
            $data = [];
            foreach ($table as $key => $tr) {
                if ($key == 0 || $key + 1 == count($table)) {
                    continue;
                }
                $columns = str_split(substr($tr, 8), 12);
                $data[$key - 1] = [
                    'name0' => substr($tr, 0,8),
                ];
                foreach ($columns as $k => $column) {
                    $data[$key - 1]['name' . $k+1] = preg_replace("/\s+/", '', $column) . ' MB';
                }
            }

            return [
                'class' => 'col-6',
                'title' => '内存',
                'body'  => <<<EOF
<ng2-smart-table
  [settings]="settings"
  [source]="source">
</ng2-smart-table>
EOF,
                'type' => 'table',
                'data' => [
                    'settings' => [
                        'actions' => [
                            'add' => false,
                            'delete' => false,
                            'edit' => false,
                        ],
                        'columns' => $trColumns,
                    ],
                    'data'    => $data,
                ]
            ];
        }
        return null;
    }

    /**
     * @param Request $request
     * @return string[]
     */
    private function getPHPInfo(Request $request)
    {
        $os = php_uname('s'). php_uname('r');
        $phpVersion = PHP_VERSION;
        $mysqlVersion = DB::getPdo()->getAttribute(\PDO::ATTR_SERVER_VERSION);
        $laravelVersion = Application::VERSION;
        $runEnv = php_sapi_name();
        $serverSoft = $request->server('SERVER_SOFTWARE');
        $zendVersion = zend_version();
        $iniDir = php_ini_loaded_file();
        $iniConfig = ini_get_all(null, false);
        $displayError = $iniConfig['display_errors'] == 'Off' ? ['close-outline', 'danger'] : ['checkmark-outline', 'success'];
        $timezone = date_default_timezone_get();
        return [
            'class' => 'col-6',
            'title' => 'PHP信息',
            'body'  => <<<EOF
<form>
  <div class="form-group row">
    <label class="label col-6 col-form-label">PHP版本</label>
    <div class="col-6">
      <input value="{$phpVersion}" nbInput disabled>
    </div>
  </div>
  <div class="form-group row">
    <label class="label col-6 col-form-label">Zend引擎版本</label>
    <div class="col-6">
      <input value="{$zendVersion}" nbInput disabled>
    </div>
  </div>
   <div class="form-group row">
    <label class="label col-6 col-form-label">Web服务</label>
    <div class="col-6">
      <input value="{$serverSoft}" nbInput disabled>
    </div>
  </div>
  <div class="form-group row">
    <label class="label col-6 col-form-label">MYSQL版本</label>
    <div class="col-6">
      <input value="{$mysqlVersion}" nbInput disabled>
    </div>
  </div>
  <div class="form-group row">
    <label class="label col-6 col-form-label">Laravel版本</label>
    <div class="col-6">
      <input value="{$laravelVersion}" nbInput disabled>
    </div>
  </div>
  <div class="form-group row">
    <label class="label col-6 col-form-label">运行模式</label>
    <div class="col-6">
      <input value="{$runEnv}" nbInput disabled>
    </div>
  </div>
  <div class="form-group row">
    <label class="label col-6 col-form-label">INI路径</label>
    <div class="col-6">
      <input value="{$iniDir}" nbInput disabled>
    </div>
  </div>
  <div class="form-group row">
    <label class="label col-6 col-form-label">脚本最大内存</label>
    <div class="col-6">
      <input value="{$iniConfig['memory_limit']}" nbInput disabled>
    </div>
  </div>
  <div class="form-group row">
    <label class="label col-6 col-form-label">最大上传大小</label>
    <div class="col-6">
      <input value="{$iniConfig['upload_max_filesize']}" nbInput disabled>
    </div>
  </div>
  <div class="form-group row">
    <label class="label col-6 col-form-label">POST提交大小</label>
    <div class="col-6">
      <input value="{$iniConfig['post_max_size']}" nbInput disabled>
    </div>
  </div>
  <div class="form-group row">
    <label class="label col-6 col-form-label">显示错误</label>
    <div class="col-6">
      <nb-icon icon="{$displayError[0]}" size="large" status="{$displayError[1]}"></nb-icon>
    </div>
  </div>
  <div class="form-group row">
    <label class="label col-6 col-form-label">脚本超时时间</label>
    <div class="col-6">
      <input value="{$iniConfig['max_execution_time']}" nbInput disabled>
    </div>
  </div>
  <div class="form-group row">
    <label class="label col-6 col-form-label">当前时区</label>
    <div class="col-6">
      <input value="{$timezone}" nbInput disabled>
    </div>
  </div>
</form>
EOF,
        ];
    }

    /**
     * @return string[]
     */
    private function getPHPExtension()
    {
        $extensions  = get_loaded_extensions();
        $exts = '<div class="row">';
        foreach ($extensions as $ext) {
            $exts .=  <<<EOF
<div class="col">
<nb-alert status="success">$ext</nb-alert>
</div>
EOF;
        }
        $exts .= '</div>';
        return [
            'class' => 'col-6',
            'title' => 'PHP扩展',
            'body'  => $exts,
        ];

    }
}
