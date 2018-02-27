@extends('layouts.main')

@section('head')

@stop



@section('content')
    <h1>Package Manager</h1>
	<br />
	<div id="packagemanager-progress" class="progress d-none">
	  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">Updating...</div>
	</div>
	<button href="#" class="btn btn-primary pull-right">Check Online</button>
	<ul class="nav nav-tabs" id="myTab" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Plugins</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Themes</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" id="profile-tab" data-toggle="tab" href="#repos" role="tab" aria-controls="profile" aria-selected="false">Repos</a>
		  </li>
	</ul>
	<div class="tab-content" id="myTabContent">
	  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
		  <table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Description</th>
						<th>Author</th>
						<th>Version</th>
						<th>Activated</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody id="module-tbody">
				@foreach ($modules as $name=>$module)
				<tr id="mod-row-{{ $name }}">
					<td><i class="fa fa-spinner fa-spin invisible"></i>{{ $name }}</td>
					<td>{{ $module->__composer_json['name'] }}</td>
					<td>{{ $module->__composer_json['description'] }}</td>
					<td>{{ $module->__composer_json['authors'][0]['name'] }} &lt;{{ $module->__composer_json['authors'][0]['email'] }}&gt;</td>
					<td>{{ array_keys($module->__composer_json['extra']['changelog'])[0] }} <i class="fa fa-exclamation-circle d-none text-danger" aria-hidden="true"></i><i class="fa fa-upload d-none text-success" aria-hidden="true"></i></td>
					<td>{{ $module->isStatus(1)? "Active" : "Deactivated" }}</td>
					<td>
						<div class="btn-group" role="group" aria-label="Basic example">
							<button type="button" class="btn btn-primary d-none">Update</button>
							<button type="button" class="btn btn-secondary">Disable</button>
							<button type="button" class="btn btn-danger">Uninstall</button>
						</div>
					</td>
				</tr>
				@endforeach
				</tbody>
			</table>
	  </div>
	  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
		<table class="table">
			<thead>
				<tr>
					<th>ID</th>
					<th>Parent Theme</th>
					<th>Version</th>
					<th>Actions</th>
				</tr>
			<thead>
			<tbody>
				@foreach ($themes as $theme)
				<tr>
					<td>{{ $theme->name }}</td>
					<td>{{ $theme->parent }}</td>
					<td>{{ $theme->__theme_json['version'] }}</td>
					<td></td>
				</tr>
				@endforeach
			</tbody>
		</table>
	  </div>
	  <div class="tab-pane fade" id="repos" role="tabpanel" aria-labelledby="repos-tab">
		<table class="table">
			<thead>
				<tr>
					<th>URL</th>
				</tr>
			<thead>
			<tbody>
				@foreach ($repos as $repo)
				<tr>
					<td>{{ $repo }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	  </div>
	</div>
	
    <p>
        This view is loaded from module: {!! config('packagemanager.name') !!}
    </p>
@stop



@section('foot')
<script type="text/javascript" src="{{ Module::asset('packagemanager:update.js') }}"></script>
@stop
