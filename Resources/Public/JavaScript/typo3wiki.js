function typo3wiki_toogleExample($) {
	console.log('func');
	$(document).ready(function(){
		$('.tx_typo3wiki_examples_content').hide();
		$('.tx_typo3wiki_examples_toggle').click(function(){
			$('.tx_typo3wiki_examples_content').toggle('slow');
		});
	});
}

// Only do anything if jQuery isn't defined
if (typeof jQuery == 'undefined') {
	console.log('undefined!');
	if (typeof $ == 'function') {
		// warning, global var
		thisPageUsingOtherJSLibrary = true;
	}
	function getScript(url, success) {
		var script     = document.createElement('script');
		     script.src = url;
		var head = document.getElementsByTagName('head')[0],
		done = false;
		// Attach handlers for all browsers
		script.onload = script.onreadystatechange = function() {
			if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) {
			done = true;
				// callback function provided as param
				success();
				script.onload = script.onreadystatechange = null;
				head.removeChild(script);	
			};
		};
		head.appendChild(script);
	};
	getScript('https://code.jquery.com/jquery-1.10.1.min.js', function() {
		if (typeof jQuery=='undefined') {
			// Super failsafe - still somehow failed...
		} else {
			// jQuery loaded! Make sure to use .noConflict just in case
			fancyCode();
			if (thisPageUsingOtherJSLibrary) {
				// Run your jQuery Code
				typo3wiki_toogleExample(jQuery);
			} else {
				// Use .noConflict(), then run your jQuery Code
				typo3wiki_toogleExample(jQuery);
			}
		}
	});
} else { // jQuery was already loaded
	// Run your jQuery Code
	console.log('defined!');
	typo3wiki_toogleExample(jQuery);
};