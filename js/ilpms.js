function jsnnone() {
	var layer = document.getElementById('jsn-layer');
	layer.style.display = 'none';
}

function jscnone() {
        var layer = document.getElementById('jsc-layer');
        layer.style.display = 'none';
}

function jsnopen(file) {
	var fbox = document.getElementById('ren-file');
	fbox.setAttribute('value', file);
	var newname = document.getElementById('ren-name');
	newname.setAttribute('value', file.split('.').slice(0, -1).join('.'));

	var container = document.getElementById('fixed-container-a');

	var layer = document.getElementById('jsn-layer');
        layer.style.display = 'inline';

	fallDown(container);
}

function jscopen(file, type) {
	var fbox = document.getElementById('convert-file');
        fbox.setAttribute('value', file);

        var container = document.getElementById('fixed-container-b');

	var formatbox = document.getElementById('convert-format');
	while (formatbox.firstChild) {
		formatbox.removeChild(formatbox.firstChild);
	}
	var formats = 'No formats for file';
	if (type != "n" && type != "i") {
		var tag = document.getElementsByName(type == 'a' ? 'audio-f' : 'video-f')[0];
		if (tag != null || tag !== undefined) {
			formats = tag.getAttribute('content');
			if (type == "v") {
				tag = document.getElementsByName('audio-f')[0];
				var aformats = tag.getAttribute('content');
				var afps = aformats.split(',');
			        if (afps.length < 1) {
			                afps = [aformats];
			        }
				aformats = "";
				afps.forEach(function(aformat) {
					aformats = aformats + ",?" + aformat;
				});
				formats = formats + aformats;

			}
		}
	}
	var parts = formats.split(',');
	if (parts.length < 1) {
		parts = [formats];
	}
	parts.forEach(function(format) {
		var node = document.createElement('option');
		var textnode = document.createTextNode(format.toUpperCase().replace("?", "(Audio) "));
		node.appendChild(textnode);
		node.setAttribute('value', format);
		formatbox.appendChild(node);
	});
        var layer = document.getElementById('jsc-layer');
        layer.style.display = 'inline';

        fallDown(container);
}

function fallDown(container, origin = -350) {
        var iv = setInterval(anim, 2);

	var pos = origin;
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
		jscnone();
	}
}
