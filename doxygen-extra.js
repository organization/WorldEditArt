/*
 * WorldEditArt
 *
 * Copyright (C) 2015 PEMapModder
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PEMapModder
 */

setTimeout(function(){
	var doc = document.getElementById("doc-content");
	doc.innerHTML = "";
	$.get("https://raw.githubusercontent.com/PEMapModder/WorldEditArt/master/README.md", {}, function(data){
		//noinspection JSUnusedGlobalSymbols
		$.ajax({
			type: "POST",
			url: "https://api.github.com/markdown",
			data: JSON.stringify({
				text: data,
				mode: "gfm",
				context: "PEMapModder/WorldEditArt"
			}),
			success: function(result){
				doc.innerHTML = result;
			},
			error: function(result){
				alert(JSON.stringify(result));
			}
		});
	});
}, 10);

