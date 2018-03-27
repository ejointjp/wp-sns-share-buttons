<?php
require_once 'class-share-twitter.php';

class WP_Sns_Share_Buttons_Follow_Twitter extends WP_Sns_Share_Buttons_Share_Twitter {
  protected $api = 'https://twitter.com/intent/follow';
  public $width = 600;
  public $height = 600;

  protected function get_query() {
    return array(
      'screen_name' => $this->get_account()
    );
  }

  public function count() {
    if(function_exists('scc_get_follow_twitter')) {
      return scc_get_follow_twitter();
    }
  }

  public function get_label() {
    return __('Follow', 'wp-sns-share-buttons');
  }
}
