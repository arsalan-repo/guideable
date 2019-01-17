<?php

    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: https://docs.reduxframework.com
     * */

    if ( ! class_exists( 'Redux_Framework_sample_config' ) ) {

        class Redux_Framework_sample_config {

            public $args = array();
            public $sections = array();
            public $theme;
            public $ReduxFramework;

            public function __construct() {

                if ( ! class_exists( 'ReduxFramework' ) ) {
                    return;
                }

                // This is needed. Bah WordPress bugs.  ;)
                if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                    $this->initSettings();
                } else {
                    add_action( 'plugins_loaded', array( $this, 'initSettings' ), 10 );
                }

            }

            public function initSettings() {

                // Just for demo purposes. Not needed per say.
                $this->theme = wp_get_theme();

                // Set the default arguments
                $this->setArguments();

                // Set a few help tabs so you can see how it's done
                $this->setHelpTabs();

                // Create the sections and fields
                $this->setSections();

                if ( ! isset( $this->args['opt_name'] ) ) { // No errors please
                    return;
                }

                // If Redux is running as a plugin, this will remove the demo notice and links
                //add_action( 'redux/loaded', array( $this, 'remove_demo' ) );

                // Function to test the compiler hook and demo CSS output.
                // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
                //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 3);

                // Change the arguments after they've been declared, but before the panel is created
                //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );

                // Change the default value of a field after it's been set, but before it's been useds
                //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );

                // Dynamically add a section. Can be also used to modify sections/fields
                //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

                $this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
            }

            /**
             * This is a test function that will let you see when the compiler hook occurs.
             * It only runs if a field    set with compiler=>true is changed.
             * */
            function compiler_action( $options, $css, $changed_values ) {
                echo '<h1>The compiler hook has run!</h1>';
                echo "<pre>";
                print_r( $changed_values ); // Values that have changed since the last save
                echo "</pre>";
                //print_r($options); //Option values
                //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

                /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
            }

            /**
             * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
             * Simply include this function in the child themes functions.php file.
             * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
             * so you must use get_template_directory_uri() if you want to use any of the built in icons
             * */
            function dynamic_section( $sections ) {
                //$sections = array();
                $sections[] = array(
                    'title'  => __( 'Section via hook', 'redux-framework-demo' ),
                    'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo' ),
                    'icon'   => 'el-icon-paper-clip',
                    // Leave this as a blank section, no options just some intro text set above.
                    'fields' => array()
                );

                return $sections;
            }

            /**
             * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
             * */
            function change_arguments( $args ) {
                //$args['dev_mode'] = true;

                return $args;
            }

            /**
             * Filter hook for filtering the default value of any given field. Very useful in development mode.
             * */
            function change_defaults( $defaults ) {
                $defaults['str_replace'] = 'Testing filter hook!';

                return $defaults;
            }

            // Remove the demo link and the notice of integrated demo from the redux-framework plugin
            function remove_demo() {

                // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
                if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                    remove_filter( 'plugin_row_meta', array(
                        ReduxFrameworkPlugin::instance(),
                        'plugin_metalinks'
                    ), null, 2 );

                    // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                    remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
                }
            }

            public function setSections() {

                /**
                 * Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
                 * */
                // Background Patterns Reader
                $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
                $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
                $sample_patterns      = array();

                if ( is_dir( $sample_patterns_path ) ) :

                    if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) :
                        $sample_patterns = array();

                        while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                            if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                                $name              = explode( '.', $sample_patterns_file );
                                $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                                $sample_patterns[] = array(
                                    'alt' => $name,
                                    'img' => $sample_patterns_url . $sample_patterns_file
                                );
                            }
                        }
                    endif;
                endif;

                ob_start();

                $ct          = wp_get_theme();
                $this->theme = $ct;
                $item_name   = $this->theme->get( 'Name' );
                $tags        = $this->theme->Tags;
                $screenshot  = $this->theme->get_screenshot();
                $class       = $screenshot ? 'has-screenshot' : '';

                $customize_title = sprintf( __( 'Customize &#8220;%s&#8221;', 'redux-framework-demo' ), $this->theme->display( 'Name' ) );

                ?>
<!--Code used to display Theme Information Tab-->
                <div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
                    <?php if ( $screenshot ) : ?>
                        <?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
                            <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize"
                               title="<?php echo esc_attr( $customize_title ); ?>">
                                <img src="<?php echo esc_url( $screenshot ); ?>"
                                     alt="<?php esc_attr_e( 'Current theme preview' ); ?>"/>
                            </a>
                        <?php endif; ?>
                        <img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>"
                             alt="<?php esc_attr_e( 'Current theme preview' ); ?>"/>
                    <?php endif; ?>

                    <h4><?php echo $this->theme->display( 'Name' ); ?></h4>

                    <div>
                        <ul class="theme-info">
                            <li><?php printf( __( 'By %s', 'redux-framework-demo' ), $this->theme->display( 'Author' ) ); ?></li>
                            <li><?php printf( __( 'Version %s', 'redux-framework-demo' ), $this->theme->display( 'Version' ) ); ?></li>
                            <li><?php echo '<strong>' . __( 'Tags', 'redux-framework-demo' ) . ':</strong> '; ?><?php printf( $this->theme->display( 'Tags' ) ); ?></li>
                        </ul>
                        <p class="theme-description"><?php echo $this->theme->display( 'Description' ); ?></p>
                        <?php
                            if ( $this->theme->parent() ) {
                                printf( ' <p class="howto">' . __( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.' ) . '</p>', __( 'http://codex.wordpress.org/Child_Themes', 'redux-framework-demo' ), $this->theme->parent()->display( 'Name' ) );
                            }
                        ?>

                    </div>
                </div>

                <?php
                $item_info = ob_get_contents();

                ob_end_clean();

                $sampleHTML = '';
                if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
                    Redux_Functions::initWpFilesystem();

                    global $wp_filesystem;

                    $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
                }

                // ACTUAL DECLARATION OF SECTIONS
                /* Social */
		$this->sections[] = array (
		
			'title' => __('Logo and Icon', 'kraftives'),
			'fields' => array (
                array(
                    'id'       => 'site-logo',
                    'type'     => 'media',
                    'url'      => true,
                    'title'    => __( 'Header Logo', 'redux-framework-demo' ),
                ),
				
				array(
                    'id'       => 'login-logo',
                    'type'     => 'media',
                    'url'      => true,
                    'title'    => __( 'Login Page Logo', 'redux-framework-demo' ),
                ),
				
				array(
                    'id'       => 'favicon-logo',
                    'type'     => 'media',
                    'title'    => __( 'Favicon', 'redux-framework-demo' ),
                    'preview' => false,
                ),
				
                array(
                    'id'       => 'footer-logo',
                    'type'     => 'media',
                    'url'      => true,
                    'title'    => __( 'Footer Logo', 'redux-framework-demo' ),
                ),



			),
			//'icon' => 'el-icon-home',
		);
		
		
		
		
		/* Footer Banner Data */
		$this->sections[] = array (
			'title' => __('Social Media Links', 'kraftives'),
			'fields' => array (
			
				array (
					'desc' => __('Enter Facebook Link.', 'kraftives'),
					'id' => 'facebook',
					'type' => 'text',
					'title' => __('Facebook', 'kraftives'),
					'validate' => 'url',
				),
				array (
					'desc' => __('Enter Twitter Link.', 'kraftives'),
					'id' => 'twitter',
					'type' => 'text',
					'title' => __('Twitter', 'kraftives'),
					'validate' => 'url',
				),
                array (
                    'desc' => __('Enter Instagram Link.', 'kraftives'),
                    'id' => 'instagram',
                    'type' => 'text',
                    'title' => __('Instagram', 'kraftives'),
                    'validate' => 'url',
                ),							
				
			),
			'icon' => 'el-icon-network',
		);
		/* Footer Banner Data END */
		
		
		
		
		
		
		 /* Contact */
		 $this->sections[] = array (
			'title' => __('Contact', 'kraftives'),
			'fields' => array (
                array (
                    'id' => 'contact',
                    'type' => 'text',
                    'title' => __('Contact Phone', 'kraftives'),
                ),
                array (
                    'id' => 'email',
                    'type' => 'text',
                    'title' => __('Contact Email', 'kraftives'),
                    'validate' => 'email',
                ),
                array (
                    'id' => 'address',
                    'type' => 'textarea',
                    'title' => __('Address', 'kraftives'),
                    'rows'    => '2'
                ),
				array (
					'id' => 'phone',
					'type' => 'text',
					'title' => __('Contact 1', 'kraftives'),
                    'label'    => true,
                    'options'  => array(
                        'c1_name' => 'Name',
                        'c1_phone' => 'Phone',
                        'c1_email' => 'Email',
                    )
				),
                array (
                    'id' => 'phone',
                    'type' => 'text',
                    'title' => __('Contact 2', 'kraftives'),
                    'label'    => true,
                    'options'  => array(
                        'c2_name' => 'Name',
                        'c2_phone' => 'Phone',
                        'c2_email' => 'Email',
                    )
                ),
                array (
					'id' => 'contact_form',
					'type' => 'text',
					'title' => __('Contact Form Code', 'kraftives'),
                    'label'    => true,
                    'options'  => array(
                        'code1' => 'Contact Page',
                        'code2' => 'Opt-in Box',
                    )
				),


			),
			'icon' => 'el-icon-map-marker',
		);
		
                 /* Service Box */
         $this->sections[] = array (
            'title' => __('Service', 'kraftives'),
            'fields' => array (
                array (
                    'id' => 'heading',
                    'type' => 'text',
                    'title' => __('Box1 Title', 'kraftives'),
                ),
    array(
    'id'               => 'box1',
    'type'             => 'editor',
    'title'            => __('Box 1', 'kraftives'),
    'subtitle'         => __('Subtitle text would go here.', 'kraftives'),
    'args'   => array(
        'teeny'            => true,
        'textarea_rows'    => 10
        )
    ),

        array(
    'id'               => 'box2',
    'type'             => 'editor',
    'title'            => __('Box 2', 'kraftives'),
    'subtitle'         => __('Subtitle text would go here.', 'kraftives'),
    'args'   => array(
        'teeny'            => true,
        'textarea_rows'    => 10
        )
    ),
),
            'icon' => 'el-icon-home',
        );
        
		
		
		
		
		$this->sections[] = array(
			'icon'      => 'el-icon-cogs',
			'title'     => __('General Settings', 'redux-framework-demo'),
			'fields'    => array(
				array(
					'id'        => 'opt-layout',
					'type'      => 'image_select',
					'compiler'  => true,
					'title'     => __('Main Layout', 'redux-framework-demo'),
					'subtitle'  => __('Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.', 'redux-framework-demo'),
					'options'   => array(
						'1' => array('alt' => '1 Column',       'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
						'2' => array('alt' => '2 Column Left',  'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
						'3' => array('alt' => '2 Column Right', 'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
						'4' => array('alt' => '3 Column Middle','img' => ReduxFramework::$_url . 'assets/img/3cm.png'),
						'5' => array('alt' => '3 Column Left',  'img' => ReduxFramework::$_url . 'assets/img/3cl.png'),
						'6' => array('alt' => '3 Column Right', 'img' => ReduxFramework::$_url . 'assets/img/3cr.png')
					),
					'default'   => '2'
				),
				array(
					'id'        => 'opt-textarea',
					'type'      => 'textarea',
					'required'  => array('layout', 'equals', '1'),
					'title'     => __('Tracking Code', 'redux-framework-demo'),
					'subtitle'  => __('Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.', 'redux-framework-demo'),
					'validate'  => 'js',
					'desc'      => 'Validate that it\'s javascript!',
				),
				array(
					'id'        => 'opt-ace-editor-css',
					'type'      => 'ace_editor',
					'title'     => __('CSS Code', 'redux-framework-demo'),
					'subtitle'  => __('Paste your CSS code here.', 'redux-framework-demo'),
					'mode'      => 'css',
					'theme'     => 'monokai',
					'desc'      => 'Possible modes can be found at <a href="http://ace.c9.io" target="_blank">http://ace.c9.io/</a>.',
					'default'   => "#header{\nmargin: 0 auto;\n}"
				),
				array(
					'id'        => 'opt-ace-editor-js',
					'type'      => 'ace_editor',
					'title'     => __('JS Code', 'redux-framework-demo'),
					'subtitle'  => __('Paste your JS code here.', 'redux-framework-demo'),
					'mode'      => 'javascript',
					'theme'     => 'chrome',
					'desc'      => 'Possible modes can be found at <a href="http://ace.c9.io" target="_blank">http://ace.c9.io/</a>.',
					'default'   => "jQuery(document).ready(function(){\n\n});"
				),
				array(
					'id'        => 'opt-ace-editor-php',
					'type'      => 'ace_editor',
					'title'     => __('PHP Code', 'redux-framework-demo'),
					'subtitle'  => __('Paste your PHP code here.', 'redux-framework-demo'),
					'mode'      => 'php',
					'theme'     => 'chrome',
					'desc'      => 'Possible modes can be found at <a href="http://ace.c9.io" target="_blank">http://ace.c9.io/</a>.',
					'default'   => '<?php\nisset ( $redux ) ? true : false;\n?>'
				),
				array(
					'id'        => 'opt-editor',
					'type'      => 'editor',
					'title'     => __('Footer Text', 'redux-framework-demo'),
					'subtitle'  => __('You can use the following shortcodes in your footer text: [wp-url] [site-url] [theme-url] [login-url] [logout-url] [site-title] [site-tagline] [current-year]', 'redux-framework-demo'),
					//'default'   => 'Powered by Redux Framework.',
				),
				array(
					'id'        => 'password',
					'type'      => 'password',
					'username'  => true,
					'title'     => 'SMTP Account',
					//'placeholder' => array('username' => 'Enter your Username')
				)
			)
		);

       
        
        
       


                $this->sections[] = array(
                    'title'  => __( 'Import / Export', 'redux-framework-demo' ),
                    'desc'   => __( 'Import and Export your Redux Framework settings from file, text or URL.', 'redux-framework-demo' ),
                    'icon'   => 'el-icon-refresh',
                    'fields' => array(
                        array(
                            'id'         => 'opt-import-export',
                            'type'       => 'import_export',
                            'title'      => 'Import Export',
                            'subtitle'   => 'Save and restore your Redux options',
                            'full_width' => false,
                        ),
                    ),
                );

                $this->sections[] = array(
                    'type' => 'divide',
                );

                $theme_info = '<div class="redux-framework-section-desc">';
                $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . __( '<strong>Theme URL:</strong> ', 'redux-framework-demo' ) . '<a href="' . $this->theme->get( 'ThemeURI' ) . '" target="_blank">' . $this->theme->get( 'ThemeURI' ) . '</a></p>';
                $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . __( '<strong>Author:</strong> ', 'redux-framework-demo' ) . $this->theme->get( 'Author' ) . '</p>';
                $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . __( '<strong>Version:</strong> ', 'redux-framework-demo' ) . $this->theme->get( 'Version' ) . '</p>';
                $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get( 'Description' ) . '</p>';
                $tabs = $this->theme->get( 'Tags' );
                if ( ! empty( $tabs ) ) {
                    $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . __( '<strong>Tags:</strong> ', 'redux-framework-demo' ) . implode( ', ', $tabs ) . '</p>';
                }
                $theme_info .= '</div>';


                $this->sections[] = array(
                    'icon'   => 'el-icon-info-sign',
                    'title'  => __( 'Theme Information', 'redux-framework-demo' ),
                    'desc'   => __( '<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo' ),
                    'fields' => array(
                        array(
                            'id'      => 'opt-raw-info',
                            'type'    => 'raw',
                            'content' => $item_info,
                        )
                    ),
                );

                if ( file_exists( trailingslashit( dirname( __FILE__ ) ) . 'README.html' ) ) {
                    $tabs['docs'] = array(
                        'icon'    => 'el-icon-book',
                        'title'   => __( 'Documentation', 'redux-framework-demo' ),
                        'content' => nl2br( file_get_contents( trailingslashit( dirname( __FILE__ ) ) . 'README.html' ) )
                    );
                }
            }

            public function setHelpTabs() {

                // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-1',
                    'title'   => __( 'Theme Information 1', 'redux-framework-demo' ),
                    'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo' )
                );

                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-2',
                    'title'   => __( 'Theme Information 2', 'redux-framework-demo' ),
                    'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo' )
                );

                // Set the help sidebar
                $this->args['help_sidebar'] = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo' );
            }

            /**
             * All the possible arguments for Redux.
             * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
             * */
            public function setArguments() {

                $theme = wp_get_theme(); // For use with some settings. Not necessary.

                $this->args = array(
                    // TYPICAL -> Change these values as you need/desire
                    'opt_name'             => 'fdata',
                    // This is where your data is stored in the database and also becomes your global variable name.
                    'display_name'         => $theme->get( 'Name' ),
                    // Name that appears at the top of your panel
                    'display_version'      => $theme->get( 'Version' ),
                    // Version that appears at the top of your panel
                    'menu_type'            => 'menu',
                    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                    'allow_sub_menu'       => true,
                    // Show the sections below the admin menu item or not
                    'menu_title'           => __( 'Theme Options', 'redux-framework-demo' ),
                    'page_title'           => __( 'Theme Options', 'redux-framework-demo' ),
                    // You will need to generate a Google API key to use this feature.
                    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                    'google_api_key'       => '',
                    // Set it you want google fonts to update weekly. A google_api_key value is required.
                    'google_update_weekly' => false,
                    // Must be defined to add google fonts to the typography module
                    'async_typography'     => true,
                    // Use a asynchronous font on the front end or font string
                    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                    'admin_bar'            => true,
                    // Show the panel pages on the admin bar
                    'admin_bar_icon'     => 'dashicons-portfolio',
                    // Choose an icon for the admin bar menu
                    'admin_bar_priority' => 50,
                    // Choose an priority for the admin bar menu
                    'global_variable'      => '',
                    // Set a different name for your global variable other than the opt_name
                    'dev_mode'             => false,
                    // Show the time the page took to load, etc
                    'update_notice'        => true,
                    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
                    'customizer'           => true,
                    // Enable basic customizer support
                    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                    // OPTIONAL -> Give you extra features
                    'page_priority'        => null,
                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                    'page_parent'          => 'themes.php',
                    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                    'page_permissions'     => 'manage_options',
                    // Permissions needed to access the options panel.
                    'menu_icon'            => '',
                    // Specify a custom URL to an icon
                    'last_tab'             => '',
                    // Force your panel to always open to a specific tab (by id)
                    'page_icon'            => 'icon-themes',
                    // Icon displayed in the admin panel next to your menu_title
                    'page_slug'            => 'theme_options',
                    // Page slug used to denote the panel
                    'save_defaults'        => true,
                    // On load save the defaults to DB before user clicks save or not
                    'default_show'         => false,
                    // If true, shows the default value next to each field that is not the default value.
                    'default_mark'         => '',
                    // What to print by the field's title if the value shown is default. Suggested: *
                    'show_import_export'   => true,
                    // Shows the Import/Export panel when not used as a field.

                    // CAREFUL -> These options are for advanced use only
                    'transient_time'       => 60 * MINUTE_IN_SECONDS,
                    'output'               => true,
                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                    'output_tag'           => true,
                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                    // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

                    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                    'database'             => '',
                    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                    'system_info'          => false,
                    // REMOVE
					
					
					'hints' => 
						array(
						  'icon' => 'el el-question-sign',
						  'icon_position' => 'right',
						  'icon_size' => 'normal',
						  'tip_style' => 
						  array(
							'color' => 'light',
							'shadow' => '1',
							'style' => 'youtube',
						  ),
						  'tip_position' => 
						  array(
							'my' => 'top left',
							'at' => 'bottom right',
						  ),
						  'tip_effect' => 
						  array(
							'show' => 
							array(
							  'duration' => '500',
							  'event' => 'mouseover',
							),
							'hide' => 
							array(
							  'duration' => '500',
							  'event' => 'mouseleave unfocus',
							),
						  ),
						),
                );

                // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
                $this->args['admin_bar_links'][] = array(
                    'id'    => 'redux-docs',
                    'href'   => 'http://docs.reduxframework.com/',
                    'title' => __( 'Documentation', 'redux-framework-demo' ),
                );

                $this->args['admin_bar_links'][] = array(
                    //'id'    => 'redux-support',
                    'href'   => 'https://github.com/ReduxFramework/redux-framework/issues',
                    'title' => __( 'Support', 'redux-framework-demo' ),
                );

                $this->args['admin_bar_links'][] = array(
                    'id'    => 'redux-extensions',
                    'href'   => 'reduxframework.com/extensions',
                    'title' => __( 'Extensions', 'redux-framework-demo' ),
                );

             
            }

            public function validate_callback_function( $field, $value, $existing_value ) {
                $error = true;
                $value = 'just testing';

                /*
              do your validation

              if(something) {
                $value = $value;
              } elseif(something else) {
                $error = true;
                $value = $existing_value;
                
              }
             */

                $return['value'] = $value;
                $field['msg']    = 'your custom error message';
                if ( $error == true ) {
                    $return['error'] = $field;
                }

                return $return;
            }

            public function class_field_callback( $field, $value ) {
                print_r( $field );
                echo '<br/>CLASS CALLBACK';
                print_r( $value );
            }

        }

        global $reduxConfig;
        $reduxConfig = new Redux_Framework_sample_config();
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ):
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    endif;

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ):
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error = true;
            $value = 'just testing';

            /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            
          }
         */

            $return['value'] = $value;
            $field['msg']    = 'your custom error message';
            if ( $error == true ) {
                $return['error'] = $field;
            }

            return $return;
        }
    endif;
