<?php
class AssessmentsOptionsPage{
    //                          __              __      
    //   _________  ____  _____/ /_____ _____  / /______
    //  / ___/ __ \/ __ \/ ___/ __/ __ `/ __ \/ __/ ___/
    // / /__/ /_/ / / / (__  ) /_/ /_/ / / / / /_(__  ) 
    // \___/\____/_/ /_/____/\__/\__,_/_/ /_/\__/____/  
    const PARENT_PAGE = 'edit.php?post_type=assessment';

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
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        add_submenu_page(self::PARENT_PAGE, __('Assessments options'), __('Assessments options'), 'administrator', __FILE__, array($this, 'create_admin_page'), ''); 
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
                settings_fields('assessments_options_page');   
                do_settings_sections(__FILE__);
                ?>
                <table class="gctable industry-table" data-count="<?php echo count($this->options['industry']); ?>">
                    <colgroup>
                        <col width="50">
                        <col width="1000">
                        <col width="50">
                      </colgroup>
                    <thead>
                        <tr>
                            <th><?php _e('#'); ?></th>
                            <th><?php _e('Industry'); ?></th>  
                            <th><?php _e('Remove'); ?></th>                  
                        </tr>
                    </thead>
                    <tbody>             
                        <?php                   
                            if($this->options['industry'])
                            {
                                foreach ($this->options['industry'] as $key => &$industry) 
                                {  
                                    echo '<tr>';                                    
                                    printf('<td><input type="text" name="assessments_options[industry_keys][%s]" value="%s" class="w100"></td>', $key, $key);
                                    printf('<td><input type="text" name="assessments_options[industry][%s]" value="%s" class="w100"></td>', $key, $industry);                            
                                    printf('<td><button type="button" class="button button-red remove-industry">%s</button></td>', __('Remove item'));
                                    echo '</tr>';
                                }                       
                            }
                        ?>              
                    </tbody>
                </table>
                <button type="button" class="button add-industry"><?php _e('Add industry'); ?></button>
                <?php
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
        return get_option('assessments_options');
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting('assessments_options_page', 'assessments_options', array($this, 'sanitize'));
        add_settings_section('default_settings', __('Options'), null, __FILE__);         
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input)
    {
        $new_input = array();     

        if(isset($input['industry'])) 
        {
            foreach ($input['industry'] as $key => $value) 
            {                
                $k = $input['industry_keys'][$key];
                if($value['industry'] != '') $arr[$k] = $value;
            }
            ksort($arr, SORT_NUMERIC);
            $new_input['industry'] = $arr;
        }
            

        return $new_input;
    }
    
}
// =========================================================
// LAUNCH
// =========================================================
$GLOBALS['assessments_options'] = new AssessmentsOptionsPage();