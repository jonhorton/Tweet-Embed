<?php

if ( ! defined('EXT')) exit('Invalid file request');


/**
 * Tweet Embed Class
 *
 * @package   FieldFrame
 * @author    Andrew Delianides <drew.delianides@newspring.cc>
 * @copyright Copyright (c) 2011 NewSpring, Inc
 * @license   http://creativecommons.org/licenses/by-sa/3.0/ Attribution-Share Alike 3.0 Unported
 */
class Tweet_ft extends Fieldframe_Fieldtype {
	/**
	 * Fieldtype Info
	 * @var array
	 */
	var $info = array(
		'name'     => 'Tweet Embed',
		'version'  => '1.0',
		'desc'     => 'Uses Twitters API to store a tweet for later use.',
		'docs_url' => 'https://dev.twitter.com/docs/embedded-tweets',
		'no_lang'  => TRUE
	);
	
	/**
	 * Display Field
	 * 
	 * @param  string  $field_name      The field's name
	 * @param  mixed   $field_data      The field's current value
	 * @param  array   $field_settings  The field's settings
	 * @return string  The field's HTML
	 */
	function display_field($field_name, $field_data, $field_settings)
	{
		global $DSP;
		
		//$this->prep_field_data($field_data);
		$r .= $DSP->input_text($field_name, $field_data['tweet_url'], '', '', 'input', '500px', '');
		return $r;
	}
	
	/*
	/**
	 * Save Field
	 * 
	 * @param  mixed   $field_data      The field's current value
	 * @param  array   $field_settings  The field's settings
	 * @return string  The field's HTML
	 */
	function save_field( $field_data, $field_settings){
		
		if(empty($field_data)) return false; //No data, exit.
		
		if(is_numeric($field_data)){ //Even though the settings don't state it, you could input just the tweet ID and that would be sufficent.
			$tweet_id = $field_data;
		}else{
			$url = $field_data;
			preg_match("/(\\d+)$/uim",$url, $matches);
			$tweet_id = $matches[0];
		}
		
		//Get Tweet from Twitter
		$api = curl_init();
		curl_setopt($api, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($api, CURLOPT_URL, "https://api.twitter.com/1/statuses/show/$tweet_id.json?include_entities=true");
		// grab URL and pass it to the browser
		$tweet = curl_exec($api);
		// close cURL resource, and free up system resources
		curl_close($api);
		
		//Data Returned is JSON, decode as Associative array
		$tweet = json_decode($tweet, TRUE);
		
		//Don't like not using a loop here but there is so much unnessary information returned from twitter I don't need to save.
		$formatted_tweet['tweet_id'] = $tweet['id_str'];
		$formatted_tweet['tweet_text'] = $tweet['text'];
		$formatted_tweet['tweet_created_at'] = strtotime($tweet['created_at']);
		$formatted_tweet['tweet_name'] = $tweet['user']['name'];
		$formatted_tweet['tweet_screen_name'] = $tweet['user']['screen_name'];
		$formatted_tweet['tweet_url'] = "https://twitter.com/".$tweet['user']['screen_name']."/status/".$tweet['id_str'];

		//Profile Image returned from tweet api is mini, this will ensure we can get and save the bigger version.
		$api = curl_init();
		curl_setopt($api, CURLOPT_URL, "http://api.twitter.com/1/users/profile_image/".$formatted_tweet['tweet_screen_name'].".json?size=original");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($api, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($api, CURLOPT_AUTOREFERER, 1);
		curl_setopt($api, CURLOPT_FOLLOWLOCATION, 1);
		// grab URL and pass it to the browser
		curl_exec($api);
		$image = curl_getinfo($api, CURLINFO_EFFECTIVE_URL);
		// close cURL resource, and free up system resources
		curl_close($api);
		$formatted_tweet['tweet_profile_image'] = $image;
		
		//return save information
		return $formatted_tweet;
	}
	
	//
	/**
	 * Display Tag
	 *
	 * @param  array   $params          Name/value pairs from the opening tag
	 * @param  string  $tagdata         Chunk of tagdata between field tag pairs
	 * @param  string  $field_data      Currently saved field value
	 * @param  array   $field_settings  The field's settings
	 * @return string  relationship references
	 */
	function display_tag($params, $tagdata, $field_data, $field_settings)
	{
		global $TMPL;
				
		foreach ($TMPL->var_pair as $key => $val){
			if($key == "tweet_text"){
				$tweet = preg_replace("/((http|ftp)+(s)?:\\/\\/[^<>\\s]+)/uim", "<a href=\"$1\" target=\"_blank\">$1</a>\n", $field_data[$key]);
				$tweet = preg_replace("/@(\\w{1,15})/i", "<a href=\"http://twitter.com/$1/\" target=\"_blank\">@$1</a>", $tweet);	
				$field_data[$key] = $tweet;
			}
			
			$tagdata = $TMPL->swap_var_single($key, $field_data[$key], $tagdata);
		}
		
		return $tagdata;
	}	
}

?>