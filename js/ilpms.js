function jsnnone() {
	var layer = document.getElementById('jsn-layer');
	layer.style.display = 'none';
}

function jsnopen(file) {
	var fbox = document.getElementById('ren-file');
	fbox.setAttribute('value', file);

	var container = document.getElementById('fixed-container');
	var origin = pos = -350;
	var iv = setInterval(anim, 2);

	var layer = document.getElementById('jsn-layer');
        layer.style.display = 'inline';

	function anim() {
		if (pos >= 0) {
			clearInterval(iv);
		} else {
			pos += 5;
			container.style.top = pos + 'px';
			var opacity = 1 - (pos / origin);
			container.style.opacity = (opacity);
		}
	}
}


window.onload = function() {
	var searchbar = document.getElementById('stream-search');
	if (searchbar) searchbar.addEventListener('input', searchEvent);

	function searchEvent(evt) {
		var listings = document.getElementsByName('listing');
		listings.forEach(function(element) {
			var anchorNode = element.childNodes[0].childNodes[0];
			if (anchorNode.tagName.toLowerCase() == 'a') {
				var localName = anchorNode.innerHTML.toLowerCase();
				if (localName.includes(searchbar.value.toLowerCase())) {
					element.style.display = 'table-row';
				} else {
					element.style.display = 'none';
				}
			}
		});
	}
}

window.onkeydown = function(evt) {
	if (evt.code == 'Escape') {
		jsnnone();
	}
}
