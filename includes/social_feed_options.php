<?php
class SocialFeedOptions{
    //                          __              __      
    //   _________  ____  _____/ /_____ _____  / /______
    //  / ___/ __ \/ __ \/ ___/ __/ __ `/ __ \/ __/ ___/
    // / /__/ /_/ / / / (__  ) /_/ /_/ / / / / /_(__  ) 
    // \___/\____/_/ /_/____/\__/\__,_/_/ /_/\__/____/  
    const CAPTION          = 'Social feed';
    const OPTION_FIELD     = 'sfoptions';
    const SETTINGS_FIELD   = 'sc_options_page';
    const SETTINGS_SECTION = 'feed_settings';
    //                __  _                 
    //   ____  ____  / /_(_)___  ____  _____
    //  / __ \/ __ \/ __/ / __ \/ __ \/ ___/
    // / /_/ / /_/ / /_/ / /_/ / / / (__  ) 
    // \____/ .___/\__/_/\____/_/ /_/____/  
    //     /_/                              
    private $options;

    //                    __  __              __    
    //    ____ ___  ___  / /_/ /_  ____  ____/ /____
    //   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
    //  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
    // /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        add_menu_page(__(self::CAPTION), __(self::CAPTION), 'administrator', __CLASS__, array($this, 'create_admin_page'), ''); 
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        $this->options = $this->getAll();       

        ?>
        <div class="wrap">
            <?php screen_icon(); ?>                 
            <form method="post" action="options.php">
            <?php                
                settings_fields(self::SETTINGS_FIELD);   
                do_settings_sections(__CLASS__);
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Get all options
     */
    public function getAll()
    {
        return get_option(self::OPTION_FIELD);
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(self::SETTINGS_FIELD, self::OPTION_FIELD, array($this, 'sanitize'));
        add_settings_section(self::SETTINGS_SECTION, __('Options'), null, __CLASS__); 

        add_settings_field('facebook', __('Facebook user'), array($this, 'facebook_callback'), __CLASS__, self::SETTINGS_SECTION);
        add_settings_field('twitter', __('Twitter user'), array($this, 'twitter_callback'), __CLASS__, self::SETTINGS_SECTION);
        add_settings_field('google_plus', __('Google+ user'), array($this, 'google_plus_callback'), __CLASS__, self::SETTINGS_SECTION);       
        add_settings_field('count', __('Count'), array($this, 'count_callback'), __CLASS__, self::SETTINGS_SECTION);       
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input)
    {
        $new_input = array();     

        if(isset($input['facebook'])) $new_input['facebook']       = strip_tags($input['facebook']);
        if(isset($input['twitter'])) $new_input['twitter']         = strip_tags($input['twitter']);
        if(isset($input['google_plus'])) $new_input['google_plus'] = strip_tags($input['google_plus']);
        if(isset($input['count'])) $new_input['count']                     = intval($input['count']);

        return $new_input;
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function facebook_callback()
    {
        printf('<input type="text" class="regular-text" id="facebook" name="'.self::OPTION_FIELD.'[facebook]" value="%s" />', isset($this->options['facebook']) ? esc_attr($this->options['facebook']) : '');
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function twitter_callback()
    {
        printf('<input type="text" class="regular-text" id="twitter" name="'.self::OPTION_FIELD.'[twitter]" value="%s" />', isset($this->options['twitter']) ? esc_attr($this->options['twitter']) : '');
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function google_plus_callback()
    {
        printf('<input type="text" class="regular-text" id="google_plus" name="'.self::OPTION_FIELD.'[google_plus]" value="%s" />', isset($this->options['google_plus']) ? esc_attr($this->options['google_plus']) : '');
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function count_callback()
    {
        printf('<input type="text" class="regular-text" id="count" name="'.self::OPTION_FIELD.'[count]" value="%s" />', isset($this->options['count']) ? intval($this->options['count']) : 0);
    }
    
}
// =========================================================
// LAUNCH
// =========================================================
$GLOBALS[SocialFeedOptions::OPTION_FIELD] = new SocialFeedOptions();