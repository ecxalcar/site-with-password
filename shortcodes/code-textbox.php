<?php

function textbox($atts, $content = null) {


	$data = shortcode_atts(array(
		'text-color'        => '#000000',
		'background-color'  => '#ffffff',
		'font-size'  		=> '20',
	), $atts);

// Generando el string con estilos en línea:
	$style = "style= 'color:    {$data['text-color']}; 
			background-color:   {$data['background-color']}; 
			font-size:          {$data['font-size']}px;
	'";

	// Aplico el texto y el stilo a la etiqeta <p>
	return "<p class='sc-textbox' {$style}>{$content}</p>";

	$html = '';
	$html .= '<p class="sc-textbox" '. $style .'>'. $content .'</p>';

	
	return $html;
}

add_shortcode('new_textbox', 'textbox');
