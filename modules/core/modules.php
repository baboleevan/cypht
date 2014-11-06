<?php

if (!defined('DEBUG_MODE')) { die(); }

define('MAX_PER_SOURCE', 100);
define('DEFAULT_PER_SOURCE', 20);
define('DEFAULT_SINCE', 'today');

require APP_PATH.'modules/core/functions.php';

/* INPUT */

class Hm_Handler_close_session_early extends Hm_Handler_Module {
    public function process() {
        $this->session->close_early();
    }
}

class Hm_Handler_http_headers extends Hm_Handler_Module {
    public function process() {
        $headers = array();
        if ($this->get('language')) {
            $headers[] = 'Content-Language: '.substr($this->get('language'), 0, 2);
        }
        if ($this->request->tls) {
            $headers[] = 'Strict-Transport-Security: max-age=31536000';
        }
        $headers[] = 'X-XSS-Protection: 1; mode=block';
        $headers[] = 'X-Content-Type-Options: nosniff';
        $headers[] = 'Expires: '.gmdate('D, d M Y H:i:s \G\M\T', strtotime('-1 year'));
        $headers[] = "Content-Security-Policy: default-src 'none'; script-src 'self' 'unsafe-inline'; connect-src 'self'; img-src 'self' data:; style-src 'self' 'unsafe-inline';";
        if ($this->request->type == 'AJAX') {
            $headers[] = 'Content-Type: application/json';
        }
        $this->out('http_headers', $headers);
    }
}

class Hm_Handler_process_list_style_setting extends Hm_Handler_Module {
    public function process() {
        list($success, $form) = $this->process_form(array('save_settings', 'list_style'));
        $new_settings = $this->get('new_user_settings', array());
        $settings = $this->get('user_settings', array());

        if ($success) {
            if (in_array($form['list_style'], array('email_style', 'news_style'))) {
                $new_settings['list_style'] = $form['list_style'];
            }
            else {
                $settings['list_style'] = $this->user_config->get('list_style', false);
            }
        }
        else {
            $settings['list_style'] = $this->user_config->get('list_style', false);
        }
        $this->out('new_user_settings', $new_settings, false);
        $this->out('user_settings', $settings, false);
    }
}

class Hm_Handler_process_unread_source_max_setting extends Hm_Handler_Module {
    public function process() {
        list($success, $form) = $this->process_form(array('save_settings', 'unread_per_source'));
        $new_settings = $this->get('new_user_settings', array());
        $settings = $this->get('user_settings', array());

        if ($success) {
            if ($form['unread_per_source'] > MAX_PER_SOURCE || $form['unread_per_source'] < 0) {
                $sources = DEFAULT_PER_SOURCE;
            }
            else {
                $sources = $form['unread_per_source'];
            }
            $new_settings['unread_per_source_setting'] = $sources;
        }
        else {
            $settings['unread_per_source'] = $this->user_config->get('unread_per_source_setting', DEFAULT_PER_SOURCE);
        }
        $this->out('new_user_settings', $new_settings, false);
        $this->out('user_settings', $settings, false);
    }
}

class Hm_Handler_process_all_source_max_setting extends Hm_Handler_Module {
    public function process() {
        list($success, $form) = $this->process_form(array('save_settings', 'all_per_source'));
        $new_settings = $this->get('new_user_settings', array());
        $settings = $this->get('user_settings', array());

        if ($success) {
            if ($form['all_per_source'] > MAX_PER_SOURCE || $form['all_per_source'] < 0) {
                $sources = DEFAULT_PER_SOURCE;
            }
            else {
                $sources = $form['all_per_source'];
            }
            $new_settings['all_per_source_setting'] = $sources;
        }
        else {
            $settings['all_per_source'] = $this->user_config->get('all_per_source_setting', DEFAULT_PER_SOURCE);
        }
        $this->out('new_user_settings', $new_settings, false);
        $this->out('user_settings', $settings, false);
    }
}

class Hm_Handler_process_flagged_source_max_setting extends Hm_Handler_Module {
    public function process() {
        list($success, $form) = $this->process_form(array('save_settings', 'flagged_per_source'));
        $new_settings = $this->get('new_user_settings', array());
        $settings = $this->get('user_settings', array());

        if ($success) {
            if ($form['flagged_per_source'] > MAX_PER_SOURCE || $form['flagged_per_source'] < 0) {
                $sources = DEFAULT_PER_SOURCE;
            }
            else {
                $sources = $form['flagged_per_source'];
            }
            $new_settings['flagged_per_source_setting'] = $sources;
        }
        else {
            $settings['flagged_per_source'] = $this->user_config->get('flagged_per_source_setting', DEFAULT_PER_SOURCE);
        }
        $new_settings = $this->get('new_user_settings', array());
        $settings = $this->get('user_settings', array());
    }
}

class Hm_Handler_process_flagged_since_setting extends Hm_Handler_Module {
    public function process() {
        list($success, $form) = $this->process_form(array('save_settings', 'flagged_since'));
        $new_settings = $this->get('new_user_settings', array());
        $settings = $this->get('user_settings', array());

        if ($success) {
            $new_settings['flagged_since_setting'] = process_since_argument($form['flagged_since'], true);
        }
        else {
            $settings['flagged_since'] = $this->user_config->get('flagged_since_setting', false);
        }
        $this->out('new_user_settings', $new_settings, false);
        $this->out('user_settings', $settings, false);
    }
}

class Hm_Handler_process_all_since_setting extends Hm_Handler_Module {
    public function process() {
        list($success, $form) = $this->process_form(array('save_settings', 'all_since'));
        $new_settings = $this->get('new_user_settings', array());
        $settings = $this->get('user_settings', array());

        if ($success) {
            $new_settings['all_since_setting'] = process_since_argument($form['all_since'], true);
        }
        else {
            $settings['all_since'] = $this->user_config->get('all_since_setting', false);
        }
        $this->out('new_user_settings', $new_settings, false);
        $this->out('user_settings', $settings, false);
    }
}

class Hm_Handler_process_unread_since_setting extends Hm_Handler_Module {
    public function process() {
        list($success, $form) = $this->process_form(array('save_settings', 'unread_since'));
        $new_settings = $this->get('new_user_settings', array());
        $settings = $this->get('user_settings', array());

        if ($success) {
            $new_settings['unread_since_setting'] = process_since_argument($form['unread_since'], true);
        }
        else {
            $settings['unread_since'] = $this->user_config->get('unread_since_setting', false);
        }
        $this->out('new_user_settings', $new_settings, false);
        $this->out('user_settings', $settings, false);
    }
}

class Hm_Handler_process_language_setting extends Hm_Handler_Module {
    public function process() {
        list($success, $form) = $this->process_form(array('save_settings', 'language_setting'));
        $new_settings = $this->get('new_user_settings', array());
        $settings = $this->get('user_settings', array());

        if ($success) {
            $new_settings['language_setting'] = $form['language_setting'];
        }
        else {
            $settings['language'] = $this->user_config->get('language_setting', false);
        }
        $this->out('new_user_settings', $new_settings, false);
        $this->out('user_settings', $settings, false);
    }
}

class Hm_Handler_process_timezone_setting extends Hm_Handler_Module {
    public function process() {
        list($success, $form) = $this->process_form(array('save_settings', 'timezone_setting'));
        $new_settings = $this->get('new_user_settings', array());
        $settings = $this->get('user_settings', array());

        if ($success) {
            $new_settings['timezone_setting'] = $form['timezone_setting'];
        }
        else {
            $settings['timezone'] = $this->user_config->get('timezone_setting', false);
        }
        $this->out('new_user_settings', $new_settings, false);
        $this->out('user_settings', $settings, false);
    }
}

class Hm_Handler_save_user_settings extends Hm_Handler_Module {
    public function process() {
        list($success, $form) = $this->process_form(array('save_settings', 'password'));
        if ($success) {
            if ($new_settings = $this->get('new_user_settings', false)) {
                foreach ($new_settings as $name => $value) {
                    $this->user_config->set($name, $value);
                }
                $user = $this->session->get('username', false);
                $path = $this->config->get('user_settings_dir', false);

                if ($this->get('new_password', false)) {
                    $pass = $this->get('new_password');
                }
                elseif ($this->session->auth($user, $form['password'])) {
                    $pass = $form['password'];
                }
                else {
                    Hm_Msgs::add('ERRIncorrect password, could not save settings to the server');
                    /* TODO: save current settings in session */
                    $pass = false;
                }
                if ($user && $path && $pass) {
                    $this->user_config->save($user, $pass);
                    Hm_Msgs::add('Settings saved');
                    $this->out('reload_folders', true, false);
                }
                Hm_Page_Cache::flush($this->session);
            }
        }
        elseif (array_key_exists('save_settings', $this->request->post)) {
            /* TODO: save current settings in session */
            Hm_Msgs::add('ERRYour password is required to save your settings to the server');
        }
    }
}

class Hm_Handler_title extends Hm_Handler_Module {
    public function process() {
        $this->out('title', ucfirst($this->page));
    }
}

class Hm_Handler_language extends Hm_Handler_Module {
    public function process() {
        $this->out('language', $this->user_config->get('language_setting', 'en_US'));
    }
}

class Hm_Handler_date extends Hm_Handler_Module {
    public function process() {
        $this->out('date', date('G:i:s'));
    }
}

class Hm_Handler_login extends Hm_Handler_Module {
    public function process() {
        if (!$this->get('create_username', false)) {
            list($success, $form) = $this->process_form(array('username', 'password'));
            if ($success) {
                $this->session->check($this->request, $form['username'], $form['password']);
                $this->session->set('username', $form['username']);
            }
            else {
                $this->session->check($this->request);
            }
            if ($this->session->is_active()) {
                Hm_Page_Cache::load($this->session);
                $this->out('changed_settings', $this->session->get('changed_settings', array()), false);
            }
        }
        $this->process_nonce();
    }
}

/* TODO: populate this from modules (imap is the only one so far) */
class Hm_Handler_default_page_data extends Hm_Handler_Module {
    public function process() {
        $this->out('data_sources', array());
    }
}

class Hm_Handler_load_user_data extends Hm_Handler_Module {
    public function process() {
        list($success, $form) = $this->process_form(array('username', 'password'));
        if ($this->session->is_active()) {
            if ($success) {
                $this->user_config->load($form['username'], $form['password']);
            }
            else {
                $user_data = $this->session->get('user_data', array());
                if (!empty($user_data)) {
                    $this->user_config->reload($user_data);
                }
                $pages = $this->user_config->get('saved_pages', array());
                if (!empty($pages)) {
                    $this->session->set('saved_pages', $pages);
                }
            }
        }
        $this->out('is_mobile', $this->request->mobile);
    }
}

class Hm_Handler_save_user_data extends Hm_Handler_Module {
    public function process() {
        $user_data = $this->user_config->dump();
        if (!empty($user_data)) {
            $this->session->set('user_data', $user_data);
        }
    }
}

class Hm_Handler_logout extends Hm_Handler_Module {
    public function process() {
        if (array_key_exists('logout', $this->request->post) && !$this->session->loaded) {
            $this->session->destroy($this->request);
            Hm_Msgs::add('Session destroyed on logout');
        }
        elseif (array_key_exists('save_and_logout', $this->request->post)) {
            list($success, $form) = $this->process_form(array('password'));
            if ($success) {
                $user = $this->session->get('username', false);
                $path = $this->config->get('user_settings_dir', false);
                $pages = $this->session->get('saved_pages', array());
                if (!empty($pages)) {
                    $this->user_config->set('saved_pages', $pages);
                }
                if ($this->session->auth($user, $form['password'])) {
                    $pass = $form['password'];
                }
                else {
                    Hm_Msgs::add('ERRIncorrect password, could not save settings to the server');
                    $pass = false;
                }
                if ($user && $path && $pass) {
                    $this->user_config->save($user, $pass);
                    $this->session->destroy($this->request);
                    Hm_Msgs::add('Saved user data on logout');
                    Hm_Msgs::add('Session destroyed on logout');
                }
            }
            else {
                Hm_Msgs::add('ERRYour password is required to save your settings to the server');
            }
        }
    }
}
/* TODO: clean this up somehow */
class Hm_Handler_message_list_type extends Hm_Handler_Module {
    public function process() {
        $uid = false;
        $list_path = false;
        $list_meta = true;
        $list_parent = false;
        $list_page = false;
        $mailbox_list_title = array();
        $message_list_since = false;
        $per_source_limit = false;
        $no_message_list_headers = false;

        if (array_key_exists('list_path', $this->request->get)) {
            $path = $this->request->get['list_path'];
            if ($path == 'unread') {
                $list_path = 'unread';
                $mailbox_list_title = array('Unread');
                $message_list_since = $this->user_config->get('unread_since_setting', DEFAULT_SINCE);
                $per_source_limit = $this->user_config->get('unread_per_source_setting', DEFAULT_SINCE);
            }
            elseif ($path == 'email') {
                $list_path = 'email';
                $mailbox_list_title = array('All Email');
            }
            elseif ($path == 'flagged') {
                $list_path = 'flagged';
                $message_list_since = $this->user_config->get('flagged_since_setting', DEFAULT_SINCE);
                $per_source_limit = $this->user_config->get('flagged_per_source_setting', DEFAULT_SINCE);
                $mailbox_list_title = array('Flagged');
            }
            elseif ($path == 'combined_inbox') {
                $list_path = 'combined_inbox';
                $message_list_since = $this->user_config->get('all_since_setting', DEFAULT_SINCE);
                $per_source_limit = $this->user_config->get('all_per_source_setting', DEFAULT_SINCE);
                $mailbox_list_title = array('Everything');
            }
        }
        if (array_key_exists('list_parent', $this->request->get)) {
            $list_parent = $this->request->get['list_parent'];
        }
        else {
            $list_parent = false;
        }
        if (array_key_exists('list_page', $this->request->get)) {
            $list_page = (int) $this->request->get['list_page'];
            if ($list_page < 1) {
                $list_page = 1;
            }
        }
        else {
            $list_page = 1;
        }
        if (array_key_exists('uid', $this->request->get) && preg_match("/\d+/", $this->request->get['uid'])) {
            $uid = $this->request->get['uid'];
        }
        $list_style = $this->user_config->get('list_style', false);
        if ($this->get('is_mobile', false)) {
            $list_style = 'news_style';
        }
        if ($list_style == 'news_style') {
            $no_message_list_headers = true;
            $this->out('news_list_style', true);
        }
        $this->out('uid', $uid);
        $this->out('list_path', $list_path, false);
        $this->out('list_meta', $list_meta, false);
        $this->out('list_parent', $list_parent);
        $this->out('list_page', $list_page);
        $this->out('mailbox_list_title', $mailbox_list_title, false);
        $this->out('message_list_since', $message_list_since, false);
        $this->out('per_source_limit', $per_source_limit, false);
        $this->out('no_message_list_headers', $no_message_list_headers);
    }
}

class Hm_Handler_reload_folder_cookie extends Hm_Handler_Module {
    public function process() {
        if ($this->get('reload_folders', false)) {
            secure_cookie($this->request, 'hm_reload_folders', '1');
        }
    }
}


/* OUTPUT */

class Hm_Output_login extends Hm_Output_Module {
    protected function output($input, $format) {
        if (!$this->get('router_login_state')) {
            return '<form class="login_form" method="POST">'.
                '<h1 class="title">'.$this->html_safe($this->get('router_app_name', '')).'</h1>'.
                ' <input type="hidden" name="hm_nonce" value="'.Hm_Nonce::site_key().'" />'.
                ' <label for="username">Username</label><input autofocus required type="text" placeholder="'.$this->trans('Username').'" id="username" name="username" value="">'.
                ' <label for="password">Password</label><input required type="password" id="password" placeholder="'.$this->trans('Password').'" name="password">'.
                ' <input type="submit" value="Login" /></form>';
        }
        else {
            return '<form class="logout_form" method="POST">'.
                '<input type="hidden" id="unsaved_changes" value="'.
                ($this->get('changed_settings', false) ? '1' : '0').'" />'.
                '<input type="hidden" name="hm_nonce" value="'.$this->html_safe(Hm_Nonce::generate()).'" />'.
                '<div class="confirm_logout"><div class="confirm_text">'.
                $this->trans('Unsaved changes will be lost! Re-neter your password to save and exit.').'</div>'.
                '<label for="logout_password">Password</label><input id="logout_password" name="password" class="save_settings_password" type="password" placeholder="Password" />'.
                '<input class="save_settings" type="submit" name="save_and_logout" value="Save and Logout" />'.
                '<input class="save_settings" id="logout_without_saving" type="submit" name="logout" value="Just Logout" />'.
                '<input class="cancel_logout save_settings" type="button" value="Cancel" />'.
                '</div></form>';
        }
    }
}

class Hm_Output_server_content_start extends Hm_Output_Module {
    protected function output($input, $format) {
        return '<div class="content_title">'.$this->trans('Servers').'</div><div class="server_content">';
    }
}

class Hm_Output_server_content_end extends Hm_Output_Module {
    protected function output($input, $format) {
        return '</div>';
    }
}

class Hm_Output_date extends Hm_Output_Module {
    protected function output($input, $format) {
        return '<div class="date">'.$this->html_safe($this->get('date')).'</div>';
    }
}

class Hm_Output_msgs extends Hm_Output_Module {
    protected function output($input, $format) {
        $res = '';
        $msgs = Hm_Msgs::get();
        $logged_out_class = '';
        if (!$this->get('router_login_state') && !empty($msgs)) {
            $logged_out_class = ' logged_out';
        }
        $res .= '<div class="sys_messages'.$logged_out_class.'">';
        if (!empty($msgs)) {
            $res .= implode(',', array_map(function($v) {
                if (preg_match("/ERR/", $v)) {
                    return sprintf('<span class="err">%s</span>', substr($this->html_safe($v), 3));
                }
                else {
                    return $this->html_safe($v);
                }
            }, $msgs));
        }
        $res .= '</div>';
        return $res;
    }
}

class Hm_Output_header_start extends Hm_Output_Module {
    protected function output($input, $format) {
        $lang = '';
        if ($this->lang) {
            $lang = 'lang='.strtolower(str_replace('_', '-', $this->lang));
        }
        return '<!DOCTYPE html><html '.$lang.'><head><meta charset="utf-8" />';
    }
}

class Hm_Output_header_end extends Hm_Output_Module {
    protected function output($input, $format) {
        return '</head>';
    }
}

class Hm_Output_content_start extends Hm_Output_Module {
    protected function output($input, $format) {
        $res = '<body><noscript class="noscript">You Need to have Javascript enabled to use '.$this->html_safe($this->get('router_app_name')).' Sorry about that!</noscript>';
        if (!$this->get('router_login_state')) {
            $res .= '<script type="text/javascript">sessionStorage.clear();</script>';
        }
        else {
            $res .= '<input type="hidden" id="hm_nonce" value="'.$this->html_safe(Hm_Nonce::generate()).'" />';
        }
        return $res;
    }
}

class Hm_Output_header_content extends Hm_Output_Module {
    protected function output($input, $format) {
        $title = '';
        if (!$this->get('router_login_state')) {
            $title = $this->get('router_app_name');
        }
        elseif ($this->exists('mailbox_list_title')) {
            $title .= ' '.implode('-', array_slice($this->get('mailbox_list_title', ''), 1));
        }
        if (!trim($title) && $this->exists('router_page_name')) {
            $title = '';
            if ($this->get('list_path') == 'message_list') {
                $title .= ' '.ucwords(str_replace('_', ' ', $this->get('list_path')));
            }
            elseif ($this->get('router_page_name') == 'notfound') {
                $title .= ' Nope';
            }
            else {
                $title .= ' '.ucfirst($this->get('router_page_name'));
            }
        }
        return '<title>'.$this->html_safe($title).'</title>'.
            '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">'.
            '<link rel="icon" class="tab_icon" type="image/png" href="'.Hm_Image_Sources::$env_closed.'">'.
            '<base href="'.$this->html_safe($this->get('router_url_path')).'" />';
    }
}

class Hm_Output_header_css extends Hm_Output_Module {
    protected function output($input, $format) {
        $res = '';
        if (DEBUG_MODE) {
            foreach (glob('modules/*', GLOB_ONLYDIR | GLOB_MARK) as $name) {
                if (is_readable(sprintf("%ssite.css", $name))) {
                    $res .= '<link href="'.sprintf("%ssite.css", $name).'" media="all" rel="stylesheet" type="text/css" />';
                }
            }
        }
        else {
            $res .= '<link href="site.css?v='.CACHE_ID.'" media="all" rel="stylesheet" type="text/css" />';
        }
        return $res;
    }
}

class Hm_Output_page_js extends Hm_Output_Module {
    protected function output($input, $format) {
        if (DEBUG_MODE) {
            $res = '';
            $zepto = '<script type="text/javascript" src="third_party/zepto.min.js"></script>';
            $core = false;
            foreach (glob('modules/*', GLOB_ONLYDIR | GLOB_MARK) as $name) {
                if ($name == 'modules/core/') {
                    $core = $name;
                    continue;
                }
                if (is_readable(sprintf("%ssite.js", $name))) {
                    $res .= '<script type="text/javascript" src="'.sprintf("%ssite.js", $name).'"></script>';
                }
            }
            if ($core) {
                $res = '<script type="text/javascript" src="'.sprintf("%ssite.js", $core).'"></script>'.$res;
            }
            return $zepto.$res;
        }
        else {
            return '<script type="text/javascript" src="site.js?v='.CACHE_ID.'"></script>';
        }
    }
}

class Hm_Output_content_end extends Hm_Output_Module {
    protected function output($input, $format) {
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            return '<div class="debug"></div></body></html>';
        }
        else {
            return '</body></html>';
        }
    }
}

class Hm_Output_js_data extends Hm_Output_Module {
    protected function output($input, $format) {
        return '<script type="text/javascript">'.
            'var hm_page_name = function() { return "'.$this->html_safe($this->get('router_page_name')).'"; };'.
            'var hm_list_path = function() { return "'.$this->html_safe($this->get('list_path', '')).'"; };'.
            'var hm_list_parent = function() { return "'.$this->html_safe($this->get('list_parent', '')).'"; };'.
            'var hm_msg_uid = function() { return "'.$this->html_safe($this->get('uid', '')).'"; };'.
            '</script>';
    }
}

class Hm_Output_loading_icon extends Hm_Output_Module {
    protected function output($input, $format) {
        return '<div class="loading_icon"></div>';
    }
}

class Hm_Output_start_settings_form extends Hm_Output_Module {
    protected function output($input, $format) {
        return '<div class="user_settings"><div class="content_title">Site Settings</div>'.
            '<form method="POST"><input type="hidden" name="hm_nonce" value="'.$this->html_safe(Hm_Nonce::generate()).'" />'.
            '<table class="settings_table"><colgroup>'.
            '<col class="label_col"><col class="setting_col"></colgroup>';
    }
}

class Hm_Output_list_style_setting extends Hm_Output_Module {
    protected function output($input, $format) {
        $options = array('email_style' => 'Email', 'news_style' => 'News');
        $settings = $this->get('user_settings', array());

        if (array_key_exists('list_style', $settings)) {
            $list_style = $settings['list_style'];
        }
        else {
            $list_style = false;
        }
        $res = '<tr class="general_setting"><td><label for="list_style">Message list style</label></td>'.
            '<td><select id="list_style" name="list_style">';
        foreach ($options as $val => $label) {
            $res .= '<option ';
            if ($list_style == $val) {
                $res .= 'selected="selected" ';
            }
            $res .= 'value="'.$val.'">'.$label.'</option>';
        }
        $res .= '</select></td></tr>';
        return $res;
    }
}

class Hm_Output_start_flagged_settings extends Hm_Output_Module {
    protected function output($input, $format) {
        return '<tr><td data-target=".flagged_setting" colspan="2" class="settings_subtitle">'.
            '<img alt="" src="'.Hm_Image_Sources::$star.'" width="16" height="16" />'.
            $this->trans('Flagged').'</td></tr>';
    }
}

class Hm_Output_start_everything_settings extends Hm_Output_Module {
    protected function output($input, $format) {
        return '<tr><td data-target=".all_setting" colspan="2" class="settings_subtitle">'.
            '<img alt="" src="'.Hm_Image_Sources::$box.'" width="16" height="16" />'.
            $this->trans('Everything').'</td></tr>';
    }
}

class Hm_Output_start_unread_settings extends Hm_Output_Module {
    protected function output($input, $format) {
        return '<tr><td data-target=".unread_setting" colspan="2" class="settings_subtitle">'.
            '<img alt="" src="'.Hm_Image_Sources::$env_closed.'" width="16" height="16" />'.
            $this->trans('Unread').'</td></tr>';
    }
}

class Hm_Output_start_general_settings extends Hm_Output_Module {
    protected function output($input, $format) {
        return '<tr><td data-target=".general_setting" colspan="2" class="settings_subtitle">'.
            '<img alt="" src="'.Hm_Image_Sources::$cog.'" width="16" height="16" />'.
            $this->trans('General').'</td></tr>';
    }
}

class Hm_Output_unread_source_max_setting extends Hm_Output_Module {
    protected function output($input, $format) {
        $sources = DEFAULT_PER_SOURCE;
        $settings = $this->get('user_settings', array());
        if (array_key_exists('unread_per_source', $settings)) {
            $sources = $settings['unread_per_source'];
        }
        return '<tr class="unread_setting"><td><label for="unread_per_source">Max messages per source</label></td>'.
            '<td><input type="text" size="2" id="unread_per_source" name="unread_per_source" value="'.$this->html_safe($sources).'" /></td></tr>';
    }
}

class Hm_Output_unread_since_setting extends Hm_Output_Module {
    protected function output($input, $format) {
        $since = false;
        $settings = $this->get('user_settings', array());
        if (array_key_exists('unread_since', $settings)) {
            $since = $settings['unread_since'];
        }
        return '<tr class="unread_setting"><td><label for="unread_since">Show messages received since</label></td>'.
            '<td>'.message_since_dropdown($since, 'unread_since', $this).'</td></tr>';
    }
}

class Hm_Output_flagged_source_max_setting extends Hm_Output_Module {
    protected function output($input, $format) {
        $sources = DEFAULT_PER_SOURCE;
        $settings = $this->get('user_settings', array());
        if (array_key_exists('flagged_per_source', $settings)) {
            $sources = $settings['flagged_per_source'];
        }
        return '<tr class="flagged_setting"><td><label for="flagged_per_source">Max messages per source</label></td>'.
            '<td><input type="text" size="2" id="flagged_per_source" name="flagged_per_source" value="'.$this->html_safe($sources).'" /></td></tr>';
    }
}

class Hm_Output_flagged_since_setting extends Hm_Output_Module {
    protected function output($input, $format) {
        $since = false;
        $settings = $this->get('user_settings', array());
        if (array_key_exists('flagged_since', $settings)) {
            $since = $settings['flagged_since'];
        }
        return '<tr class="flagged_setting"><td><label for="flagged_since">Show messages received since</label></td>'.
            '<td>'.message_since_dropdown($since, 'flagged_since', $this).'</td></tr>';
    }
}

class Hm_Output_all_source_max_setting extends Hm_Output_Module {
    protected function output($input, $format) {
        $sources = DEFAULT_PER_SOURCE;
        $settings = $this->get('user_settings', array());
        if (array_key_exists('all_per_source', $settings)) {
            $sources = $settings['all_per_source'];
        }
        return '<tr class="all_setting"><td><label for="all_per_source">Max messages per source</label></td>'.
            '<td><input type="text" size="2" id="all_per_source" name="all_per_source" value="'.$this->html_safe($sources).'" /></td></tr>';
    }
}

class Hm_Output_all_since_setting extends Hm_Output_Module {
    protected function output($input, $format) {
        $since = false;
        $settings = $this->get('user_settings', array());
        if (array_key_exists('all_since', $settings)) {
            $since = $settings['all_since'];
        }
        return '<tr class="all_setting"><td><label for="all_since">Show messages received since</label></td>'.
            '<td>'.message_since_dropdown($since, 'all_since', $this).'</td></tr>';
    }
}

class Hm_Output_language_setting extends Hm_Output_Module {
    protected function output($input, $format) {
        $langs = array(
            'en_US' => 'English',
            'es_ES' => 'Spanish'
        );
        $settings = $this->get('user_settings', array());
        if (array_key_exists('language', $settings)) {
            $mylang = $settings['language'];
        }
        else {
            $mylang = false;
        }
        $res = '<tr class="general_setting"><td><label for="language_setting">Interface language</label></td>'.
            '<td><select id="language_setting" name="language_setting">';
        foreach ($langs as $id => $lang) {
            $res .= '<option ';
            if ($id == $mylang) {
                $res .= 'selected="selected" ';
            }
            $res .= 'value="'.$id.'">'.$lang.'</option>';
        }
        $res .= '</select></td></tr>';
        return $res;
    }
}

class Hm_Output_timezone_setting extends Hm_Output_Module {
    protected function output($input, $format) {
        $zones = timezone_identifiers_list();
        $settings = $this->get('user_settings', array());
        if (array_key_exists('timezone', $settings)) {
            $myzone = $settings['timezone'];
        }
        else {
            $myzone = false;
        }
        $res = '<tr class="general_setting"><td><label for="timezone_setting">Timezone</label></td>'.
            '<td><select id="timezone_setting" name="timezone_setting">';
        foreach ($zones as $zone) {
            $res .= '<option ';
            if ($zone == $myzone) {
                $res .= 'selected="selected" ';
            }
            $res .= 'value="'.$zone.'">'.$zone.'</option>';
        }
        $res .= '</select></td></tr>';
        return $res;
    }
}

class Hm_Output_end_settings_form extends Hm_Output_Module {
    protected function output($input, $format) {
        return '<tr><td class="submit_cell" colspan="2">'.
            '<label class="screen_reader" for="password">Password</label><input required id="password" name="password" class="save_settings_password" type="password" placeholder="Password" />'.
            '<input class="save_settings" type="submit" name="save_settings" value="Save" />'.
            '<div class="password_notice">* You must enter your password to save your settings on the server</div>'.
            '</td></tr></table></form></div>';
    }
}

class Hm_Output_two_col_layout_start extends Hm_Output_Module {
    protected function output($input, $format) {
        return '<div class="framework">';
    }
}

class Hm_Output_two_col_layout_end extends Hm_Output_Module {
    protected function output($input, $format) {
        return '</div><br class="end_float" />';
    }
}

class Hm_Output_folder_list_start extends Hm_Output_Module {
    protected function output($input, $format) {
        $res = '<a class="folder_toggle" href="#"><img alt="" src="'.Hm_Image_Sources::$big_caret.'" width="20" height="20" /></a>'.
            '<nav class="folder_cell"><div class="folder_list">';
        return $res;
    }
}

class Hm_Output_folder_list_content_start extends Hm_Output_Module {
    protected function output($input, $format) {
        if ($format == 'HTML5') {
            return '';
        }
        $this->out('formatted_folder_list', '', false);
    }
}

class Hm_Output_main_menu_start extends Hm_Output_Module {
    protected function output($input, $format) {
        $res = '<div class="src_name main_menu" data-source=".main">Main'.
        '<img alt="" class="menu_caret" src="'.Hm_Image_Sources::$chevron.'" width="8" height="8" />'.
        '</div><div class="main"><ul class="folders">';
        if ($format == 'HTML5') {
            return $res;
        }
        $this->concat('formatted_folder_list', $res);
    }
}

class Hm_Output_main_menu_content extends Hm_Output_Module {
    protected function output($input, $format) {
        $email = false;
        if (in_array('email_folders', $this->get('folder_sources', array()))) {
            $email = true;
        }
        $res = '<li class="menu_home"><a class="unread_link" href="?page=home">'.
            '<img class="account_icon" src="'.$this->html_safe(Hm_Image_Sources::$home).'" alt="" width="16" height="16" /> '.$this->trans('Home').'</a></li>'.
            '<li class="menu_combined_inbox"><a class="unread_link" href="?page=message_list&amp;list_path=combined_inbox">'.
            '<img class="account_icon" src="'.$this->html_safe(Hm_Image_Sources::$box).'" alt="" width="16" height="16" /> '.$this->trans('Everything').
            '</a><span class="combined_inbox_count"></span></li>';
        if ($email) {
            $res .= '<li class="menu_unread"><a class="unread_link" href="?page=message_list&amp;list_path=unread">'.
                '<img class="account_icon" src="'.$this->html_safe(Hm_Image_Sources::$env_closed).'" alt="" width="16" height="16" /> '.$this->trans('Unread').'</a></li>';
        }
        $res .= '<li class="menu_flagged"><a class="unread_link" href="?page=message_list&amp;list_path=flagged">'.
            '<img class="account_icon" src="'.$this->html_safe(Hm_Image_Sources::$star).'" alt="" width="16" height="16" /> '.$this->trans('Flagged').
            '</a> <span class="flagged_count"></span></li>';

        if ($format == 'HTML5') {
            return $res;
        }
        $this->concat('formatted_folder_list', $res);
    }
}

class Hm_Output_logout_menu_item extends Hm_Output_Module {
    protected function output($input, $format) {
        $res =  '<li><a class="unread_link logout_link" href="#"><img class="account_icon" src="'.
            $this->html_safe(Hm_Image_Sources::$power).'" alt="" width="16" height="16" /> '.$this->trans('Logout').'</a></li>';

        if ($format == 'HTML5') {
            return $res;
        }
        $this->concat('formatted_folder_list', $res);
    }
}

class Hm_Output_main_menu_end extends Hm_Output_Module {
    protected function output($input, $format) {
        $res = '</ul></div>';
        if ($format == 'HTML5') {
            return $res;
        }
        $this->concat('formatted_folder_list', $res);
    }
}

class Hm_Output_email_menu_content extends Hm_Output_Module {
    protected function output($input, $format) {
        $res = '';
        $folder_sources = array_unique($this->get('folder_sources', array()));
        foreach ($folder_sources as $src) {
            $parts = explode('_', $src);
            $name = ucfirst(strtolower($parts[0]));
            $res .= '<div class="src_name" data-source=".'.$this->html_safe($src).'">'.$this->html_safe($name).
                '<img class="menu_caret" src="'.Hm_Image_Sources::$chevron.'" alt="" width="8" height="8" /></div>';

            $res .= '<div style="display: none;" ';
            $res .= 'class="'.$this->html_safe($src).'"><ul class="folders">';
            if ($name == 'Email') {
                $res .= '<li class="menu_email"><a class="unread_link" href="?page=message_list&amp;list_path=email">'.
                '<img class="account_icon" src="'.$this->html_safe(Hm_Image_Sources::$globe).'" alt="" width="16" height="16" /> '.$this->trans('All').'</a> <span class="unread_mail_count"></span></li>';
            }
            $cache = Hm_Page_Cache::get($src);
            Hm_Page_Cache::del($src);
            if ($cache) {
                $res .= $cache;
            }
            $res .= '</ul></div>';
        }
        if ($format == 'HTML5') {
            return $res;
        }
        $this->concat('formatted_folder_list', $res);
    }
}

class Hm_Output_settings_menu_start extends Hm_Output_Module {
    protected function output($input, $format) {
        $res = '<div class="src_name" data-source=".settings">Settings'.
            '<img class="menu_caret" src="'.Hm_Image_Sources::$chevron.'" alt="" width="8" height="8" />'.
            '</div><ul style="display: none;" class="settings folders">';
        if ($format == 'HTML5') {
            return $res;
        }
        $this->concat('formatted_folder_list', $res);
    }
}

class Hm_Output_settings_menu_content extends Hm_Output_Module {
    protected function output($input, $format) {
        $res = '<li class="menu_servers"><a class="unread_link" href="?page=servers">'.
            '<img class="account_icon" src="'.$this->html_safe(Hm_Image_Sources::$monitor).'" alt="" width="16" height="16" /> '.$this->trans('Servers').'</a></li>'.
            '<li class="menu_settings"><a class="unread_link" href="?page=settings">'.
            '<img class="account_icon" src="'.$this->html_safe(Hm_Image_Sources::$cog).'" alt="" width="16" height="16" /> '.$this->trans('Site').'</a></li>';
        if ($format == 'HTML5') {
            return $res;
        }
        $this->concat('formatted_folder_list', $res);
    }
}

class Hm_Output_settings_menu_end extends Hm_Output_Module {
    protected function output($input, $format) {
        $res = '</ul>';
        if ($format == 'HTML5') {
            return $res;
        }
        $this->concat('formatted_folder_list', $res);
    }
}

class Hm_Output_folder_list_content_end extends Hm_Output_Module {
    protected function output($input, $format) {
        $res = '<a href="#" class="update_message_list">[reload]</a>';
        $res .= '<a href="#" class="hide_folders"><img src="'.Hm_Image_Sources::$big_caret_left.'" alt="Collapse" width="16" height="16" /></a>';
        if ($format == 'HTML5') {
            return $res;
        }
        $this->concat('formatted_folder_list', $res);
    }
}

class Hm_Output_folder_list_end extends Hm_Output_Module {
    protected function output($input, $format) {
        return '</div></nav>';
    }
}

class Hm_Output_content_section_start extends Hm_Output_Module {
    protected function output($input, $format) {
        return '<div class="content_cell">';
    }
}

class Hm_Output_content_section_end extends Hm_Output_Module {
    protected function output($input, $format) {
        return '</div>';
    }
}

class Hm_Output_server_status_start extends Hm_Output_Module {
    protected function output($input, $format) {
        $res = '<div class="server_status"><div class="content_title">Home</div>';
        $res .= '<table><thead><tr><th>Type</th><th>Name</th><th>Status</th></tr>'.
                '</thead><tbody>';
        return $res;
    }
}

class Hm_Output_server_status_end extends Hm_Output_Module {
    protected function output($input, $format) {
        return '</tbody></table></div>';
    }
}

class Hm_Output_message_start extends Hm_Output_Module {
    protected function output($input, $format) {
        if ($this->in('list_parent', array('search', 'flagged', 'combined_inbox', 'unread', 'feeds'))) {
            if ($this->get('list_parent') == 'combined_inbox') {
                $list_name = 'Everything';
            }
            else {
                $list_name = ucwords(str_replace('_', ' ', $this->get('list_parent', '')));
            }
            if ($this->get('list_parent') == 'search') {
                $page = 'search';
            }
            else {
                $page = 'message_list';
            }
            $title = '<a href="?page='.$page.'&amp;list_path='.$this->html_safe($this->get('list_parent')).
                '">'.$this->html_safe($list_name).'</a>';
            if (count($this->get('mailbox_list_title', array())) > 1) {
                $mb_title = $this->get('mailbox_list_title', array());
                $title .= '<img class="path_delim" src="'.Hm_Image_Sources::$caret.'" alt="&gt;" />'.
                    '<a href="?page='.$page.'&amp;list_path='.$this->html_safe($this->get('list_path')).'">'.$this->html_safe($mb_title[1]).'</a>';
            }
        }
        elseif ($this->get('mailbox_list_title')) {
            $title = '<a href="?page=message_list&amp;list_path='.$this->html_safe($this->get('list_path')).'">'.
                implode('<img class="path_delim" src="'.Hm_Image_Sources::$caret.'" alt="&gt;" />', $this->get('mailbox_list_title', array())).'</a>';
        }
        else {
            $title = '';
        }
        $res = '';
        if ($this->get('uid')) {
            $res .= '<input type="hidden" class="msg_uid" value="'.$this->html_safe($this->get('uid')).'" />';
        }
        $res .= '<div class="content_title">'.$title.'</div>';
        $res .= '<div class="msg_text">';
        return $res;
    }
}

class Hm_Output_message_end extends Hm_Output_Module {
    protected function output($input, $format) {
        return '</div>';
    }
}

class Hm_Output_notfound_content extends Hm_Output_Module {
    protected function output($input, $format) {
        $res = '<div class="content_title">Page Not Found!</div>';
        $res .= '<div class="empty_list"><br />Nothingness</div>';
        return $res;
    }
}

class Hm_Output_message_list_start extends Hm_Output_Module {
    protected function output($input, $format) {
        $res = '<table class="message_table">';
        if (!$this->get('no_message_list_headers')) {
            $res .= '<colgroup><col class="chkbox_col"><col class="source_col">'.
            '<col class="from_col"><col class="subject_col"><col class="date_col">'.
            '<col class="icon_col"></colgroup><thead><tr><th></th><th class="source">'.
            'Source</th><th class="from">From</th><th class="subject">Subject</th>'.
            '<th class="msg_date">Date</th><th></th></tr></thead>';
        }
        $res .= '<tbody>';
        return $res;
    }
}

class Hm_Output_message_list_heading extends Hm_Output_Module {
    protected function output($input, $format) {
        /* TODO: remove module specific stuff */
        if ($this->in('list_path', array('unread', 'flagged', 'pop3', 'combined_inbox', 'feeds'))) {
            if ($this->get('list_path') == 'combined_inbox') {
                $path = 'all';
            }
            else {
                $path = $this->get('list_path');
            }
            $config_link = '<a href="?page=settings#'.$path.'_setting"><img alt="Configure" class="refresh_list" src="'.Hm_Image_Sources::$cog.'" width="20" height="20" /></a>';
        }
        else {
            $config_link = '';
        }
        $res = '';
        $res .= '<div class="message_list"><div class="content_title">';
        $res .= message_controls().
            implode('<img class="path_delim" src="'.Hm_Image_Sources::$caret.'" alt="&gt;" width="8" height="8" />', $this->get('mailbox_list_title', array()));
        $res .= '<div class="list_controls">';
        $res .= '<a class="refresh_link" href="#"><img alt="Refresh" class="refresh_list" src="'.Hm_Image_Sources::$refresh.'" width="20" height="20" /></a>';
        $res .= $config_link;
        $res .= '</div>';
	    $res .= message_list_meta($this->module_output(), $this);
        $res .= '</div>';
        return $res;
    }
}

class Hm_Output_message_list_end extends Hm_Output_Module {
    protected function output($input, $format) {
        $res = '</tbody></table><div class="page_links"></div></div>';
        return $res;
    }
}

?>
