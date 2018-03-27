<?php
// include_once plugin_basename('wp-sns-share-buttons.php');
require_once dirname(__FILE__) . '/../wp-sns-share-buttons.php';
require_once 'class-button.php';
require_once 'class-share-twitter.php';
require_once 'class-share-facebook.php';
require_once 'class-share-google-plus.php';
require_once 'class-share-hatebu.php';
require_once 'class-share-pocket.php';
require_once 'class-follow-twitter.php';
require_once 'class-follow-facebook.php';
require_once 'class-follow-feedly.php';

class WP_Sns_Share_Buttons_Buttons {

  public $options;
  public $share_buttons = array();
  public $follow_buttons = array();

  public function __construct() {
    global $wpssb;
    $this->options = $wpssb->options;
    $this->textdomain = $wpssb->textdomain;
    $this->set_share_buttons();
    $this->set_follow_buttons();
  }

  public function set_share_buttons() {
    if($this->options['share-twitter'] !== '1') {
      $button = new WP_Sns_Share_Buttons_Share_Twitter();
      $button->show_count = !$this->options['hide-count'];
      $this->share_buttons[] = $button;
    }
    if($this->options['share-facebook'] !== '1') {
      $button = new WP_Sns_Share_Buttons_Share_Facebook();
      $button->show_count = !$this->options['hide-count'];
      $this->share_buttons[] = $button;
    }
    if($this->options['share-google-plus'] !== '1') {
      $button = new WP_Sns_Share_Buttons_Share_Google_Plus();
      $button->show_count = !$this->options['hide-count'];
      $this->share_buttons[] = $button;
    }
    if($this->options['share-hatebu'] !== '1') {
      $button = new WP_Sns_Shere_Button_Share_Hatebu();
      $button->show_count = !$this->options['hide-count'];
      $this->share_buttons[] = $button;
    }
    if($this->options['share-pocket'] !== '1') {
      $button = new WP_Sns_Shere_Button_Share_Pocket();
      $button->show_count = !$this->options['hide-count'];
      $this->share_buttons[] = $button;
    }
  }

  public function set_follow_buttons() {
    if($this->options['follow-twitter'] !== '1' && $this->options['twitter-via']) {
      $button = new WP_Sns_Share_Buttons_Follow_Twitter();
      $button->show_count = !$this->options['hide-count'];
      $this->follow_buttons[] = $button;
    }
    if($this->options['follow-facebook'] !== '1' && $this->options['facebook-segment']) {
      $button = new WP_Sns_Share_Buttons_Follow_Facebook();
      $button->show_count = !$this->options['hide-count'];
      $this->follow_buttons[] = $button;
    }
    if($this->options['follow-feedly'] !== '1') {
      $button = new WP_Sns_Share_Buttons_Follow_Feedly();
      $button->show_count = !$this->options['hide-count'];
      $this->follow_buttons[] = $button;
    }
  }

  private function get_buttons_html($buttons) {
    if(is_array($buttons) && count($buttons) > 0) {

      $html = '<div class="wpssb">';

      foreach($buttons as $button) {
        $html .= $this->show($button);
      }

      $html .= '</div>';

      return $html;
    }
  }

  public function show_share() {
    $buttons = $this->share_buttons;
    return $this->get_buttons_html($buttons);
  }

  public function show_follow() {
    $buttons = $this->follow_buttons;
    return $this->get_buttons_html($buttons);
  }

  public function show_all() {
    $html = $this->show_share();
    $html .= $this->show_follow();

    return $html;
  }

  public function show($obj) {
    $html = sprintf('<a class="wpssb__item social-button__item--%s" %s>', $obj->name, $obj->props());
    $html .= sprintf('<i class="wpssb__icon %s"></i>', $obj->icon);
    $html .= sprintf('<span class="wpssb__text">%s</span>', __($obj->get_label(), $this->textdomain));
    $html .= sprintf('<span class="wpssb__count">%s</span>', $obj->count_tag());
    $html .= '</a>';

    return $html;
  }
}
