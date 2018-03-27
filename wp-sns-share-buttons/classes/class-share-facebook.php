<?php
require_once 'class-button.php';

class WP_Sns_Share_Buttons_Share_Facebook extends WP_Sns_Share_Buttons_Button {
  protected $api = 'https://www.facebook.com/sharer.php';
  public $name = 'facebook';
  public $icon = 'icon-facebook';
  public $width = 600;
  public $height = 300;

  protected function get_query() {
    return array(
      'src' => 'b',
      'u' => $this->url,
      't' => $this->title
    );
  }

  public function count() {
    if(function_exists('scc_get_share_facebook')) {
      return scc_get_share_facebook();
    }
  }

  public function get_label() {
    return __('Share', 'wp-sns-share-buttons');
  }
}
