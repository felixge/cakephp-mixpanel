<?php
class MixpanelComponent extends Object{
	public $Controller;

	public $settings = array(
		'token' => null,
		'properties' => array()
	);

	public $events = array();

	function startup(&$Controller, $settings = array()) {
		$this->Controller = &$Controller;
		$this->settings = array_merge($this->settings, $settings);
	}

	public function beforeRender() {
		Configure::write('Mixpanel.events', $this->events);
		Configure::write('Mixpanel.settings', $this->settings);
		$this->Controller->helpers[] = 'Mixpanel.Mixpanel';
	}

	function track($event, $properties = array()) {
		$this->events[] = compact('event', 'properties');
	}

	function trackInternal($event, $properties = array()) {
		$this->events[] = compact('event', 'properties');
	}
}

?>