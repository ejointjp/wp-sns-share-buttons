<?php
/*
Plugin Name: WP SNS Share Buttons
Plugin URI: http://e-joint.jp/works/wp-sns-share-buttons/
Description: A WordPress plugin that makes SNS Share and Follow Buttons easily.
Version: 0.1.0
Author: e-JOINT.jp
Author URI: http://e-joint.jp
Text Domain: wp-sns-share-buttons
Domain Path: /languages
License: GPL2
*/

/*  Copyright 2018 e-JOINT.jp (email : mail@e-joint.jp)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
     published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once 'classes/class-buttons.php';

class WP_Sns_Share_Buttons {
  public $options;
  private $version;
  public $textdomain;
  private $domainpath;
  public $default_options = array(
    'src' => '.toc-src',
    'headings' => 'h2, h3',
    'min' => '2',
    'title' => 'Contents',
    'title_element' => 'h2',
    'excludes' => 'toc-exclude'
  );

  public function __construct(){

    $this->set_datas();
    $this->options = get_option('wpssb-setting');

    // 翻訳ファイルの読み込み
    // load_plugin_textdomain($this->textdomain, false, basename(dirname(__FILE__)) . '/languages');
    add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
    // 設定画面を追加
    add_action('admin_menu', array($this, 'add_plugin_page'));
    // 設定画面の初期化
    add_action('admin_init', array($this, 'page_init'));
    add_action('wp_enqueue_scripts', array($this, 'add_styles'));

    if($this->options['auto']) {
      add_filter('the_content', array($this, 'the_content'));
    }
  }

  public function load_plugin_textdomain() {
    load_plugin_textdomain($this->textdomain, false, dirname(plugin_basename(__FILE__)) . $this->domainpath);
  }

  private function set_datas() {
    $datas = get_file_data(__FILE__, array(
      'version' => 'Version',
      'textdomain' => 'Text Domain',
      'domainpath' => 'Domain Path'
    ));

    $this->version = $datas['version'];
    $this->textdomain = $datas['textdomain'];
    $this->domainpath = $datas['domainpath'];
  }

  // 設定画面を追加
  public function add_plugin_page() {
    add_options_page(
      __('WP SNS Share Buttons', $this->textdomain),
      __('WP SNS Share Buttons', $this->textdomain),
      'manage_options',
      'wpssb-setting',
      array($this, 'create_admin_page')
    );
  }

  // 設定画面を生成
  public function create_admin_page() { ?>
    <div class="wrap">
      <h2>WP SNS Share Buttons</h2>
      <?php
      global $parent_file;
      if($parent_file != 'options-general.php') {
        require(ABSPATH . 'wp-admin/options-head.php');
      }
      ?>

      <form method="post" action="options.php">
      <?php
        settings_fields('wpssb-setting');
        do_settings_sections('wpssb-setting');
        submit_button();
      ?>
      </form>

      <p><?php echo __('For details of setting, please see', $this->textdomain); ?> <a href="http://e-joint.jp/works/wp-sns-share-buttons/">http://e-joint.jp/works/wp-sns-share-buttons/</a></p>
    </div>
  <?php
  }

  // 設定画面の初期化
  public function page_init(){
    register_setting('wpssb-setting', 'wpssb-setting');
    add_settings_section('wpssb-setting-section-id', '', '', 'wpssb-setting');

    add_settings_field(
      'twitter-via',
      'Twitter ' . __('Account', $this->textdomain),
      array($this, 'twitter_via_callback'),
      'wpssb-setting',
      'wpssb-setting-section-id'
    );

    add_settings_field(
      'share-twitter',
      __('Tweet Button', $this->textdomain),
      array($this, 'share_twitter_callback'),
      'wpssb-setting',
      'wpssb-setting-section-id'
    );

    add_settings_field(
      'follow-twitter',
      'Twitter ' . __('Follow Button', $this->textdomain),
      array($this, 'follow_twitter_callback'),
      'wpssb-setting',
      'wpssb-setting-section-id'
    );

    add_settings_field(
      'facebook-segment',
      'Facebook URL',
      array($this, 'facebook_segment_callback'),
      'wpssb-setting',
      'wpssb-setting-section-id'
    );

    add_settings_field(
      'share-facebook',
      'Facebook ' . __('Share Button', $this->textdomain),
      array($this, 'share_facebook_callback'),
      'wpssb-setting',
      'wpssb-setting-section-id'
    );

    add_settings_field(
      'follow-facebook',
      'Facebook ' . __('Follow Button', $this->textdomain),
      array($this, 'follow_facebook_callback'),
      'wpssb-setting',
      'wpssb-setting-section-id'
    );

    add_settings_field(
      'share-google-plus',
      'Google Plus ' . __('Share Button', $this->textdomain),
      array($this, 'share_google_plus_callback'),
      'wpssb-setting',
      'wpssb-setting-section-id'
    );

    add_settings_field(
      'share-hatebu',
      __('Hatena Bookmark Button', $this->textdomain),
      array($this, 'share_hatebu_callback'),
      'wpssb-setting',
      'wpssb-setting-section-id'
    );

    add_settings_field(
      'share-pocket',
      __('Pocket Button', $this->textdomain),
      array($this, 'share_pocket_callback'),
      'wpssb-setting',
      'wpssb-setting-section-id'
    );

    add_settings_field(
      'follow-feedly',
      'Feedly ' . __('Follow Button', $this->textdomain),
      array($this, 'follow_feedly_callback'),
      'wpssb-setting',
      'wpssb-setting-section-id'
    );

    add_settings_field(
      'auto',
      __('Automatically display', $this->textdomain),
      array($this, 'auto_callback'),
      'wpssb-setting',
      'wpssb-setting-section-id'
    );

    add_settings_field(
      'hide_count',
      __('Share Count', $this->textdomain),
      array($this, 'hide_count_callback'),
      'wpssb-setting',
      'wpssb-setting-section-id'
    );

    add_settings_field(
      'nocss',
      __('Do not use plugin\'s CSS', $this->textdomain),
      array($this, 'nocss_callback'),
      'wpssb-setting',
      'wpssb-setting-section-id'
    );
  }

  public function share_twitter_callback() {
    $selected = selected($this->options['share-twitter'], 1, false);

    $html = '<select name="wpssb-setting[share-twitter]">';
    $html .= sprintf('<option value="">%s</option>', __('Show', $this->textdomain));
    $html .= sprintf('<option value="1"%s>%s</option>', $selected, __('Hide', $this->textdomain));
    $html .= '</select>';

    echo $html;
  }

  public function follow_twitter_callback() {
    $selected = selected($this->options['follow-twitter'], 1, false);

    $html = '<select name="wpssb-setting[follow-twitter]">';
    $html .= sprintf('<option value="">%s</option>', __('Show', $this->textdomain));
    $html .= sprintf('<option value="1"%s>%s</option>', $selected, __('Hide', $this->textdomain));
    $html .= '</select>';
    $html .= sprintf('<small>%s</small>', __('Twitter Account is required.', $this->textdomain));

    echo $html;
  }

  public function share_facebook_callback() {
    $selected = selected($this->options['share-facebook'], 1, false);

    $html = '<select name="wpssb-setting[share-facebook]">';
    $html .= sprintf('<option value="">%s</option>', __('Show', $this->textdomain));
    $html .= sprintf('<option value="1"%s>%s</option>', $selected, __('Hide', $this->textdomain));
    $html .= '</select>';

    echo $html;
  }

  public function follow_facebook_callback() {
    $selected = selected($this->options['follow-facebook'], 1, false);

    $html = '<select name="wpssb-setting[follow-facebook]">';
    $html .= sprintf('<option value="">%s</option>', __('Show', $this->textdomain));
    $html .= sprintf('<option value="1"%s>%s</option>', $selected, __('Hide', $this->textdomain));
    $html .= '</select>';

    $html .= sprintf('<small>%s</small>', __('Facebook URL is required.', $this->textdomain));
    echo $html;
  }

  public function share_google_plus_callback() {
    $selected = selected($this->options['share-google-plus'], 1, false);

    $html = '<select name="wpssb-setting[share-google-plus]">';
    $html .= sprintf('<option value="">%s</option>', __('Show', $this->textdomain));
    $html .= sprintf('<option value="1"%s>%s</option>', $selected, __('Hide', $this->textdomain));
    $html .= '</select>';

    echo $html;
  }

  public function share_hatebu_callback() {
    $selected = selected($this->options['share-hatebu'], 1, false);

    $html = '<select name="wpssb-setting[share-hatebu]">';
    $html .= sprintf('<option value="">%s</option>', __('Show', $this->textdomain));
    $html .= sprintf('<option value="1"%s>%s</option>', $selected, __('Hide', $this->textdomain));
    $html .= '</select>';

    echo $html;
  }

  public function share_pocket_callback() {
    $selected = selected($this->options['share-pocket'], 1, false);

    $html = '<select name="wpssb-setting[share-pocket]">';
    $html .= sprintf('<option value="">%s</option>', __('Show', $this->textdomain));
    $html .= sprintf('<option value="1"%s>%s</option>', $selected, __('Hide', $this->textdomain));
    $html .= '</select>';

    echo $html;
  }

  public function twitter_via_callback() {
    $value = isset($this->options['twitter-via']) ? esc_html($this->options['twitter-via']) : '';
    ?><input type="text" class="" name="wpssb-setting[twitter-via]" value="<?php echo $value; ?>">
    <small><?php echo __('Enter Twitter account ID without"@"', $this->textdomain); ?></small><br>
    <?php
  }

  public function facebook_segment_callback() {
    $value = isset($this->options['facebook-segment']) ? esc_html($this->options['facebook-segment']) : '';
    ?><input type="text" class="" name="wpssb-setting[facebook-segment]" value="<?php echo $value; ?>">
    <small><?php echo __('Enter Facebook URL, after "https://www.facebook.com/".', $this->textdomain); ?></small><br>
    <?php
  }

  public function follow_feedly_callback() {

    $feeds = array('rss2_url', 'rss_url', 'rdf_url', 'atom_url');
    $html = '<select name="wpssb-setting[follow-feedly]">';

    foreach($feeds as $feed) {
      $html .= sprintf('<option value="%s"%s>%s</option>', $feed, selected($this->options['follow-feedly'], $feed, false), get_bloginfo($feed));
    }

    $html .= sprintf('<option value="%s"%s>%s</option>', '1', selected($this->options['follow-feedly'], '1', false), __('Hide', $this->textdomain));
    $html .= '</select>';

    echo $html;
  }

  public function hide_count_callback() {
    $selected = selected($this->options['hide-count'], 1, false);

    $html = '<select name="wpssb-setting[hide-count]">';
    $html .= sprintf('<option value="">%s</option>', __('Show', $this->textdomain));
    $html .= sprintf('<option value="1"%s>%s</option>', $selected, __('Hide', $this->textdomain));
    $html .= '</select>';

    $html .= sprintf('<small>%s</small>', __('Installing plugin "SNS Count Cache" is required..', $this->textdomain));

    echo $html;
  }


  public function auto_callback() {
    $html = '<select name="wpssb-setting[auto]">';
    $html .= sprintf('<option value="">%s</option>', __('After article', $this->textdomain));
    $html .= sprintf('<option value="1"%s>%s</option>', selected($this->options['auto'], '1', false), __('Before article', $this->textdomain));
    $html .= sprintf('<option value="2"%s>%s</option>', selected($this->options['auto'], '2', false), __('No (Use function and template)', $this->textdomain));

    $html .= '</select>';

    echo $html;
  }

  public function nocss_callback() {
    $checked = isset($this->options['nocss']) ? checked($this->options['nocss'], 1, false) : '';
    ?><input type="checkbox" id="nocss" name="wpssb-setting[nocss]" value="1"<?php echo $checked; ?>><?php
  }

  // スタイルシートの追加
  public function add_styles() {
    if(!isset($this->options['nocss']) || !$this->options['nocss']) {
      if(!$this->options['nocss']) {
        wp_enqueue_style('wpssb', plugins_url('assets/css/wp-sns-share-buttons.css', __FILE__), array(), $this->version, 'all');
      }
    } else {
      wp_enqueue_style('wpssb', plugins_url('assets/css/wp-sns-share-buttons.css', __FILE__), array(), $this->version, 'all');
    }
  }

  public function the_content($content) {

    $buttons = new WP_Sns_Share_Buttons_Buttons;
    $buttons = $buttons->show_all();

    if($this->options['auto'] === '1') {
      return $buttons . $content;

    } else if($this->options['auto'] === '2') {
      return $content;

    } else {
      return $buttons . $content;
    }

    return $content;
  }

  public function show() {

  }
}

$wpssb = new WP_Sns_Share_Buttons();

function wp_sns_share_buttons() {
  $name = 'wpssb-template.php';
  $custom = get_stylesheet_directory() . '/' . $name ;
  $default = 'template/' . $name;

  if(file_exists($custom)) {
    include $custom;
  } else {
    include $default;
  }
}
