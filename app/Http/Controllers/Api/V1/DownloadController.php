<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Transformers\DownloadJobTransformer;
use Illuminate\Support\Facades\Storage;
use App\Models\DownloadJob;


class DownloadController extends Controller
{
    //
    static $model = DownloadJob::class;
    static $permissions = [
  		'index' => ['dev_r','org_r'],
  		'update' => ['dev_w','org_w'],
  		'store' => ['dev_w','org_w']
  	];

    public function index(Request $request){
        $user = $this->user();
        $per_page = $request->input('limit', null);
        // return compact('items');
        if(!is_null($per_page)){
          return $this->response->collection($user->download_jobs()->paginate($per_page), new DownloadJobTransformer());
        }
        else{
          return $this->response->collection($user->download_jobs()->get(), new DownloadJobTransformer());
        }
    }

    public function store(Request $request){
    	  $body = $request->all();
        $download_job = new DownloadJob;
        $download_job->user_id = $this->user()->id;
        $download_job->url = '';
        $download_job->options = json_encode($body);
        $download_job->status = 'processing';
        // $download_job->app_id = Auth::user()->app_id;
        $download_job->save();
        // $body['job_id'] = $download_job->id;
    	$job = new ProcessDownload($download_job);
    	dispatch($job);
        // return $body;
    	return;
    }

    public function destroy($id){
        $download_job = DownloadJob::find($id);
        $this->delete_file($download_job->url);
        DownloadJob::destroy($id);
    }

    protected function delete_file($url){
        $driver = Storage::drive('upyun');
        $driver->delete($url);
    }

}
