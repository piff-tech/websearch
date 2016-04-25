<?php

	class Payload {

		protected $payloadArray;
		protected $payloadJSON;

		private $responseType;
		protected $text;

		private $attachment;


		function __construct($responseType, $text, $attachment) {
			$this->payloadArray =	array();

			$this->responseType	= 	$responseType;

			$this->text 		= 	$text;

			$this->attachment 	=	$attachment;
		}

		public function getJSONObject($key, $value) 		{return '"'.$key.'": "'.$value.'"'	;}
		public function getJSONObjectNoQuote($key, $value) 	{return '"'.$key.'": '.$value		;}


		public function setResponseType($responseType)		{	$this->responseType 	=	$responseType 	;	}
		public function setText($text)						{	$this->text 			= 	$text 			;	}
		public function setAttachment($attachment)			{	$this->attachment 		= 	$attachment 	;	}



		public function getResponseTypeJSON()		{	return $this->getJSONObject	("response_type", 	$this->responseType	);	}
		public function getTextJSON()				{	return $this->getJSONObject	("text", 			$this->text 		);	}
		public function getAttachmentJSON()			{	return $this->attachment->getSendablePayloadJSON();	}



		public function buildPayloadArray()	{

			if(!is_null($this->responseType))	{	array_push(	$this->payloadArray, $this->getResponseTypeJSON() 	);		}
			if(!is_null($this->text)) 			{	array_push(	$this->payloadArray, $this->getTextJSON()			); 		}
			if(!is_null($this->attachment)) 	{	array_push(	$this->payloadArray, $this->getAttachmentJSON()		); 		}

		}

		public function getPayloadJSON() {
			$this->buildPayloadArray();
			return '{'.join(", ", $this->payloadArray).'}';
		}

		public function getSendableJSON() {
			return 'payload= '.$this->getPayloadJSON();
		}
	}
?>