<?php
class MixpanelHelper extends AppHelper{
	public function embed($inline = true) {
		$include = <<<JS
<script type='text/javascript'>
var mp_protocol = (('https:' == document.location.protocol) ? 'https://' : 'http://');
document.write(unescape('%3Cscript src="' + mp_protocol + 'api.mixpanel.com/site_media/js/api/mixpanel.js" type="text/javascript"%3E%3C/script%3E'));
</script>
<script type='text/javascript'>
try {
	var mpmetrics = new MixpanelLib(TOKEN); 
} catch(err) {
	null_fn = function () {};
	var mpmetrics = {  track: null_fn,  track_funnel: null_fn,  register: null_fn,  register_once: null_fn, register_funnel: null_fn };
}
TRACKERS
</script>
JS;

		$settings = Configure::read('Mixpanel.settings');
		$events = Configure::read('Mixpanel.events');

		if (empty($events)) {
			return '';
		}

		$trackers = array();
		foreach ($events as $event) {
			$properties = $event['properties'];
			$properties = array_merge($settings['properties'], $properties);

			$trackers[] = sprintf(
				'mpmetrics.track(%s, %s);',
				json_encode($event['event']),
				(!empty($properties))
					? json_encode($properties)
					: '{}'
			);
		}

		return str_replace(
			array('TOKEN', 'TRACKERS'),
			array(json_encode($settings['token']), join("\n", $trackers)),
			$include
		);
	}
}
?>