<?php
require_once 'class-button.php';

class WP_Sns_Shere_Button_Share_Hatebu extends WP_Sns_Share_Buttons_Button {
  protected $api = 'http://b.hatena.ne.jp/add';
  public $name = 'hatbu';
  public $icon = 'icon-hatebu';
  Public $label = 'Bookmark';
  public $width = 505;
  public $height = 500;

  protected function get_query() {
    return array(
      'mode' => 'confirm',
      'url' => $this->url
    );
  }

  public function count() {
    if(function_exists('scc_get_share_hatebu')) {
      return scc_get_share_hatebu();
    }
  }

  public function get_label() {
    return __('Bookmark', 'wp-sns-share-buttons');
  }
}
