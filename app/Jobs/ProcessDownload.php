<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use App\Models\DeviceData;
use App\Models\Device;
use App\Models\DownloadJob;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

use ZipArchive;

class ProcessDownload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $options;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($options)
    {
        //
        $this->options = $options;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $image_url_prefix = 'http://thc-platfrom-storage.b0.upaiyun.com';
        $root_path = storage_path().'/app/';
        $unique_key = md5(uniqid());
        $download_job = DownloadJob::find($this->options['id']);
        $folder_name = (string)($download_job->user_id).'-'.($download_job->created_at)->toDateTimeString().'-'.$unique_key;
        $folder_name = join('_', explode(' ', $folder_name));
        $folder_path = $folder_name.'/';
        Storage::makeDirectory($folder_path);
        $query_options = json_decode($this->options['options']);
        foreach($query_options->devices as $device_id){
          if(in_array('image',$query_options->contents)){
            $image_folder_path = $folder_path.$device_id.'/';
            Storage::makeDirectory($image_folder_path);
            $client = new Client();
            $images = DeviceData::where('device_id', $device_id)
                                        ->where('type','image')
                                        ->whereBetween('ts', [$query_options->dates[0], $query_options->dates[1]])
                                        ->get();
            foreach ($images as $img) {
              foreach($img->data as $key=>$val){
                $image_folder_key_path = $image_folder_path.'/'.$key.'/';
                Storage::makeDirectory($image_folder_key_path);
                $image_url = $val['value'];
                $res = $client->request('GET', $image_url_prefix.$image_url);
                $image_file_path = $image_folder_key_path.$img->ts.'.jpg';
                Storage::disk('local')->put($image_file_path, $res->getBody()->getContents());
              }
            }
          }

          if(in_array('data', $query_options->contents)){
            $device = Device::find($device_id);
            $config = $device->config()->first();
            Log::info($config);
            if($config->version == "2.0"){
              $cf = $config->data;
              $cfs = array();
              foreach($cf as $item){
                foreach($item['params'] as $param){
                  $cfs[$param['key']] = ['name'=>$param['name'],
                   'type'=> $param['type'],
                   'unit'=>$param['unit'],
                   'desc'=>$param['desc'],
                   'value'=>0];
                }
              }
            }
            else{
              $cfs = $config->data;
              foreach($cfs as $k=>$c){
                $cfs[$k]['value'] = 0;
              }
            }

            $csv_file_name = $device->name.'-'.($download_job->created_at)->toDateTimeString().'.csv';
            $datas = DeviceData::where('device_id', $device_id)
                                        ->where('type','data')
                                        ->whereBetween('ts', [$query_options->dates[0], $query_options->dates[1]])
                                        ->get();
            $title = array();
            array_push($title, 'date');
            foreach ($cfs as $key => $value) {
              if($value['type']!='image'){
                array_push($title,$value['name']);
              }
              else{
                unset($cfs[$key]);
              }

            }
            $file = fopen($root_path.$folder_path.$csv_file_name, 'w+');
            fputcsv($file, $title);

            foreach($datas as $dt){
              $line = array();
              array_push($line, $dt->ts);//time
              foreach($dt->data as $k =>$v){
                if(array_key_exists($k,$cfs)){
                  $cfs[$k]['value'] = $v['value'];
                }
              }

              foreach($cfs as $k=>$c){
                array_push($line,$cfs[$k]['value']);

              }
              Log::info($line);

              fputcsv($file,$line);
              foreach($cfs as $k=>$c){
                $cfs[$k]['value'] = 0;
              }
            }

            fclose($file);
          }


        }

        $zip = new ZipArchive();
        if ($zip->open($root_path.$folder_name.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE) != true) {
            die('An error occurred creating your zip file.');
        }
        $this->add_file_to_zip($root_path.$folder_path, $zip);
        $zip->close();

        Storage::deleteDirectory($folder_path);

        $this->push_to_upyun($root_path.$folder_name.'.zip', $folder_name.'.zip');

        Storage::delete($folder_name.'.zip');

        $download_job->status = 'completed';
        $download_job->url = '/'.$folder_name.'.zip';
        $download_job->save();
    }


    public function add_file_to_zip($path, $zip){
        $handler = opendir($path);
        while (($filename = readdir($handler)) !== false){
            if ($filename != '.' && $filename != '..') {
                if (is_dir($path.'/'.$filename)) {
                    $this->add_file_to_zip($path.'/'.$filename, $zip);
                }
                else{
                    $path_component = explode('/', $path);
                    $relative_path = $path_component[count($path_component)-1];
                    $zip->addFile($path.'/'.$filename, $relative_path.'/'.$filename);
                }
            }
        }
        @closedir($path);
    }

    public function push_to_upyun($path, $filename){
        $driver = Storage::drive('upyun');
        $file = fopen($path, 'r');
        $driver->write('/'.$filename, $file);
    }
}
