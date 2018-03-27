<?php
require_once 'class-button.php';

class WP_Sns_Share_Buttons_Follow_Facebook extends WP_Sns_Share_Buttons_Button {
  protected $api = 'https://www.facebook.com/';
  public $name = 'facebook';
  public $icon = 'icon-facebook';
  public $page_segment;
  public $target = '_blank';

  public function __construct() {
  }

  public function href() {
    $segment = get_option('wpssb-setting')['facebook-segment'];
    return $this->api . $this->page_segment;
  }

  public function props() {
    if($this->$target) {
      $target = ' target="' . $this->target . '"';
    }
    return sprintf('href="%s"%s', $this->href(), $target);
  }

  public function count() {
    if(function_exists('scc_get_follow_facebook')) {
      return scc_get_follow_facebook();
    }
  }

  public function get_label() {
    return __('Follow', 'wp-sns-share-buttons');
  }
}
