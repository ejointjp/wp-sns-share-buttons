<?php
require_once 'class-button.php';

class WP_Sns_Share_Buttons_Share_Twitter extends WP_Sns_Share_Buttons_Button {
  protected $api = 'https://twitter.com/intent/tweet';
  public $name = 'twitter';
  public $icon = 'icon-twitter';
  public $label = 'Tweet';
  public $width = 600;
  public $height = 473;

  public function __construct() {
    parent::__construct();
  }

  protected function get_account() {
    $account = get_option('wpssb-setting')['twitter-via'];
    return preg_replace('/^@/', '', $account);
  }

  protected function get_query() {
    return array(
      'url' => $this->url,
      'text' => $this->title,
      'via' => $this->get_account()
    );
  }

  public function count() {
    if(function_exists('scc_get_share_twitter')) {
      return scc_get_share_twitter();
    }
  }
}
