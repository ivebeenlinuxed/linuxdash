"use strict"
var packagemanager_tasks;
var packagemanager_complete;

fetch("/packagemanager/get_update_tasks").then((resp) => {
	return resp.json();
}).then((json) => {
	packagemanager_tasks = json.length;
	packagemanager_complete = 0;
	document.querySelector("#packagemanager-progress").classList.remove("d-none");
	
	for (var i in json) {
		
		if (json[i][0] == "get_repo") {
			fetch("/packagemanager/"+json[i][0]+"/"+json[i][1]).then(resp => resp.json()).then((pkg_json) => {

				for (var j in pkg_json) {
					var row = document.createElement("tr");
					
					var cell = document.createElement("td");
					cell.innerHTML = pkg_json[j].id;
					row.appendChild(cell);
					
					var cell = document.createElement("td");
					cell.innerHTML = pkg_json[j].name;
					row.appendChild(cell);
					
					cell = document.createElement("td");
					cell.innerHTML = pkg_json[j].description;
					row.appendChild(cell);
					
					
					cell = document.createElement("td");
					cell.innerHTML = pkg_json[j].author;
					row.appendChild(cell);
					
					
					cell = document.createElement("td");
					cell.innerHTML = "Latest";
					row.appendChild(cell);
					
					cell = document.createElement("td");
					cell.innerHTML = "Available";
					row.appendChild(cell);
					
					cell = document.createElement("td");
					var button = document.createElement("button");
					button.type="button";
					button.classList.add("btn");
					button.classList.add("btn-primary");
					button.innerText = "Install";
					button.pkg_json = pkg_json[j];
					button.addEventListener("click", function() {
						packagemanager_install(this.pkg_json.archive);
					});
					cell.appendChild(button);
					row.appendChild(cell);
					
					
					
					document.querySelector("#module-tbody").appendChild(row);
				}
				var perc = Math.round((0.0+ (++packagemanager_complete))/packagemanager_tasks*100);
				var prog = document.querySelector("#packagemanager-progress > div");
				prog.style.width = perc+"%";
				prog.innerText = "Fetched "+pkg_json.length+" packages...";
				if (perc >= 100) {
					document.querySelector("#packagemanager-progress").classList.add("d-none");
				}
				
			});
		}
		
		if (json[i][0] == "get_module_manifest") {
			document.querySelector("#module-tbody #mod-row-"+json[i][1]+" .fa-spin").classList.remove("invisible");
			fetch("/packagemanager/"+json[i][0]+"/"+json[i][1]).then(resp => resp.json()).then(function(j) {
				return function(pkg_json) {
					document.querySelector("#module-tbody #mod-row-"+j[1]+" .fa-spin").classList.add("invisible");
					
					if (pkg_json == false) {
						document.querySelector("#module-tbody #mod-row-"+j[1]+" .fa-exclamation-circle").classList.remove("d-none");
					} else if (pkg_json.__update) {
						document.querySelector("#module-tbody #mod-row-"+j[1]+" .fa-upload").classList.remove("d-none");
						document.querySelector("#module-tbody #mod-row-"+j[1]+" .btn-primary").classList.remove("d-none");
						document.querySelector("#module-tbody #mod-row-"+j[1]+" .btn-primary").addEventListener("click", function(j) {
							return function() {
								packagemanager_install(j.extra.archive);
							}
						}(pkg_json));
					}
					
					
					
					var perc = Math.round((0.0+ (++packagemanager_complete))/packagemanager_tasks*100);
					var prog = document.querySelector("#packagemanager-progress > div");
					prog.style.width = perc+"%";
					prog.innerText = "Completed fetch manifest for "+j[1]+"...";
					if (perc >= 100) {
						document.querySelector("#packagemanager-progress").classList.add("d-none");
					}
				}
			}(json[i]));
			
			
		}
	}
});

function packagemanager_install(pkg) {
	var formData = new FormData();
	formData.append("url", pkg);
	fetch("packagemanager/install_module", {
		method: "post",
		body: formData,
		headers: {
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
		},
		credentials: "include"
	});
}