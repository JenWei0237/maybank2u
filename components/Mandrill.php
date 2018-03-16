<?php

namespace app\components;

use Yii;
use yii\base\Component;

class Mandrill extends Component
{
	protected $_mandrill;
	public $api_key;
	public $company_logo;
	public $company_name;
	public $from_name;
	public $from_email;
	public $reply_to_email;
	public $message;

	public function init()
	{
		parent::init();
		$this->_mandrill = new \Mandrill($this->api_key);
		$this->initMessage();
	}

	public function initMessage()
	{
		$this->message = [
			'html' => '',
			'text' => '',
			'subject' => '',
			'from_name' => $this->from_name,
			'from_email' => $this->from_email,
			'to' => [],
			'headers' => [
				'Reply-To' => $this->reply_to_email,
			],
			'important' => true,
			'track_opens' => false,
			'track_clicks' => false,
			'auto_text' => true,
			'auto_html' => false,
			'inline_css' => true,
			'url_strip_qs' => false,
			'preserve_recipients' => false,
			'view_content_link' => false,
			'merge' => true,
			'global_merge_vars' => [],
			'merge_vars' => [],
			'tags' => [],
		];
	}

	public function getInstance()
	{
		return $this->_mandrill;
	}
}
