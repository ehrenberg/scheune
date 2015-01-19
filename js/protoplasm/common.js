function listUserFiles(directory, callback) {
	new Ajax.Request('fileManager.php', {
			parameters: { 'a': 'listdir', 'd': (directory || '') },
			onComplete: function(response) {
				try {
					callback(eval('(' + response.responseText + ')'));
				} catch(e) {
					callback({status:'error'});
				}
			}
		});
}
