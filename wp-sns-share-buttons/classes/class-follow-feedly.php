<?php
require_once 'class-button.php';

class WP_Sns_Share_Buttons_Follow_Feedly extends WP_Sns_Share_Buttons_Button {
  protected $api = 'http://feedly.com/i/subscription/feed';
  public $name = 'feedly';
  public $icon = 'icon-feedly';
  public $target = '_blank';

  public function __construct() {
    parent::__construct();
  }

  public function get_feed_url() {
    $feed = get_option('wpssb-setting')['follow-feedly'];

    if($feed !== '') {
      $url = get_bloginfo($feed);
    } else {
      $url = get_bloginfo('rss2_url');
    }

    return $url;
  }

  public function href() {
    return $this->api . rawurlencode('/' . $this->get_feed_url());
  }

  public function props() {
    if($this->$target) {
      $target = ' target="' . $this->target . '"';
    }
    return sprintf('href="%s"%s', $this->href(), $target);
  }

  public function count() {
    if(function_exists('scc_get_follow_feedly')) {
      return scc_get_follow_feedly();
    }
  }

  public function get_label() {
    return __('Follow', 'wp-sns-share-buttons');
  }
}
