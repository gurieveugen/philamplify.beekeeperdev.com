<?php
class SubscribersPage{ 
    //                    __  __              __    
    //    ____ ___  ___  / /_/ /_  ____  ____/ /____
    //   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
    //  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
    // /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_plugin_page'));  

        add_action('wp_ajax_resetratings', array($this, 'resetRatingsAJAX'));
        add_action('wp_ajax_nopriv_resetratings', array($this, 'resetRatingsAJAX'));    
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        add_menu_page( __('Subscribers report'), __('Subscribers'), 'administrator', __CLASS__, array($this, 'create_admin_page'), '');
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>Subscribers report</h2>
            <table class="gctable">
                <col width="50"> 
                <col width="500">
                <thead>                    
                    <tr>
                        <th>#</th>
                        <th><?php _e('Email'); ?></th>                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $posts = $this->getAllItems();
                    $i = 1;
                    if($posts)
                    {
                        foreach ($posts as $key => $value) 
                        {                       
                            ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><a href="mailto:<?php echo $value; ?>"><?php echo $value; ?></a></td>                            
                            </tr>
                            <?php
                        }    
                    }
                    ?>                         
                </tbody>
            </table>
            <button class="button" type="button" id="reset-subscribers"><?php _e('Delete all'); ?></button>
        </div>
        <?php
    }

    /**
     * Get all subscribers
     * @return array
     */
    public function getAllItems()
    {       
        return get_option(AJAX::SUBSCRIBE_OPTION);
    }
}
// =========================================================
// LAUNCH
// =========================================================
$GLOBALS['subscribers_page'] = new SubscribersPage();