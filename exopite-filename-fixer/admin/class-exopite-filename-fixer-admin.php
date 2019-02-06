<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.joeszalai.org
 * @since      1.0.0
 *
 * @package    Exopite_Filename_Fixer
 * @subpackage Exopite_Filename_Fixer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Exopite_Filename_Fixer
 * @subpackage Exopite_Filename_Fixer/admin
 * @author     Joe Szalai <joe@joeszalai.org>
 */
class Exopite_Filename_Fixer_Admin {

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

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Exopite_Filename_Fixer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Exopite_Filename_Fixer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/exopite-filename-fixer-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Exopite_Filename_Fixer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Exopite_Filename_Fixer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/exopite-filename-fixer-admin.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Automatically Set the WordPress Image Title, Alt-Text & Other Meta
	 *
	 * - Convert multiple spaces, dashes and underscores to single space,
	 * - capitalize first character,
	 * - capitalize character after --,
	 * - set image title, caption, alt text and description.
     *
     * @link https://brutalbusiness.com/automatically-set-the-wordpress-image-title-alt-text-other-meta/
     */
    public function auto_image_details( $post_ID ) {

        // Check if uploaded file is an image, else do nothing

        if ( wp_attachment_is_image( $post_ID ) ) {

            // Get image filename
            // @link https://wordpress.stackexchange.com/questions/30313/change-attachment-filename
            $my_image_title = get_post( $post_ID )->post_title;

            // https://stackoverflow.com/questions/5546120/php-capitalize-after-dash/5546534#5546534
            $my_image_title = implode( '-', array_map( 'ucfirst', explode( '--', $my_image_title ) ) );

			// Remove multiple -
			$my_image_title = preg_replace( '/-+/', '-', $my_image_title );

            // Sanitize the title:  remove hyphens, underscores & extra spaces:
            $my_image_title = preg_replace( '%\s*[-_\s]+\s*%', ' ',  $my_image_title );

            // Sanitize the title:  capitalize first letter of every word (other letters lower case):
            $my_image_title = ucfirst( $my_image_title );

            // Create an array with the image meta (Title, Caption, Description) to be updated
            // Note:  comment out the Excerpt/Caption or Content/Description lines if not needed
            $my_image_meta = array(
                'ID'            => $post_ID,            // Specify the image (ID) to be updated
                'post_title'    => $my_image_title,     // Set image Title to sanitized title
                'post_excerpt'  => $my_image_title,     // Set image Caption (Excerpt) to sanitized title
                'post_content'  => $my_image_title,     // Set image Description (Content) to sanitized title
            );

            // Set the image Alt-Text
            update_post_meta( $post_ID, '_wp_attachment_image_alt', $my_image_title );

            // Set the image meta (e.g. Title, Excerpt, Content)
            wp_update_post( $my_image_meta );

        }

    }

	/**
	 * FOR DEMONSTRATION PURPOSES ONLY
	 *
	 * Sanitize file name.
	 *
	 * This will work only not for Images.
	 * With this _wp_attachment_metadata on upload will not be generated by WordPress.
	 *
	 * @link https://stackoverflow.com/questions/45576997/wordpress-updating-attachment-data/45596709#45596709
	 * @link https://hotexamples.com/examples/-/-/update_attached_file/php-update_attached_file-function-examples.html
	 */
	public function manage_attachment( $post_ID ) {

		$post = get_post( $post_ID );
		$file = get_attached_file( $post_ID );
		$path = pathinfo( $file );
			//dirname   = File Path
			//basename  = Filename.Extension
			//extension = Extension
			//filename  = Filename

        /**
         * Remove special chars, sanitize_title does not this (or at least not all).
         * Replace "speacial" chars without remove accents.
         */
        $filename = $this->special_replace_chars( $filename );
        $filename = remove_accents( $filename );

		// Create new attachment name
		$filename_new = sanitize_title( $filename_new_pre );
		$file_updated = $path['dirname'] . '/' . $filename_new . "." . $path['extension'];

		// $this->auto_image_details( $post_ID );

		rename( $file, $file_updated );
		update_attached_file( $post_ID, $file_updated );

		/**
		 * Update attachment meta data
		 *
		 * @link https://codex.wordpress.org/Function_Reference/wp_generate_attachment_metadata
		 */
		$file_updated_meta = wp_generate_attachment_metadata( $post_ID, $file_updated );
		wp_update_attachment_metadata( $post_ID, $file_updated_meta );

	}

	/**
	 * FOR DEMONSTRATION PURPOSES ONLY
	 *
	 * Sanitize filename on upload. This will not check if filename is unique.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_unique_filename/
	 * @link https://core.trac.wordpress.org/browser/tags/4.9.8/src/wp-includes/functions.php#L0
	 */
	public function wp_unique_filename( $filename, $ext, $dir, $unique_filename_callback ) {

		/**
		 * Remove ext from filename before process.
		 *
		 * @link https://stackoverflow.com/questions/5573334/remove-a-part-of-a-string-but-only-when-it-is-at-the-end-of-the-string/5573439#5573439
		 */
		if ( substr( $filename, -strlen( $ext ) ) === $ext ) {
			$filename = substr( $filename, 0, strlen( $filename )-strlen( $ext ) );
		}

        /**
         * Remove special chars, sanitize_title does not this (or at least not all).
         * Replace "speacial" chars without remove accents.
         */
        $filename = $this->special_replace_chars( $filename );
        $filename = remove_accents( $filename );

		/**
		 * Sanitize filename.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/sanitize_title
		 *
		 * "Despite the name of this function, the returned value is intended to be
		 * suitable for use in a URL, not as a human-readable title."
		 */
		$filename = sanitize_title( $filename );

		$filename = wp_unique_filename( $dir, $filename );

		return $filename . $ext;

	}

	/**
	 * Fix and sanitize some special cases.
	 *
	 * @link https://github.com/salcode/fe-sanitize-title-js/issues/1
	 */
	public function special_replace_chars( $string  ) {

        $replace = array(
            'Ä' => 'ae',
            'ä' => 'ae',
            'Ö' => 'oe',
            'ö' => 'oe',
            'Ü' => 'ue',
            'ü' => 'ue',
            'ß' => 'ss',
            '€' => 'euro',
            '@' => 'at',
            '%20' => '-',
            '©' => 'copy',
            '&amp;' => 'and',
            '℠' => 'sm',
            '™' => 'tm',
            '№' => 'No',
        );

        return strtr( $string, $replace );

    }
	public function sanitize_file_name( $filename_sanitized, $filename_raw ) {

		// Get file parts.
		$pathinfo = pathinfo( $filename_raw );
		$ext = $pathinfo['extension'];
		$filename = $pathinfo['filename'];

        /**
         * Remove special chars, sanitize_title does not this (or at least not all).
         * Replace "speacial" chars without remove accents.
         */
        $filename = $this->special_replace_chars( $filename );
        $filename = remove_accents( $filename );

		/**
		 * Sanitize filename.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/sanitize_title
		 *
		 * "Despite the name of this function, the returned value is intended to be
		 * suitable for use in a URL, not as a human-readable title."
		 */
		$filename = sanitize_title( $filename );

		return $filename . '.' . $ext;

	}


}
