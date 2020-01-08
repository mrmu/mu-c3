<?php
/**
 * Register all custom post types for the plugin
 *
 * @link       https://audilu.com
 * @since      1.0.0
 *
 * @package    Mu_C3
 * @subpackage Mu_C3/includes
 */

/**
 * Register all custom post types for the plugin.
 *
 * Maintain a list of all custom post types that are registered throughout
 * the plugin, and register them with the WordPress API.
 *
 * @package    Mu_C3
 * @subpackage Mu_C3/includes
 * @author     Audi Lu <khl0327@gmail.com>
 */
class Mu_C3_Custom_Post_Type {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        add_shortcode( 'muc3', array($this, 'add_shortcode_muc3') );
    }

    private function minify_js($input) {
        if(trim($input) === "") return $input;
        return preg_replace(
            array(
                // Remove comment(s)
                '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
                // Remove white-space(s) outside the string and regex
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
                // Remove the last semicolon
                '#;+\}#',
                // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
                '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
                // --ibid. From `foo['bar']` to `foo.bar`
                '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
            ),
            array(
                '$1',
                '$1$2',
                '}',
                '$1$3',
                '$1.$3'
            ),
        $input);
    }

    public function add_shortcode_muc3($atts) {
        // ob_start();
        $atts = shortcode_atts( array(
            'chart' => ''
        ), $atts, 'muc3' );
     
        $html = '';
        if (!empty($atts['chart'])) {
            $post_id = absint($atts['chart']);
            // $args = array(
            //     'name'        => $the_slug,
            //     'post_type'   => 'c3_chart',
            //     'numberposts' => 1
            // );
            // $the_post = get_posts($args);
            // $post_id = $the_post[0]->ID;
            $_c3d_data_x = get_post_meta($post_id, '_c3d_data_x', true);
            $_c3d_data_columns =   get_post_meta($post_id, '_c3d_data_columns', true);
            $_c3d_grid_x_lines =   get_post_meta($post_id, '_c3d_grid_x_lines', true);
            $_c3d_grid_y_lines =   get_post_meta($post_id, '_c3d_grid_y_lines', true);
            $_c3d_axis_codes = get_post_meta($post_id, '_c3d_axis_codes', true);

            $ary_data_columns = array();
            $_c3d_data_columns_codes = '';
            if(isset($_c3d_data_columns) && is_array($_c3d_data_columns)){
                foreach ($_c3d_data_columns as $data_col) {
                    $ary_data_columns[] = '['.$data_col.']';
                }
                $_c3d_data_columns_codes = implode(',', $ary_data_columns);
            }

            $ary_grids_x_lines = array();
            $_c3d_grid_x_lines_codes = '';
            if(isset($_c3d_grid_x_lines) && is_array($_c3d_grid_x_lines)){
                foreach ($_c3d_grid_x_lines as $xlines) {
                    $ary_grids_x_lines[] = $xlines;
                }
                $_c3d_grid_x_lines_codes = implode(',', $ary_grids_x_lines);
            }

            $ary_grids_y_lines = array();
            $_c3d_grid_y_lines_codes = '';
            if(isset($_c3d_grid_y_lines) && is_array($_c3d_grid_y_lines)){
                foreach ($_c3d_grid_y_lines as $ylines) {
                    $ary_grids_y_lines[] = $ylines;
                }
                $_c3d_grid_y_lines_codes = implode(',', $ary_grids_y_lines);
            }

            $code_name = str_replace('-', '_', 'chart_'.$atts['chart']);
            $html .= '<div id="'.$code_name.'"></div>';

            $script = "
                jQuery(function(){
                    c3.generate({
                        bindto: '#".$code_name."',
                        size: {
                        },
                        data: {
                            x: '".$_c3d_data_x."',
                            columns: [
                                ".$_c3d_data_columns_codes."
                            ]
                        },
                        grid: {
                            x: {
                                lines: [
                                    ".$_c3d_grid_x_lines_codes."
                                ]
                            },
                            y: {
                                lines: [
                                    ".$_c3d_grid_y_lines_codes."
                                ]
                            }
                        },
                        axis: {
                            ".$_c3d_axis_codes."
                        }
                    });
                });
            ";
            wp_add_inline_script( 'c3-js', $this->minify_js($script) );
        }
        // $results = ob_get_clean();
        return $html;
    }
    
    public function reg() {

		if ( post_type_exists( "c3_chart" ) )
            return;

        $plugin_name = $this->plugin_name;

        // // Custom Taxonomy

		// $tax_singular  = __( 'taxonomy name', $plugin_name );
		// $tax_plural = __( 'taxonomy names', $plugin_name );
        // $rewrite   = array(
        //     'slug'         => 'taxonomy-name',
        //     'with_front'   => false,
        //     'hierarchical' => false
        // );
        // $public    = true;
        // $admin_capability = 'manage_categories';

        // register_taxonomy(
        //     "taxonomy_name",
        //     array( 'c3_chart' ),
        //     array(
        //         'hierarchical' 			=> true,
        //         'label' 				=> $tax_singular,
        //         'labels' => array(
        //             'name'              => $tax_singular,
        //             'singular_name'     => $tax_singular,
        //             'menu_name'         => ucwords( $tax_singular ),
        //             'search_items'      => sprintf( __( 'Search %s', $plugin_name ), $tax_plural ),
        //             'all_items'         => sprintf( __( 'All %s', $plugin_name ), $tax_plural ),
        //             'parent_item'       => sprintf( __( 'Parent %s', $plugin_name ), $tax_singular ),
        //             'parent_item_colon' => sprintf( __( 'Parent %s:', $plugin_name ), $tax_singular ),
        //             'edit_item'         => sprintf( __( 'Edit %s', $plugin_name ), $tax_singular ),
        //             'update_item'       => sprintf( __( 'Update %s', $plugin_name ), $tax_singular ),
        //             'add_new_item'      => sprintf( __( 'Add New %s', $plugin_name ), $tax_singular ),
        //             'new_item_name'     => sprintf( __( 'New %s Name', $plugin_name ),  $tax_singular )
        //         ),
        //         'show_ui' 				=> true,
        //         'public' 	     		=> $public,
        //         'capabilities'			=> array(
        //             'manage_terms' 		=> $admin_capability,
        //             'edit_terms' 		=> $admin_capability,
        //             'delete_terms' 		=> $admin_capability,
        //             'assign_terms' 		=> $admin_capability,
        //         ),
        //         'rewrite' 				=> $rewrite,
        //     )
        // );

        // Custom Post types

		$singular  = __( 'chart', $plugin_name );
        $plural = __( 'charts', $plugin_name );
        $menu_name = __( 'C3 charts', $plugin_name );

        $has_archive = false;
        $rewrite     = array(
            'slug'       => 'c3-chart',
            'with_front' => false,
            'feeds'      => true,
            'pages'      => false
        );

        register_post_type(
            "c3_chart",
            array(
                'labels' => array(
                    'name' 					=> $singular,
                    'singular_name' 		=> $singular,
                    'menu_name'             => sprintf( __( '%s', $plugin_name ), $menu_name ),
                    'all_items'             => sprintf( __( 'All %s', $plugin_name ), $plural ),
                    'add_new' 				=> __( 'Add New', $plugin_name ),
                    'add_new_item' 			=> sprintf( __( 'Add %s', $plugin_name ), $singular ),
                    'edit' 					=> __( 'Edit', $plugin_name ),
                    'edit_item' 			=> sprintf( __( 'Edit %s', $plugin_name ), $singular ),
                    'new_item' 				=> sprintf( __( 'New %s', $plugin_name ), $singular ),
                    'view' 					=> sprintf( __( 'View %s', $plugin_name ), $singular ),
                    'view_item' 			=> sprintf( __( 'View %s', $plugin_name ), $singular ),
                    'search_items' 			=> sprintf( __( 'Search %s', $plugin_name ), $plural ),
                    'not_found' 			=> sprintf( __( 'No %s found', $plugin_name ), $singular ),
                    'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', $plugin_name ), $singular ),
                    'parent' 				=> sprintf( __( 'Parent %s', $plugin_name ), $singular ),
                ),
                'description' => sprintf( __( 'This is where you can create and manage %s.', $plugin_name ), $singular ),
                'public' 				=> false,
                'show_ui' 				=> true,
                'capability_type' 		=> 'post',
                'map_meta_cap'          => true,
                'publicly_queryable' 	=> false,
                'exclude_from_search' 	=> true,
                'hierarchical' 			=> false,
                'rewrite' 				=> $rewrite,
                'query_var' 			=> true,
                'supports' 				=> array( 'title', 'custom-fields' , 'thumbnail'),
                'has_archive' 			=> $has_archive,
                'show_in_nav_menus' 	=> false,
                // 'menu_icon' => 'dashicons-calendar'
            )
        );


		// // Custom Post status

		// register_post_status( 'expired', array(
		// 	'label'                     => _x( 'Expired', 'post status', $plugin_name ),
		// 	'public'                    => true,
		// 	'exclude_from_search'       => true,
		// 	'show_in_admin_all_list'    => true,
		// 	'show_in_admin_status_list' => true,
		// 	'label_count'               => _n_noop( 'Expired <span class="count">(%s)</span>', 'Expired <span class="count">(%s)</span>', $plugin_name )
		// ) );

		// register_post_status( 'preview', array(
		// 	'public'                    => true,
		// 	'exclude_from_search'       => true,
		// 	'show_in_admin_all_list'    => true,
		// 	'show_in_admin_status_list' => true,
		// 	'label_count'               => _n_noop( 'Preview <span class="count">(%s)</span>', 'Preview <span class="count">(%s)</span>', $plugin_name )
        // ) );

    }

	public function add_metaboxes() {
		add_meta_box(
			'data_metabox', // metabox ID, it also will be the HTML id attribute
			__('Settings', $this->plugin_name), // title
			array($this, 'data_ui_func'), // this is a callback function, which will print HTML of our metabox
			'c3_chart', // post type or post types in array
			'normal', // position on the screen where metabox should be displayed (normal, side, advanced)
			'high' // priority over another metaboxes on this page (default, low, high, core)
		);
	}

	public function data_ui_func( $post ) {
        $post_id = $post->ID;
        wp_nonce_field( basename( __FILE__ ), 'data_ui_func_nonce' );
        $_c3d_data_x = get_post_meta($post_id, '_c3d_data_x', true);
        $_c3d_data_columns =   get_post_meta($post->ID, '_c3d_data_columns', true);
        $_c3d_grid_x_lines =   get_post_meta($post->ID, '_c3d_grid_x_lines', true);
        $_c3d_grid_y_lines =   get_post_meta($post->ID, '_c3d_grid_y_lines', true);
        $_c3d_axis_codes = get_post_meta($post_id, '_c3d_axis_codes', true);

        $html = '';
        $html .= '<h3>'.__('Key of X:', $this->plugin_name).'</h3>';
        $html .= '<input type="text" name="_c3d_data_x" value="'.$_c3d_data_x.'"> <br>';

        $html .= '<h3>'.__('Columns:', $this->plugin_name).'</h3>';
        $html .= '   <div class="data_columns_wrap">';
        $html .= '       <div><a class="add_data_columns_button button-secondary">＋</a></div>';
        if(isset($_c3d_data_columns) && is_array($_c3d_data_columns)) {
            // $i = 1;
            $output = '';
            foreach($_c3d_data_columns as $data_col){
                $output = '<div><textarea name="_c3d_data_columns[]" style="width:90%">'.$data_col.'</textarea> <a href="javascript:void(0);" class="remove_field button-secondary">－</a></div>';
                $html .= $output;
                // $i++;
            }
        }
        $html .= '    </div>'; // data_columns_wrap

        $html .= '<h3>'.__('Grid X lines', $this->plugin_name).'</h3>';
        $html .= '   <div class="grid_x_lines_wrap">';
        $html .= '       <div><a class="add_grid_x_lines_button button-secondary">＋</a></div>';
        if(isset($_c3d_grid_x_lines) && is_array($_c3d_grid_x_lines)) {
            $output = '';
            foreach($_c3d_grid_x_lines as $xlines){
                $output = '<div><input type="text" name="_c3d_grid_x_lines[]" style="width:90%" value="'.$xlines.'" /> <a href="javascript:void(0);" class="remove_field button-secondary">－</a></div>';
                $html .= $output;
            }
        }
        $html .= '    </div>'; // grid_x_lines_wrap

        $html .= '<h3>'.__('Grid Y lines', $this->plugin_name).'</h3>';
        $html .= '   <div class="grid_y_lines_wrap">';
        $html .= '       <div><a class="add_grid_y_lines_button button-secondary">＋</a></div>';
        if(isset($_c3d_grid_y_lines) && is_array($_c3d_grid_y_lines)) {
            $output = '';
            foreach($_c3d_grid_y_lines as $ylines){
                $output = '<div><input type="text" name="_c3d_grid_y_lines[]" style="width:90%" value="'.$ylines.'" /> <a href="javascript:void(0);" class="remove_field button-secondary">－</a></div>';
                $html .= $output;
            }
        }
        $html .= '    </div>'; // grid_y_lines_wrap

        $html .= '<h3>'.__('Axis codes', $this->plugin_name).'</h3>';
        $html .= '<textarea name="_c3d_axis_codes" rows="8" style="width:100%;">'.$_c3d_axis_codes.'</textarea>';

        $html .= '<h3>'.__('Preview', $this->plugin_name).'</h3>';
        if ($post->post_status == 'publish' ) {
            $html .= '<div>';
            $html .= do_shortcode('[muc3 chart='.$post_id.']');
            $html .= '</div>'; 
        }
        $html .= '<h3>'.__('Shortcode', $this->plugin_name).'</h3>';
        $html .= '<div><strong>[muc3 chart='.$post_id.']</strong></div>';
		echo $html;
    }
    
    public function save_post_meta( $post_id, $post  ) {
        /* 
        * Security checks
        */
        if ( !isset( $_POST['data_ui_func_nonce'] ) 
        || !wp_verify_nonce( $_POST['data_ui_func_nonce'], basename( __FILE__ ) ) )
            return $post_id;
        /* 
        * Check current user permissions
        */
        $post_type = get_post_type_object( $post->post_type );
        if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
            return $post_id;
        /*
        * Do not save the data if autosave
        */
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
            return $post_id;
    
        if ($post->post_type == 'c3_chart') { // define your own post type here
            update_post_meta($post_id, '_c3d_data_x', sanitize_text_field( $_POST['_c3d_data_x'] ) );

            if(isset($_POST['_c3d_data_columns'])) {
                update_post_meta( $post_id, '_c3d_data_columns', $_POST['_c3d_data_columns'] );
            }
            if(isset($_POST['_c3d_grid_x_lines'])) {
                update_post_meta( $post_id, '_c3d_grid_x_lines', $_POST['_c3d_grid_x_lines'] );
            }
            if(isset($_POST['_c3d_grid_y_lines'])) {
                update_post_meta( $post_id, '_c3d_grid_y_lines', $_POST['_c3d_grid_y_lines'] );
            }
            update_post_meta($post_id, '_c3d_axis_codes', sanitize_text_field( $_POST['_c3d_axis_codes'] ) );
        }
        return $post_id;
    }
}
