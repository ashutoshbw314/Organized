function getinfo(url, data, obj, property, fun) {
	let hr = new XMLHttpRequest();
	hr.open("POST", url, true);
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	hr.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			obj[property] = this.responseText;
			if (fun) {
				fun();
			}
		}
  	};
	hr.send(data);
}
