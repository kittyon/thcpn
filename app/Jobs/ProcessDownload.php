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
        for ($i=0; $i < count($query_options->device_ids); $i++){
            $device_datas = DeviceData::where('device_id', $query_options->device_ids[$i])
                                        ->whereBetween('ts', [$query_options->start_at, $query_options->end_at])
                                        ->get();
            $device_datas_image = array();
            $raw_device_datas_data = $device_datas->filter(function($item, $key) use (&$device_datas_image) {
                $data = $item->data;
                $config = $item->config->data;
                foreach ($data as $key => $value){
                    $type = $config[$key]['type'];
                    if ($type == 'image'){
                        $item->image_key = $key;
                        array_push($device_datas_image, $item);
                        return false;
                    }
                }
                return true;
            });
            if ($query_options->with_image){
                $image_folder_path = $folder_path.$query_options->device_ids[$i].'/';
                Storage::makeDirectory($image_folder_path);
                $client = new Client();
                foreach ($device_datas_image as $device_data_image) {
                    $image_url = $device_data_image->data[$device_data_image->image_key]['value'];
                    $res = $client->request('GET', $image_url_prefix.$image_url);
                    // $image_file_path = $image_folder_path.($device_data_image->created_at)->toDateTimeString().'.jpg';
                    $image_file_path = $image_folder_path.$device_data_image->ts.'.jpg';
                    Storage::disk('local')->put($image_file_path, $res->getBody()->getContents());
                }
            }
            if ($query_options->with_data){
                $device = Device::find($query_options->device_ids[$i]);
                $csv_file_name = $device->name.'-'.($download_job->created_at)->toDateTimeString().'.csv';
                $processed_data_collection = $this->data_process($raw_device_datas_data);
                $file = fopen($root_path.$folder_path.$csv_file_name, 'w+');
                foreach ($processed_data_collection as $line) {
                    fputcsv($file, $line);
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

    public function data_process($raw_data_collection){
        $processed_data_collection = array();
        $device_config_id = 0;
        foreach ($raw_data_collection as $raw_data){
            if ($raw_data->device_config_id != $device_config_id) {
                $device_config_id = $raw_data->device_config_id;
                $title_line = array();
                array_push($title_line, 'date');
                foreach ($raw_data->config->data as $key => $value) {
                    if ($value['type'] != 'image') {
                        array_push($title_line, $value['desc']);
                    }
                }
                array_push($processed_data_collection, $title_line);
            }
            $data_line = array();
            array_push($data_line, $raw_data->ts);
            foreach ($raw_data->data as $key => $value) {
                array_push($data_line, $value['value']);
            }
            array_push($processed_data_collection, $data_line);
        }
        return $processed_data_collection;
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
