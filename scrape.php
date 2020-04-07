<?php
	header('Content-Type: application/json');
	include('simple_html_dom.php');
	$html = file_get_html("https://www.sportsinteraction.com/specials/us-elections-betting/");
	$divs = $html->find('div');
	foreach ($divs as $i=>$div) {
		if (isset($div->attr["data-component"]) && ($div->attr["data-component"]=="event_types/Show")) {
			$events_string=$div->attr["data-props"];
		}
	}
	$string_to_array=json_decode(html_entity_decode($events_string), true);
	
	$json_games=[];
	$game_options=[];
	
	foreach ($string_to_array['games'] as $i=>$game) {
		foreach ($game['betTypeGroups'] as $bet_type_group) {
			foreach ($bet_type_group['betTypes'] as $bet_type) {
				foreach ($bet_type['events'] as $event) {
					$game_options=[];
					foreach ($event['runners'] as $runner) {						
						$game_options[]=[
						'Outcome' => $runner['runner'],
						'Odds' => number_format((round($runner['currentPrice'], 2)+1), 2, '.', ''),
						];
					}
				}
			}
		}
		$json_games[]=[
		'BetName' => $game['gameName'],
		'BetOptions'=>$game_options
		];	
	}
	echo json_encode($json_games);
?>										