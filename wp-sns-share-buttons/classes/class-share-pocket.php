<?php
require_once 'class-button.php';

class WP_Sns_Shere_Button_Share_Pocket extends WP_Sns_Share_Buttons_Button {
  protected $api = 'http://getpocket.com/edit';
  public $name = 'pocket';
  public $icon = 'icon-pocket';
  public $label = 'Pocket';
  public $width = 500;
  public $height = 350;

  protected function get_query() {
    return array(
      'url' => $this->url,
      'title' => $this->title
    );
  }

  public function count() {
    if(function_exists('scc_get_share_pocket')) {
      return scc_get_share_pocket();
    }
  }
}
