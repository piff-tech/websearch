<?php

	require_once('payload.php');

	class Attachment extends Payload {

		private $fallback; 
		private $color;
		private $pretext;
		private $authorName, $authorLink, $authorIcon;
		private $title, $titleLink;

		private $fields;

		private $imageURL, $thumbURL;

		// function Attachments($responseType, $fallback, $color, $pretext, $authorName, $authorLink, $authorIcon, $title, $titleLink, $text, $fields, $imageURL, $thumbURL) {
		// 	$this->responseType = 	$responseType; 
		// 	$this->fallback 	=	$fallback;
		// 	$this->color 		= 	$color;
		// 	$this->pretext 		= 	$pretext;
		// 	$this->authorName	= 	$authorName;
		// 	$this->authorLink	= 	$authorLink;
		// 	$this->authorIcon	= 	$authorIcon;
		// 	$this->title 		=	$title;
		// 	$this->title_link	= 	$title_link;
		// 	$this->text 		= 	$text;
		// 	$this->fields 		=	$fields;
		// 	$this->imageURL 	=  	$imageURL;
		// 	$this->thumbURL 	=	$thumbURL;
		// }


		function __construct($fallback, $color, $title, $fields) {
			parent::__construct("in_channel", NULL, NULL);
			$this->fallback 	=	$fallback;
			$this->color 		= 	$color;
			
			$this->title 		=	$title;
			
			$this->fields 		=	$fields;
		}



		public function setFallback($fallback)			{	$this->fallback 	=	$fallback 	;	}
		public function setColor($color)				{	$this->color 		= 	$color 		;	}
		public function setPretext($pretext)			{	$this->pretext 		= 	$pretext 	;	}
		public function setAuthorName($authorName)		{	$this->authorName 	= 	$authorName ;	}
		public function setAuthorLink($authorLink)		{	$this->authorLink 	= 	$authorLink ;	}
		public function setAuthorIcon($authorIcon)		{	$this->authorIcon 	= 	$authorIcon	;	}
		public function setTitle($title)				{	$this->title 		= 	$title 		;	}
		public function setTitleLink($titleLink)		{	$this->titleLink 	= 	$titleLink 	;	}
		public function setText($text)					{	$this->text 		= 	$text 		;	}
		public function setFields($fields)				{	$this->fields 		= 	$fields 	;	}
		public function setImageURL($imageURL)			{	$this->imageURL 	= 	$imageURL 	;	}
		public function setThumbURL($thumbURL)			{	$this->thumbURL 	= 	$thumbURL 	;	}

		public function getFallbackJSON()				{	return $this->getJSONObject	("fallback", 		$this->fallback 	);	}
		public function getColorJSON()					{	return $this->getJSONObject	("color", 			$this->color 		);	}
		public function getPretextJSON()				{	return $this->getJSONObject	("pretext", 		$this->pretext 		);	}
		public function getAuthorNameJSON()				{	return $this->getJSONObject	("author_name",		$this->authorName 	);	}
		public function getAuthorLinkJSON()				{	return $this->getJSONObject	("author_link", 	$this->authorLink 	);	}
		public function getAuthorIconJSON()				{	return $this->getJSONObject	("author_icon", 	$this->authorIcon 	);	}
		public function getTitleJSON()					{	return $this->getJSONObject	("title", 			$this->title 		);	}
		public function getTitleLinkJSON()				{	return $this->getJSONObject	("title_link",		$this->titleLink 	);	}
		public function getTextJSON()					{	return $this->getJSONObject	("text", 			$this->text 		);	}
		public function getImageURLJSON()				{	return $this->getJSONObject	("image_url", 		$this->imageURL 	);	}
		public function getThumbURLJSON()				{	return $this->getJSONObject	("thumb_url", 		$this->thumbURL 	);	}



		public function getFieldsJSON()	{	
			$payload = array();
			for($i = 0, $size = count($this->fields); $i < $size; ++$i) {
    			array_push ($payload, $this->fields[$i]->getPayloadJSON()); 
			}		

			return $this->getJSONObjectNoQuote("fields", '[ '.join(", ", $payload).' ]');
		}



		public function buildPayloadArray()	{
			$this->payloadArray = array();

			if(!is_null($this->fallback))	{	array_push(	$this->payloadArray, $this->getFallbackJSON() 	);		}
			if(!is_null($this->color)) 		{	array_push(	$this->payloadArray, $this->getColorJSON()		); 		}
			if(!is_null($this->pretext)) 	{	array_push(	$this->payloadArray, $this->getPretextJSON()	); 		}
			if(!is_null($this->authorName))	{	array_push(	$this->payloadArray, $this->getAuthorNameJSON()	);		}
			if(!is_null($this->authorLink))	{	array_push(	$this->payloadArray, $this->getAuthorLinkJSON()	);		}
			if(!is_null($this->authorIcon)) {	array_push(	$this->payloadArray, $this->getAuthorIconJSON()	);	 	}
			if(!is_null($this->title)) 		{	array_push(	$this->payloadArray, $this->getTitleJSON()		);		}
			if(!is_null($this->titleLink))  {	array_push(	$this->payloadArray, $this->getTitleLinkJSON()	); 		}
			if(!is_null($this->text)) 		{	array_push(	$this->payloadArray, $this->getTextJSON()		);		}
			if(!is_null($this->fields)) 	{	array_push(	$this->payloadArray, $this->getFieldsJSON()		); 		}
			if(!is_null($this->imageURL)) 	{	array_push(	$this->payloadArray, $this->getImageURLJSON()	); 		}
			if(!is_null($this->thumbURL)) 	{	array_push(	$this->payloadArray, $this->getThumbURLJSON()	); 		}

		}


		public function getPayloadJSON() {
			$this->buildPayloadArray();
			return '[ {'.join(", ", $this->payloadArray).'} ]';
		}

		public function getSendablePayloadJSON() {
			$this->buildPayloadArray();
			return $this->getJSONObjectNoQuote("attachments", '[ {'.join(", ", $this->payloadArray).'} ]');
		}

		public function getSendableJSON() {
			return 'payload={ "response_type": "in_channel",'.$this->getSendablePayloadJSON().' }';
		}
	}

?>