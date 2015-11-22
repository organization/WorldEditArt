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
	var doc = $("#doc-content");
	doc.html("");
	var st = $("<div id='stats'><h1>Statistics</h1></div>");
	var table = $("<table></table>");
	table.attr("border", 1);
	table.appendTo(st);
	var rm = $("<div id='readme'></div>");
	st.appendTo(doc);
	rm.appendTo(doc);
	$.get("stats.json", {}, function(data){
		if(typeof data !== "object"){
			data = JSON.parse(data);
		}
		var row = $("<tr></tr>");
		row.append("<th style='text-align: right; padding: 5px;'>PHP Files</th>");
		row.append("<td style='text-align: left; padding: 5px;'>" + data.files + "</td>");
		row.appendTo(table);
		row = $("<tr></tr>");
		row.append("<th style='text-align: right; padding: 5px;'>Lines of PHP code</th>");
		//noinspection JSUnresolvedVariable
		row.append("<td style='text-align: left; padding: 5px;'>" + data.lines + "</td>");
		row.appendTo(table);
		row = $("<tr></tr>");
		row.append("<th style='text-align: right; padding: 5px;'>Kilobytes of PHP code</th>");
		row.append("<td style='text-align: left; padding: 5px;'>" + Math.round(data.size / 102.4) / 10 + "</td>");
		row.appendTo(table);
	});
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
				rm.html(result);
			},
			error: function(result){
				alert("GitHub Markdown error: " + result.message);
			}
		});
	});
}, 10);

