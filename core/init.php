<?php

/**
* Fires the theme : constants definition, core classes loading
*
*
* @package      Customizr
* @subpackage   classes
* @since        3.0
* @author       Nicolas GUILLAUME <nicolas@presscustomizr.com>
* @copyright    Copyright (c) 2013-2015, Nicolas GUILLAUME
* @link         http://presscustomizr.com/customizr
* @license      http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/
if ( ! class_exists( 'TC___' ) ) :

  final class TC___ {
    public static $instance;//@todo make private in the future
    public $tc_core;
    public $is_customizing;
    public static $theme_name;
    public static $tc_option_group;

    public $views;//object, stores the views
    public $controllers;//object, stores the controllers

    //stack
    public $current_model = array();

    public static function tc_instance() {
      if ( ! isset( self::$instance ) && ! ( self::$instance instanceof TC___ ) ) {
        self::$instance = new TC___();
        self::$instance -> tc_setup_constants();
        self::$instance -> tc_setup_loading();
        self::$instance -> tc_load();
        self::$instance -> collection = new TC_Collection();
        self::$instance -> controllers = new TC_Controllers();
        self::$instance -> helpers = new TC_Helpers();

        //register the model's map in front
        if ( ! is_admin() )
          add_action('wp'         , array(self::$instance, 'tc_register_model_map') );
      }
      return self::$instance;
    }



    private function tc_setup_constants() {
      /* GETS INFORMATIONS FROM STYLE.CSS */
      // get themedata version wp 3.4+
      if( function_exists( 'wp_get_theme' ) ) {
        //get WP_Theme object of customizr
        $tc_theme                     = wp_get_theme();

        //Get infos from parent theme if using a child theme
        $tc_theme = $tc_theme -> parent() ? $tc_theme -> parent() : $tc_theme;

        $tc_base_data['prefix']       = $tc_base_data['title'] = $tc_theme -> name;
        $tc_base_data['version']      = $tc_theme -> version;
        $tc_base_data['authoruri']    = $tc_theme -> {'Author URI'};
      }

      // get themedata for lower versions (get_stylesheet_directory() points to the current theme root, child or parent)
      else {
           $tc_base_data                = call_user_func('get_' .'theme_data', get_stylesheet_directory().'/style.css' );
           $tc_base_data['prefix']      = $tc_base_data['title'];
      }

      self::$theme_name                 = sanitize_file_name( strtolower($tc_base_data['title']) );

      //CUSTOMIZR_VER is the Version
      if( ! defined( 'CUSTOMIZR_VER' ) )      define( 'CUSTOMIZR_VER' , $tc_base_data['version'] );
      //TC_BASE is the root server path of the parent theme
      if( ! defined( 'TC_BASE' ) )            define( 'TC_BASE' , get_template_directory().'/' );
      //TC_FRAMEWORK_PREFIX is the relative path where the framerk is located
      if( ! defined( 'TC_FRAMEWORK_PREFIX' ) ) define( 'TC_FRAMEWORK_PREFIX' , 'core/front/' );
      //TC_ASSETS_PREFIX is the relative path where the assets are located
      if( ! defined( 'TC_ASSETS_PREFIX' ) )   define( 'TC_ASSETS_PREFIX' , 'assets/' );
      //TC_BASE_CHILD is the root server path of the child theme
      if( ! defined( 'TC_BASE_CHILD' ) )      define( 'TC_BASE_CHILD' , get_stylesheet_directory().'/' );
      //TC_BASE_URL http url of the loaded parent theme
      if( ! defined( 'TC_BASE_URL' ) )        define( 'TC_BASE_URL' , get_template_directory_uri() . '/' );
      //TC_BASE_URL_CHILD http url of the loaded child theme
      if( ! defined( 'TC_BASE_URL_CHILD' ) )  define( 'TC_BASE_URL_CHILD' , get_stylesheet_directory_uri() . '/' );
      //THEMENAME contains the Name of the currently loaded theme
      if( ! defined( 'THEMENAME' ) )          define( 'THEMENAME' , $tc_base_data['title'] );
      //TC_WEBSITE is the home website of Customizr
      if( ! defined( 'TC_WEBSITE' ) )         define( 'TC_WEBSITE' , $tc_base_data['authoruri'] );

    }//setup_contants()

    private function tc_setup_loading() {
      //this is the structure of the Customizr code : groups => ('path' , 'class_suffix')
      $this -> tc_core = apply_filters( 'tc_core',
        array(
            'fire'      =>   array(
              array('core'       , 'init'),//defines default values (layout, socials, default slider...) and theme supports (after_setup_theme)
              array('core'       , 'plugins_compat'),//handles various plugins compatibilty (Jetpack, Bbpress, Qtranslate, Woocommerce, The Event Calendar ...)
              array('core/utils' , 'utils_settings_map'),//customizer setting map
              array('core/utils' , 'utils'),//helpers used everywhere
              array('core/utils' , 'utils_thumbnails'),//thumbnails helpers used almost everywhere
              array('core/utils' , 'utils_query'),//query helpers used almost everywhere
              array('core/utils' , 'utils_texts'),//texts (titles, text trimimng) helpers used almost everywhere
              array('core'       , 'resources'),//loads front stylesheets (skins) and javascripts
              array('core'       , 'widgets'),//widget factory
              array('core/back'  , 'admin_init'),//loads admin style and javascript ressources. Handles various pure admin actions (no customizer actions)
              array('core/back'  , 'admin_page')//creates the welcome/help panel including changelog and system config
            ),
            'admin'     => array(
              array('core/back' , 'customize'),//loads customizer actions and resources
              array('core/back' , 'meta_boxes')//loads the meta boxes for pages, posts and attachment : slider and layout settings
            ),
            'header'    =>   array(
              array('core/front/utils', 'nav_walker')
            ),
            'content'   =>   array(
              array('core/front/utils', 'gallery')
            ),
            'addons'    => apply_filters( 'tc_addons_classes' , array() )
        )
      );
      //check the context
      if ( $this -> tc_is_pro() )
        require_once( sprintf( '%score/init-pro.php' , TC_BASE ) );

      self::$tc_option_group = 'tc_theme_options';

      //set files to load according to the context : admin / front / customize
      add_filter( 'tc_get_files_to_load' , array( $this , 'tc_set_files_to_load' ) );
    }


    /**
    * Class instantiation using a singleton factory :
    * Can be called to instantiate a specific class or group of classes
    * @param  array(). Ex : array ('admin' => array( array( 'core/back' , 'meta_boxes') ) )
    * @return  instances array()
    *
    * Thanks to Ben Doherty (https://github.com/bendoh) for the great programming approach
    *
    * @since Customizr 3.0
    */
    function tc_load( $_to_load = array(), $_no_filter = false ) {
      //do we apply a filter ? optional boolean can force no filter
      $_to_load = $_no_filter ? $_to_load : apply_filters( 'tc_get_files_to_load' , $_to_load );
      if ( empty($_to_load) )
        return;

      foreach ( $_to_load as $group => $files )
        foreach ($files as $path_suffix ) {
          $this -> tc_require_once ( $path_suffix[0] . '/class-' . $group . '-' .$path_suffix[1] . '.php');
          $classname = 'TC_' . $path_suffix[1];
          if ( in_array( $classname, apply_filters( 'tc_dont_instantiate_in_init', array( 'TC_nav_walker') ) ) )
            continue;
          //instantiates
          $instances = class_exists($classname)  ? new $classname : '';
        }

      //load the new framework classes
      $this -> tc_fw_require_once( 'class-model.php' );
      $this -> tc_fw_require_once( 'class-collection.php' );
      $this -> tc_fw_require_once( 'class-view.php' );
      $this -> tc_fw_require_once( 'class-controllers.php' );
      $this -> tc_fw_require_once( 'class-helpers.php' );
    }



    //hook : wp
    function tc_register_model_map( $_map = array() ) {
      $_to_register =  ( empty( $_map ) || ! is_array($_map) ) ? $this -> tc_get_model_map() : $_map;

      foreach ( $_to_register as $model ) {
        CZR() -> collection -> tc_register( $model);
      }

    }


    //returns an array of models describing the theme's views
    private function tc_get_model_map() {
      return apply_filters(
        'tc_model_map',
        array(
          /*********************************************
          * ROOT HTML STRUCTURE
          *********************************************/
          array(
            'hook' => 'wp_head' ,
            'template' => 'header/favicon',
          ),


          /*********************************************
          * HEADER
          *********************************************/
          array(
            'model_class'    => 'header',
            'id'             => 'header'
          ),

          /*********************************************
          * SLIDER
          *********************************************/
          array(
            'model_class' => 'modules/slider/slider',
            'id'          => 'main_slider'
          ),
          //slider of posts
          array(
            'id'          => 'main_posts_slider',
            'model_class' => array( 'parent' => 'modules/slider/slider', 'name' => 'modules/slider/slider_of_posts' ),
          ),
          /** end slider **/


          /*********************************************
          * Featured Pages
          *********************************************/
          /* contains the featured page item registration */
          array(
            'id'          => 'featured_pages',
            'model_class' => 'modules/featured-pages/featured_pages',
          ),
          /** end featured pages **/

          /*********************************************
          * CONTENT
          *********************************************/
          array(
            'id'             => 'content',
            'model_class'    => 'content',
          ),

          /*********************************************
          * FOOTER
          *********************************************/
          array(
            'id'           => 'footer',
            'model_class'  => 'footer',
          ),
        )
      );
    }




    /***************************
    * HELPERS
    ****************************/
    /**
    * Check the context and return the modified array of class files to load and instantiate
    * hook : tc_get_files_to_load
    * @return boolean
    *
    * @since  Customizr 3.3+
    */
    function tc_set_files_to_load( $_to_load ) {
      $_to_load = empty($_to_load) ? $this -> tc_core : $_to_load;
      //Not customizing
      //1) IS NOT CUSTOMIZING : tc_is_customize_left_panel() || tc_is_customize_preview_frame() || tc_doing_customizer_ajax()
      //---1.1) IS ADMIN
      //---1.2) IS NOT ADMIN
      //2) IS CUSTOMIZING
      //---2.1) IS LEFT PANEL => customizer controls
      //---2.2) IS RIGHT PANEL => preview
      if ( ! $this -> tc_is_customizing() )
        {
          if ( is_admin() )
            $_to_load = $this -> tc_unset_core_classes( $_to_load, array( 'header' , 'content' , 'footer' ), array( 'admin|core/back|customize' ) );
          else
            //Skips all admin classes
            $_to_load = $this -> tc_unset_core_classes( $_to_load, array( 'admin' ), array( 'fire|core/back|admin_init', 'fire|core/back|admin_page') );
        }
      //Customizing
      else
        {
          //left panel => skip all front end classes
          if ( $this -> tc_is_customize_left_panel() ) {
            $_to_load = $this -> tc_unset_core_classes(
              $_to_load,
              array( 'header' , 'content' , 'footer' ),
              array( 'fire|core|resources' , 'fire|core/back|admin_page' , 'admin|core/back|meta_boxes' )
            );
          }
          if ( $this -> tc_is_customize_preview_frame() ) {
            $_to_load = $this -> tc_unset_core_classes(
              $_to_load,
              array(),
              array( 'fire|core/back|admin_init', 'fire|core/back|admin_page' , 'admin|core/back|meta_boxes' )
            );
          }
        }
      return $_to_load;
    }



    /**
    * Helper
    * Alters the original classes tree
    * @param $_groups array() list the group of classes to unset like header, content, admin
    * @param $_files array() list the single file to unset.
    * Specific syntax for single files: ex in fire|core/back|admin_page
    * => fire is the group, core/back is the path, admin_page is the file suffix.
    * => will unset core/back/class-fire-admin_page.php
    *
    * @return array() describing the files to load
    *
    * @since  Customizr 3.0.11
    */
    public function tc_unset_core_classes( $_tree, $_groups = array(), $_files = array() ) {
      if ( empty($_tree) )
        return array();
      if ( ! empty($_groups) ) {
        foreach ( $_groups as $_group_to_remove ) {
          unset($_tree[$_group_to_remove]);
        }
      }
      if ( ! empty($_files) ) {
        foreach ( $_files as $_concat ) {
          //$_concat looks like : fire|core|resources
          $_exploded = explode( '|', $_concat );
          //each single file entry must be a string like 'admin|core/back|customize'
          //=> when exploded by |, the array size must be 3 entries
          if ( count($_exploded) < 3 )
            continue;

          $gname = $_exploded[0];
          $_file_to_remove = $_exploded[2];
          if ( ! isset($_tree[$gname] ) )
            continue;
          foreach ( $_tree[$gname] as $_key => $path_suffix ) {
            if ( false !== strpos($path_suffix[1], $_file_to_remove ) )
              unset($_tree[$gname][$_key]);
          }//end foreach
        }//end foreach
      }//end if
      return $_tree;
    }//end of fn

    //called when requiring a file will - always give the precedence to the child-theme file if it exists
    //then to a custom root (plugins)
    //then to the theme root
    function tc_get_theme_file( $path_suffix ) {
      $path_prefixes = apply_filters( 'tc_include_paths'     , array( '' ) );
      $roots         = apply_filters( 'tc_include_roots_path', array( TC_BASE_CHILD, TC_BASE ) );

      foreach ( $roots as $root )
        foreach ( $path_prefixes as $path_prefix )
          if ( file_exists( $filename = $root . $path_prefix . $path_suffix ) )
            return $filename;

      return false;
    }

    //called when requiring a file url - will always give the precedence to the child-theme file if it exists
    //then to a custom root (plugins)
    //then to the theme root
    function tc_get_theme_file_url( $url_suffix ) {
      $url_prefixes   = apply_filters( 'tc_include_paths'     , array( '' ) );
      $roots          = apply_filters( 'tc_include_roots_path', array( TC_BASE_CHILD, TC_BASE ) );
      $roots_urls     = apply_filters( 'tc_include_roots_url' , array( TC_BASE_URL_CHILD, TC_BASE_URL ) );

      $combined_roots = array_combine( $roots, $roots_urls );

      foreach ( $roots as $root )
        foreach ( $url_prefixes as $url_prefix ) {
          if ( file_exists( $filename = $root . $url_prefix . $url_suffix ) )
            return array_key_exists( $root, $combined_roots) ? $combined_roots[ $root ] . $url_prefix . $url_suffix : false;
        }
      return false;
    }

    //requires a file only if exists
    function tc_require_once( $path_suffix ) {
      if ( false !== $filename = $this -> tc_get_theme_file( $path_suffix ) ) {
        require_once( $filename );
        return true;
      }
      return false;
    }

    //requires a framework file only if exists
    function tc_fw_require_once( $path_suffix ) {
      return $this -> tc_require_once( TC_FRAMEWORK_PREFIX . $path_suffix );
    }


    /*
    * Stores the current model in the class current_model stack
    * called by the View class before requiring the view template
    * @param $model
    */
    function tc_set_current_model( $model ) {
      $this -> current_model[ $model -> id ] = &$model;
    }


    /*
    * Pops the current model from the current_model stack
    * called by the View class after the view template has been required/rendered
    */
    function tc_reset_current_model() {
      array_pop( $this -> current_model );
    }


    /*
    * An handly function to get a current model property
    * @param $property (string), the property to get
    * @param $args (array) - optional, an ordered list of params to pass to the current model property getter (if defined)
    */
    function tc_get( $property, $args = array() ) {
      $current_model = end( $this -> current_model );
      return is_object($current_model) ? $current_model -> tc_get_property( $property, $args ) : false;
    }

    /*
    * An handly function to print a current model property (wrapper for tc_get)
    * @param $property (string), the property to get
    * @param $args (array) - optional, an ordered list of params to pass to the current model property getter (if defined)
    */
    function tc_echo( $property, $args = array() ) {
      echo tc_get( $property, $args );
    }

    /**
    * Are we in a customization context ? => ||
    * 1) Left panel ?
    * 2) Preview panel ?
    * 3) Ajax action from customizer ?
    * @return  bool
    * @since  3.2.9
    */
    function tc_is_customizing() {
      if ( ! isset( $this -> is_customizing ) )
        //checks if is customizing : two contexts, admin and front (preview frame)
        $this -> is_customizing = in_array( 1, array(
          $this -> tc_is_customize_left_panel(),
          $this -> tc_is_customize_preview_frame(),
         $this -> tc_doing_customizer_ajax()
        ) );
      return $this -> is_customizing;
    }


    /**
    * Is the customizer left panel being displayed ?
    * @return  boolean
    * @since  3.3+
    */
    function tc_is_customize_left_panel() {
      global $pagenow;
      return is_admin() && isset( $pagenow ) && 'customize.php' == $pagenow;
    }


    /**
    * Is the customizer preview panel being displayed ?
    * @return  boolean
    * @since  3.3+
    */
    function tc_is_customize_preview_frame() {
      return ! is_admin() && isset($_REQUEST['wp_customize']);
    }


    /**
    * Always include wp_customize or customized in the custom ajax action triggered from the customizer
    * => it will be detected here on server side
    * typical example : the donate button
    *
    * @return boolean
    * @since  3.3.2
    */
    function tc_doing_customizer_ajax() {
      $_is_ajaxing_from_customizer = isset( $_POST['customized'] ) || isset( $_POST['wp_customize'] );
      return $_is_ajaxing_from_customizer && ( defined( 'DOING_AJAX' ) && DOING_AJAX );
    }


    /**
    * Checks if we use a child theme. Uses a deprecated WP functions (get _theme_data) for versions <3.4
    * @return boolean
    *
    * @since  Customizr 3.0.11
    */
    function tc_is_child() {
      // get themedata version wp 3.4+
      if ( function_exists( 'wp_get_theme' ) ) {
        //get WP_Theme object of customizr
        $tc_theme       = wp_get_theme();
        //define a boolean if using a child theme
        return $tc_theme -> parent() ? true : false;
      }
      else {
        $tc_theme       = call_user_func('get_' .'theme_data', get_stylesheet_directory().'/style.css' );
        return ! empty($tc_theme['Template']) ? true : false;
      }
    }

    /**
    * @return  boolean
    * @since  3.4+
    */
    static function tc_is_pro() {
      return file_exists( sprintf( '%score/init-pro.php' , TC_BASE ) ) && "customizr-pro" == self::$theme_name;
    }

  }//end of class
endif;//endif;
