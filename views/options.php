<?php

Class wptm_admin {

    var $version = '1.2.4';

    function wptm_admin() {

        // Load language file
        $locale = get_locale();
        if ( !empty($locale) )
        //load_textdomain('wp-category-meta', WPTM_ABSPATH.'lang/wp-category-meta-'.$locale.'.mo');

        add_action('admin_head', array(&$this, 'wptm_options_style'));
        add_action('admin_menu', array(&$this, 'wptm_add_options_panel'));

    }
    
    //styling options page
    function wptm_options_style() {
        ?>
        <style type="text/css" media="screen">
            .titledesc {width:300px;}
            .thanks {width:400px; }
            .thanks p {padding-left:20px; padding-right:20px;}
            .info { background: #FFFFCC; border: 1px dotted #D8D2A9; padding: 10px; color: #333; }
            .info a { color: #333; text-decoration: none; border-bottom: 1px dotted #333 }
            .info a:hover { color: #666; border-bottom: 1px dotted #666; }
        </style>
    <?php
    }
    
    //Add configuration page into admin interface.
    function wptm_add_options_panel() {
        add_options_page('Category Meta Options', 'Category Meta', 7, 'category_meta', array(&$this, 'wptm_option_page'));
    }
    
    //build admin interface
    function wptm_option_page() 
    {   
        global $wp_version;
        $configuration = get_option("wptm_configuration");
        if(is_null($configuration) || $configuration == '')
        {
            $configuration = array();
        }
        
        if($_POST['action'] == "add") 
        {
            $new_meta_name = $_POST["new_meta_name"];
            $new_meta_name_sanitize = $_POST["new_meta_name_sanitize"];
            // Sanitize the entered string to avoid special char problems
            if($new_meta_name_sanitize == 1)
            {
                $new_meta_name = sanitize_title($new_meta_name);
            }
            $new_meta_type = $_POST["new_meta_type"];
            $new_meta_taxonomy = $_POST["new_meta_taxonomy"];
            $configuration[$new_meta_name] = array('type' => $new_meta_type, 'taxonomy' => $new_meta_taxonomy);
            
            update_option("wptm_configuration", $configuration);
            
        }
        else if($_POST['action'] == "delete") 
        {
            $delete_Meta_Name = $_POST["delete_Meta_Name"];
            unset($configuration[$delete_Meta_Name]);
            update_option("wptm_configuration", $configuration);
        }
    ?>
        <div class="wrap">
            <h2><?php _e('Category Meta Version:', 'wp-category-meta'); ?> <?php echo $this->version; ?></h2>
            <table class="widefat fixed">
                <thead>
                    <tr class="title">
                        <th scope="col" class="manage-column"><?php _e('Meta list', 'wp-category-meta'); ?></th>
                        <th scope="col" class="manage-column"></th>
                        <?php if($wp_version >= '3.0') {?>
                        <th scope="col" class="manage-column"></th>
                        <?php } ?>
                        <th scope="col" class="manage-column"></th>
                    </tr>
                    <tr class="title">
                        <th scope="col" class="manage-column"><?php _e('Meta Name', 'wp-category-meta'); ?></th>
                        <th scope="col" class="manage-column"><?php _e('Meta Type', 'wp-category-meta'); ?></th>
                        <?php if($wp_version >= '3.0') {?>
                        <th scope="col" class="manage-column"><?php _e('Meta Taxonomy', 'wp-category-meta'); ?></th>
                        <?php } ?>
                        <th scope="col" class="manage-column"><?php _e('Action', 'wp-category-meta'); ?></th>
                    </tr>
                </thead>
                <?php 
                    foreach($configuration as $name => $data)
                    { 
                        $type = '';
                        $taxonomy = 'category';
                        if(is_array($data)) {
                            $type = $data['type'];
                            $taxonomy = $data['taxonomy'];
                        } else {
                            $type = $data;
                        }
                        ?>
                <tr class="mainrow">        
                    <td class="titledesc"><?php echo $name;?></td>
                    <td class="forminp">
                        <?php echo $type;?>
                    </td>
                    <?php if($wp_version >= '3.0') {?>
                    <td class="forminp">
                        <?php echo $taxonomy;?>
                    </td>
                    <?php } ?>
                    <td class="forminp">
                        <form method="post">
                        <input type="hidden" name="action" value="delete" />
                        <input type="hidden" name="delete_Meta_Name" value="<?php echo $name;?>" />
                        <input type="submit" value="<?php _e('Delete this Meta', 'wp-category-meta') ?>" />
                        </form>
                    </td>
                </tr>
                    <?php }
                ?>
            </table>
            <br/>
            <form method="post">
                <table class="widefat">
                    <thead>
                        <tr class="title">
                            <th scope="col" class="manage-column"><?php _e('Add a Meta', 'wp-category-meta'); ?></th>
                            <th scope="col" class="manage-column"></th>
                        </tr>
                    </thead>
                    <tr class="mainrow">        
                        <td class="titledesc"><?php _e('Meta Name','wp-category-meta'); ?>:</td>
                        <td class="forminp">
                            <input type="text" id="new_meta_name" name="new_meta_name" value="" />
                        </td>
                    </tr>
                    <tr class="mainrow">        
                        <td class="titledesc"><?php _e('Sanitize meta name','wp-category-meta'); ?>:</td>
                        <td class="forminp">
                            <input type="checkbox" id="new_meta_name_sanitize" name="new_meta_name_sanitize" value="1" checked="checked" />
                        </td>
                    </tr>
                    <tr class="mainrow">        
                        <td class="titledesc"><?php _e('Meta Type','wp-category-meta'); ?>:</td>
                        <td class="forminp">
                            <select id="new_meta_type" name="new_meta_type">
                                <option value="text"><?php _e('Text','wp-category-meta'); ?></option>
                                <option value="textarea"><?php _e('Text Area','wp-category-meta'); ?></option>
                                <option value="image"><?php _e('Image','wp-category-meta'); ?></option>
                                <option value="checkbox"><?php _e('Check Box','wp-category-meta'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <?php if($wp_version >= '3.0') {?>
                    <tr class="mainrow">        
                        <td class="titledesc"><?php _e('Meta Toxonomy','wp-category-meta'); ?>:</td>
                        <td class="forminp">
                            <select id="new_meta_taxonomy" name="new_meta_taxonomy">
                                <?php 
                                    $taxonomies=get_taxonomies('','names'); 
                                    foreach ($taxonomies as $taxonomy ) {
                                      echo '<option value="'.$taxonomy.'">'. $taxonomy. '</option>';
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr class="mainrow">
                        <td class="titledesc">
                        <input type="hidden" name="action" value="add" />
                        </td>
                        <td class="forminp">
                        <input type="submit" value="<?php _e('Add Meta', 'wp-category-meta') ?>" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    <?php 
    }
}
?>