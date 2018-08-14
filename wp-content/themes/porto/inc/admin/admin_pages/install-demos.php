<?php
$theme = wp_get_theme();
if ($theme->parent_theme) {
    $template_dir =  basename(get_template_directory());
    $theme = wp_get_theme($template_dir);
}
$porto_url = 'http://www.portotheme.com/wordpress/porto/';
$demos = porto_demo_types();
$demo_filters = porto_demo_filters();

wp_register_script('porto-admin-isotope', porto_js.'/jquery.isotope.min.js', array('jquery'), porto_version, true);
wp_enqueue_script('porto-admin-isotope');

$required_plugins = porto_get_required_plugins_list();
$uninstalled_plugins = array();
$all_plugins = array();
foreach( $required_plugins as $plugin ) {
    if ( $plugin['required'] && is_plugin_inactive( $plugin['url'] ) ) {
        $uninstalled_plugins[$plugin['slug']] = $plugin;
    }
    $all_plugins[$plugin['slug']] = $plugin;
}
?>
<div class="wrap about-wrap porto-wrap">
    <h1><?php _e( 'Welcome to Porto!', 'porto' ); ?></h1>
    <div class="about-text"><?php echo esc_html__( 'Porto is now installed and ready to use! Read below for additional information. We hope you enjoy it!', 'porto' ); ?></div>
    <div class="porto-logo"><span class="porto-version"><?php _e( 'Version', 'porto' ); ?> <?php echo porto_version; ?></span></div>
    <h2 class="nav-tab-wrapper">
        <?php
        printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=porto' ), __( "Welcome", 'porto' ) );
        printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=porto-system' ), __( "System Status", 'porto' ) );
        printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=porto-plugins' ), __( "Plugins", 'porto' ) );
        printf( '<a href="#" class="nav-tab nav-tab-active">%s</a>', __( "Demos", 'porto' ) );
        printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=porto_settings' ), __( "Theme Options", 'porto' ) );
        ?>
    </h2>
    <div class="porto-section">
        <p class="about-description"><?php _e( 'Installing a demo provides pages, posts, menus, images, theme options, widgets and more. IMPORTANT: The included plugins need to be installed and activated before you install a demo. Please check the "System Status" tab to ensure your server meets all requirements for a successful import. Settings that need attention will be listed in red.', 'porto' ); ?></p>
        <div class="porto-install-demos">
            <div class="porto-install-demo mfp-hide">
                <div class="theme-img"></div>
                <div id="porto-install-options">
                    <h3>
                        <span class="theme-name"></span> <?php _e('Demo', 'porto') ?>
                        <span class="more-options"><?php _e( 'Details', 'porto' ); ?></span>
                    </h3>
                    <div class="porto-install-section" style="margin-bottom: 10px;">
                        <div class="porto-install-options-section" style="display: none;">
                            <label for="porto-import-options"><input type="checkbox" id="porto-import-options" value="1" checked="checked"/> <?php _e('Import theme options', 'porto') ?></label>
                            <input type="hidden" id="porto-install-demo-type" value="landing"/>
                            <label for="porto-reset-menus"><input type="checkbox" id="porto-reset-menus" value="1" checked="checked"/> <?php _e('Reset menus', 'porto') ?></label>
                            <label for="porto-reset-widgets"><input type="checkbox" id="porto-reset-widgets" value="1" checked="checked"/> <?php _e('Reset widgets', 'porto') ?></label>
                            <label for="porto-import-dummy"><input type="checkbox" id="porto-import-dummy" value="1" checked="checked"/> <?php _e('Import dummy content', 'porto') ?></label>
                            <label for="porto-import-widgets"><input type="checkbox" id="porto-import-widgets" value="1" checked="checked"/> <?php _e('Import widgets', 'porto') ?></label>
                            <label for="porto-import-icons"><input type="checkbox" id="porto-import-icons" value="1" checked="checked"/> <?php _e('Import icons for ultimate addons plugin', 'porto') ?></label>
                            <label for="porto-import-shortcodes"><input type="checkbox" id="porto-import-shortcodes" value="1"/> <?php _e('Import shortcode pages', 'porto') ?></label>
                            <label for="porto-override-contents"><input type="checkbox" id="porto-override-contents" value="1"/> <?php _e('Override existing contents', 'porto') ?></label>
                        </div>
                        <p><?php _e('Do you want to install demo? It can also take a minute to complete.', 'porto') ?></p>
                        <button class="btn btn-primary" id="porto-import-yes"><?php _e('Install', 'porto') ?></button>
                    </div>
                    <a href="#" class="live-site" target="_blank"><?php _e( 'Live Preview', 'porto' ); ?></a>
                </div>
                <div id="import-status"></div>
            </div>
            <div class="feature-section theme-browser rendered">
                <div class="demo-sort-filters">
                    <ul data-sort-id="theme-install-demos" class="sort-source">
                    <?php foreach ( $demo_filters as $filter_class => $filter_name) : ?>
                        <li data-filter-by="<?php echo esc_attr($filter_class) ?>" data-active="<?php echo ($filter_class=='all' ? 'true' : 'false') ?>"><a href="#"><?php echo $filter_name ?></a></li>
                    <?php endforeach; ?>
                    </ul>
                    <div class="clear"></div>
                </div>
                <div id="theme-install-demos">
                    <?php foreach ( $demos as $demo => $demo_details) : ?>
                        <?php
                            $uninstalled_demo_plugins = $uninstalled_plugins;
                            if ( isset( $demo_details['revslider'] ) && !empty( $demo_details['revslider'] ) && is_plugin_inactive( 'revslider/revslider.php' ) ) {
                                $uninstalled_demo_plugins['revslider'] = $all_plugins['revslider'];
                            }
                            if ( !empty( $demo_details['plugins'] ) ) {
                                foreach( $demo_details['plugins'] as $plugin ) {
                                    if ( is_plugin_inactive( $all_plugins[$plugin]['url'] ) ) {
                                        $uninstalled_demo_plugins[$plugin] = $all_plugins[$plugin];
                                    }
                                }
                            }
                        ?>
                        <div class="theme <?php echo $demo_details['filter'] ?>">
                            <div class="theme-wrapper">
                                <div class="theme-screenshot">
                                    <img src="<?php echo $demo_details['img']; ?>" />
                                </div>
                                <h3 class="theme-name" id="<?php echo $demo; ?>" data-live-url="<?php echo ( $demo != 'landing' ) ? $porto_url .  $demo : $porto_url; ?>"><?php echo $demo_details['alt']; ?></h3>
                                <?php if ( !empty( $uninstalled_demo_plugins ) ) : ?>
                                    <ul class="plugins-used">
                                        <?php foreach( $uninstalled_demo_plugins as $plugin ) : ?>
                                            <li>
                                                <div class="thumb">
                                                    <img src="<?php echo $plugin['image_url']; ?>" alt="" />
                                                </div>
                                                <div>
                                                    <h5><?php echo $plugin['name']; ?></h5>
                                                    <?php if ( $plugin['slug'] == 'revslider' ) : ?>
                                                        <p><?php _e( 'Demo sliders <u>will not</u> be installed if Revolution Slider is not active', 'porto' ); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="install"><a href="admin.php?page=porto-plugins"><?php _e( 'Install', 'porto' ); ?></a></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="porto-thanks">
        <p class="description"><?php _e( 'Thank you, we hope you to enjoy using Porto!', 'porto' ); ?></p>
    </div>
</div>