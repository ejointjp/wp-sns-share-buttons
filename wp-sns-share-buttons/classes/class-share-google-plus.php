<?php
require_once 'class-button.php';

class WP_Sns_Share_Buttons_Share_Google_Plus extends WP_Sns_Share_Buttons_Button {
  protected $api = 'https://plus.google.com/share';
  public $name = 'google-plus';
  public $icon = 'icon-google-plus';
  public $width = 500;
  public $height = 600;

  protected function get_query() {
    return array(
      'url' => $this->url
    );
  }

  public function count() {
    if(function_exists('scc_get_share_gplus')) {
      return scc_get_share_gplus();
    }
  }

  public function get_label() {
    return __('+1', 'wp-sns-share-buttons');
  }
}
