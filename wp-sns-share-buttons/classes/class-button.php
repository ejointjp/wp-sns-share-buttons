<?php
abstract class WP_Sns_Share_Buttons_Button {

  protected $api;
  public $name;
  public $icon;
  public $width;
  public $height;

  public $title;
  public $url;

  public $show_count = true;

  public function __construct() {
    $this->init();
  }

  private function init() {
    $this->title = get_the_title() . ' ' . apply_filters('document_title_separator', '') . ' ' . get_bloginfo('name');
    $this->url = get_permalink();

  }

  public function href() {
    if($param = $this->get_param()) {
      return $this->api . '?' . $param;

    } else {
      return $this->api;
    }
  }

  private function get_param() {
    if($array = $this->get_query()) {

      foreach($array as $key => $val) {
        if($val) {
          $temp[] = $key . '=' . rawurlencode($val);
        }
      }
      return implode('&', $temp);

    } else {
      return false;
    }
  }

  public function onclick() {
    $width = is_null($this->width) ? null : ',width=' . $this->width;
    $height = is_null($this->height) ? null : ',height=' . $this->height;

    return sprintf('javascript:window.open(this.href, \'\',\'menubar=no,toolbar=no,resizable=yes,scrollbars=yes%s%s\');return false;', $width, $height);
  }

  public function props() {
    return sprintf('href="%s" onclick="%s"', $this->href(), $this->onclick());
  }

  public function count_tag() {

    if(!is_null($this->count()) && $this->count() != 0 && $this->show_count) {
      return sprintf('<span class="social-button__count__span">%s</span>', $this->count());
    }
  }

  abstract public function count();
}
