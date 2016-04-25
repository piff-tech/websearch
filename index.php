<?php
	include 'payload.php';
	include 'field.php';
	include 'attachments.php';

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	// phpinfo();
	# Incoming values #
	$text = $_POST['text'];
	$channel_name = $_POST['channel_name'];
	$user_id = $_POST['user_id'];
	$user_name = $_POST['user_name'];
	$response_type = "in_channel";
	$response_url = $_POST['response_url'];

	// $text = "text";
	// $channel_name = "channel_name";
	// $user_id = "user_id";
	// $user_name = "user_name";
	// $response_type = "response_type";
	// $response_url = "response_url";

	# Split text to search for command #
	$text = explode(" ", $text);

	function getCommand($command, &$array) {
		if(in_array($command, $array)) { 
			unset($array[array_search($command, $array)]);
			$array = array_values($array);
			$command = TRUE;
			return $command;
		}

		return FALSE;
	}

		
	$commandSearch = getCommand("-search", $text) || getCommand("-s", $text);
	$commandDictionary = getCommand("-dictionary", $text) || getCommand("-d", $text) || getCommand("-dic", $text) || getCommand("-dict", $text);
	$commandSocial = getCommand("-social", $text) || getCommand("-soc", $text);
	$commandMedia = getCommand("-media", $text) || getCommand("-m", $text);
	$commandNews = getCommand("-news", $text) || getCommand("-n", $text);

	if(!$commandSearch && !$commandDictionary && !$commandSocial && !$commandMedia && !$commandNews && !$commandIBM && !$commandStorage) { $all = TRUE; }
	else {$all = FALSE; }


	$text = join(" ", $text);
	$curl = curl_init();


	# Build Links #
	$lmgtfy = "http://lmgtfy.com/?q=".rawurlencode($text);
	$google = "https://www.google.co.uk/?gfe_rd=cr&ei=-4ttVv7eOLDj8wfF153gBA&gws_rd=ssl#q=".rawurlencode($text);
	$duckDuckGo = "https://duckduckgo.com/?q=".rawurlencode($text);
	$bing = "http://www.bing.com/search?q=".rawurlencode($text);


	$urbandictionary = "http://www.urbandictionary.com/define.php?term=".rawurlencode($text);
	$dictionary = "http://dictionary.reference.com/browse/".rawurlencode($text);

	$facebook = "https://www.facebook.com/search/top/?init=quick&q=".rawurlencode($text);
	$twitter = "https://twitter.com/search?q=".rawurlencode($text);


	$youtube = "https://www.youtube.com/results?search_query=".rawurlencode($text);
	$googleVideo = "https://www.google.co.uk/search?tbm=vid&hl=en&source=hp&q=".rawurlencode($text);
	$soundCloud = "https://soundcloud.com/search?q=".rawurlencode($text);
	$liveLeak = "http://www.liveleak.com/browse?q=".rawurlencode($text);
	$hnhh = "http://www.hotnewhiphop.com/search/".rawurlencode($text);
	$audiomack = "http://www.audiomack.com/search?q=".rawurlencode($text);

	$BBCNews = "http://www.bbc.co.uk/search?q".rawurlencode($text).'&filter=news&suggid=';
	$skyNews = "http://news.sky.com/search?term=".rawurlencode($text);
	$googleNews = "https://www.google.co.uk/search?hl=en&gl=uk&tbm=nws&authuser=0&q=".rawurlencode($text);
	
	$curlget = curl_init();
	$curlConfig = array(
    CURLOPT_URL            => "http://api.embed.ly/1/oembed?key=21c4a6fe80e24f019bc6d80a2b9375cf&url=".$google,
    CURLOPT_RETURNTRANSFER => true
	);
	curl_setopt_array($curlget, $curlConfig);
	// print "http://api.embed.ly/1/oembed?key=21c4a6fe80e24f019bc6d80a2b9375cf&url=".$google;
	// print curl_exec($curlget);
	$short = json_decode(curl_exec($curlget));





	function makeLink($title, $link) {	return '<'.rawurlencode($link).'|'.$title.'>';	}

	$introText = ( new Payload("in_channel", '<@'.$user_id.'|'.$user_name.'> wants to search for '.$text, NULL ) )->getSendableJSON();

	# Build search attachment #
	$fields = array(new Field("Google", makeLink("Your best bet..", $google), "true" ), new Field("LMGTFY", makeLink("Feeling stupid?", $lmgtfy), "true" ), 
				new Field("DuckDuckGO", makeLink("Tin hat time?", $duckDuckGo), "true" ), new Field("Bing", makeLink("Fancy a challenge?", $bing), "true" ) ); 
	$searchAttachment = new Attachment( "Search Engines", "danger", "Search Engines", $fields);
	$searchAttachment->setThumbURL($short->{'thumbnail_url'});
	// print $short->{'thumbnail_url'};
	$searchAttachment = (new Payload("in_channel", NULL, $searchAttachment))->getSendableJSON();

	# Build dictionary attachment #
	$fields = array(new Field("Dictionary.com", makeLink("Feeling classic?", $dictionary), "true" ), new Field("Urban Dictionary", makeLink("Translate Life..", $urbandictionary), "true" ) ); 
	$dictionaryAttachment = new Attachment( "Dictionary Search", "#439FE0", "Dictionary Search", $fields);
	$dictionaryAttachment = (new Payload("in_channel", NULL, $dictionaryAttachment))->getSendableJSON();

 
	# Build social attachment #
	$fields = array(new Field("Facebook", makeLink("Stalk time?", $facebook), "true" ), new Field("Twitter", makeLink("Chirp.", $twitter), "true" ) ); 
	$socialAttachment = new Attachment( "Social Search", "good", "Social Search", $fields);
	$socialAttachment = (new Payload("in_channel", NULL, $socialAttachment))->getSendableJSON();

	# Build media attachment #
	$fields = array(new Field("YouTube", makeLink("Now featuring adverts!", $youtube), "true" ),
					new Field("Google Video", makeLink("Yes, this still exists.", $googleVideo), "true"),
					new Field("Sound Cloud", makeLink("Amateur hour?", $soundCloud), "true" ), 
					new Field("Live Leak", makeLink("Feeling conspiratorial? ", $liveLeak), "true" ),
					new Field("HNHH", makeLink("Internet Gangster..", $hnhh), "true" ), 
					new Field("Audio Mack", makeLink("Keyboard Warrior..", $audiomack), "true" ) ); 
 
	$mediaAttachment = new Attachment( "Media Search", "danger", "Media Search", $fields);
	$mediaAttachment = (new Payload("in_channel", NULL, $mediaAttachment))->getSendableJSON();

	# Build news attachments #
	$fields = array(new Field("BBC News", makeLink('Bias != Neutral', $BBCNews), "true" ), new Field("Sky News", makeLink("Scandal!", $skyNews), "true" ), 
					new Field("Google News", makeLink("Google FTW!", $googleNews), "true" ) ); 

	$newsAttachment = new Attachment( "News Search", "#0000ff", "News Search", $fields);
	$newsAttachment = (new Payload("in_channel", NULL, $newsAttachment))->getSendableJSON();
	
	

	# Build IBM attachment #
	$fields = array(new Field("Faces.Tap", makeLink("IBMs Facebook", $facesTap), "true" ), new Field("Blue Pages", makeLink("Feeling blue?", $bluePages), "true" ), 
					new Field("w3", makeLink("Slightly broader..", $w3), "true" ) ); 

	$IBMAttachment = new Attachment( "IBM Search", "#0000ff", "IBM Search", $fields);
	$IBMAttachment = (new Payload("in_channel", NULL, $IBMAttachment))->getSendableJSON();
	
	
	# Build storage attachment #
	$fields = array(new Field("Wiki", makeLink("Hungry 4 Info?", $wiki), "true" ), new Field("RTC Defect", makeLink("If it ain't broke..", $RTC), "true" ) ); 
	$storageAttachment = new Attachment( "Storage Search", "#F35A00", "Storage Only", $fields);
	$storageAttachment = (new Payload("in_channel", NULL, $storageAttachment))->getSendableJSON();


	$curlConfig = array(
	    CURLOPT_URL            => $response_url,
	    CURLOPT_POST           => true,
	    CURLOPT_RETURNTRANSFER => true,
		);

	curl_setopt_array($curl, $curlConfig);

	curl_setopt($curl, CURLOPT_POSTFIELDS, $introText);

	$result = curl_exec($curl);

	if($commandSearch || $all){
		curl_setopt($curl, CURLOPT_POSTFIELDS, $searchAttachment);

		$result = curl_exec($curl);
	}
	
	if($commandDictionary) {
		curl_setopt($curl, CURLOPT_POSTFIELDS, $dictionaryAttachment);

		$result = curl_exec($curl);
	}
	
	if($commandSocial || $all) {
		curl_setopt($curl, CURLOPT_POSTFIELDS, $socialAttachment);

		$result = curl_exec($curl);
	}

	if($commandMedia ) {
		curl_setopt($curl, CURLOPT_POSTFIELDS, $mediaAttachment);

		$result = curl_exec($curl);
	}

	if($commandNews ) {
		curl_setopt($curl, CURLOPT_POSTFIELDS, $newsAttachment);

		$result = curl_exec($curl);
	}

	if($commandIBM || $all) {
		curl_setopt($curl, CURLOPT_POSTFIELDS, $IBMAttachment);

		$result = curl_exec($curl);
	}

	if($commandStorage || $all) {
		curl_setopt($curl, CURLOPT_POSTFIELDS, $storageAttachment);

		$result = curl_exec($curl);
	}

	curl_close($curl);


?>