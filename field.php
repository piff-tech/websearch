<?php
	require_once('payload.php');

	class Field extends Payload {


		private $title;
		private $value;
		private $short;

		function __construct($title, $value, $short) {
			parent::__construct("in_channel", NULL, NULL);
			$this->title = $title;
			$this->value = $value;
			$this->short = $short;
			$this->payloadArray = array();
		}

		public function setTitle($title)			{	$this->title 	=	$title 	;	}
		public function setValue($value)			{	$this->value 	= 	$value 	;	}
		public function setShort($short)			{	$this->short 	= 	$short 	;	}

		public function getTitleJSON()				{	return $this->getJSONObject	("title",		$this->title 	);	}
		public function getValueJSON()				{	return $this->getJSONObject	("value", 		$this->value 	);	}
		public function getShortJSON()				{	return $this->getJSONObjectNoQuote("short", $this->short	);	}


		public function buildPayloadArray()	{
			if(!is_null($this->title))		{	array_push(	$this->payloadArray, $this->getTitleJSON()  );	}
			if(!is_null($this->value)) 		{	array_push(	$this->payloadArray, $this->getValueJSON()	); 	}
			if(!is_null($this->short)) 		{	array_push(	$this->payloadArray, $this->getShortJSON()	); 	}
		}

		// public function getPayloadJSON() {
		// 	$this->buildPayloadArray();
		// 	return '{'.join(", ", $this->payloadArray).'}';
		// }
	}




?>