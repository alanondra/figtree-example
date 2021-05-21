window._ = require('lodash');

try {
	window.$ = window.jQuery = require('jquery');

	try {
		require('bootstrap');
	} catch (bootstrapException) {
		console.log({
			'message': 'Failed to load Bootstrap',
			'exception': bootstrapException
		});
	}
} catch (jQueryException) {
	console.log({
		'message': 'Failed to load jQuery',
		'exception': jQueryException
	});
}

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
