<?php

$title = get_input('title');
$route_name = get_input('route_name');

if (empty($route_name)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$guid = get_input('guid');
if (!empty($guid)) {
	$tour = get_entity($guid);
	if (!$tour instanceof \FeatureTour || !$tour->canEdit()) {
		return elgg_error_response(elgg_echo('actionunauthorized'));
	}
} else {
	$tour = new FeatureTour();
	$tour->save();
}

$tour->title = $title;
$tour->route_name = $route_name;

$steps = get_input('steps', []);

$steps_config = [];
if (isset($steps['element'])) {
	foreach ($steps['element'] as $index => $value) {
		if (empty($value)) {
			continue;
		}
		
		$steps_config[] = json_encode([
			'element' => $value,
			'popover' => [
				'title' => elgg_extract($index, $steps['title']),
				'description' => elgg_extract($index, $steps['description']),
				'position' => elgg_extract($index, $steps['position']),
			],
		]);
	}
}

$tour->steps_config = $steps_config;

return elgg_ok_response('', elgg_echo('save:success'));
