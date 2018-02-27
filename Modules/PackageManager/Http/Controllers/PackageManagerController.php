<?php

namespace Modules\PackageManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Module;
use Theme;

use Modules\PackageManager\Jobs\InstallPackage;

class PackageManagerController extends Controller
{
	/**
	 * Repo list
	 * @fixme make this dynamic
	 */
	private $repos = array("https://api.github.com/users/ivebeenlinuxed/repos");
	
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
		$modules = Module::all();
		$themes = Theme::all();
		
		foreach ($modules as $key=>$data) {
			$modules[$key]->__composer_json = json_decode(file_get_contents($data->getPath()."/composer.json"), true);
		}
		
		foreach ($themes as $key=>$data) {
			$themes[$key]->__theme_json = json_decode(file_get_contents(Theme::themes_path()."/".$themes[$key]->assetPath.'/'.'theme.json'), true);
		}
		
        return view('packagemanager::index', ['modules'=>$modules, 'themes'=>$themes, 'repos'=>$this->repos]);
    }
	
	public function get_update_tasks()
	{
		$tasks = array();
		foreach ($this->repos as $id=>$repo) {
			$tasks[] = array("get_repo", $id, $repo);
		}
		
		foreach (Module::all() as $name => $module) {
			$tasks[] = array("get_module_manifest", $name);
		}
		return $tasks;
	}
	
	public function get_repo($id)
	{
		$ch = curl_init($this->repos[$id]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "LinuxDash 0.1");
		$data = json_decode(curl_exec($ch), true);
		$output = array();
		foreach ($data as $pkg) {
			if (strpos($pkg['full_name'], "linuxdash") === false) {
				continue;
			}
			$output[] = array(
				"id"=>"$id:{$pkg['id']}",
				"name"=>$pkg['full_name'],
				"description"=>$pkg['description'],
				"author"=>$pkg['owner']['login'],
				"manifest"=>"https://raw.githubusercontent.com/".$pkg['full_name']."/master/composer.json",
				"archive"=>"http://www.github.com/".$pkg['full_name']."/archive/"
			);
		}
		return $output;
	}
	
	public function get_module_manifest($name)
	{
		$mod = Module::find($name);
		$manifest = json_decode(file_get_contents($mod->getPath()."/composer.json"), true);
		
		$ch = curl_init("https://packagist.org/p/".$manifest['name'].".json");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "LinuxDash 0.1");
		$data = json_decode(curl_exec($ch), true);
		
		sleep(5);
		if (is_null($data)) {
			return "false";
		}
		
		$data['__update'] = version_compare(array_keys($manifest['extra']['changelog'])[0], array_keys($data['extra']['changelog'])[0], "<");
		
		return $data;
	}
	
	public function install_module()
	{
		var_dump(InstallPackage::dispatch($_POST['url']));
	}

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('packagemanager::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('packagemanager::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('packagemanager::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
