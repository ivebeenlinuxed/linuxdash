<?php
namespace Modules\PackageManager\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Storage;
use ZipArchive;
use Module;

class InstallPackage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
	protected $channel;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url)
    {
		$this->channel = "queue.job.".rand(1,10000).(microtime(true)*10000);
        $this->url = $url;
    }
	
	public function getChannel() {
		
	}

    /**
     * Execute the job.
     *
     * @return bool
     */
    public function handle()
    {
		$jobID = "installer-".rand(1,10000)."-".(microtime(true)*10000);
        $tmpstorage = base_path("tmp/".$jobID."/");
		//Storage::disk('local')->makeDirectory($tmpstorage, 0755, true, true);
		mkdir($tmpstorage);
		$fp = fopen($zippath = $tmpstorage."archive.zip", "w+");
		$ch = curl_init($this->url);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		try {
			$zip = new ZipArchive;
			$zip->open($zippath);
			$zip->extractTo($tmpstorage);
			$zip->close();
		} catch (\Exception $e) {
			echo $e->getMessage();
			return false;
		}
		$dh = opendir($tmpstorage);
		while (($entry = readdir($dh)) !== false) {
			if (is_dir($tmpstorage.$entry)) {
				Module::install($tmpstorage.$entry);
				//$json = json_decode(file_get_contents($tmpstorage."/".$entry."/module.json"), true);
				//$dir = $json['name'];
				//rename($tmpstorage."/".$entry, $dir);
			}
		}
		return false;
		
    }
}