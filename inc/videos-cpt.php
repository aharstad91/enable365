<?php
/**
 * Videos Custom Post Type and Taxonomy
 * 
 * Provides VideoObject schema markup for improved Google Page Score
 * Originally from enable365-videos plugin - now integrated in theme for Git deployment
 * 
 * @package Enable365
 */

if (!defined('ABSPATH')) {
    exit;
}

class Enable365_Videos_CPT {

    /**
     * Initialize
     */
    public function __construct() {
        add_action('init', array($this, 'register_videos_cpt'));
        add_action('init', array($this, 'register_video_app_taxonomy'));
        add_action('add_meta_boxes', array($this, 'add_video_meta_boxes'));
        add_action('save_post_videos', array($this, 'save_video_meta'));
        add_action('wp_head', array($this, 'output_video_schema'));
        
        // Setup default terms on theme switch
        add_action('after_switch_theme', array($this, 'setup_defaults'));
    }

    /**
     * Setup defaults (run on theme activation)
     */
    public function setup_defaults() {
        $this->register_videos_cpt();
        $this->register_video_app_taxonomy();
        $this->insert_default_app_terms();
        flush_rewrite_rules();
    }

    /**
     * Register Videos Custom Post Type
     */
    public function register_videos_cpt() {
        $labels = array(
            'name'                  => 'Videos',
            'singular_name'         => 'Video',
            'menu_name'             => 'Videos',
            'add_new'               => 'Legg til ny',
            'add_new_item'          => 'Legg til ny video',
            'edit_item'             => 'Rediger video',
            'new_item'              => 'Ny video',
            'view_item'             => 'Vis video',
            'search_items'          => 'Søk i videoer',
            'not_found'             => 'Ingen videoer funnet',
            'not_found_in_trash'    => 'Ingen videoer i papirkurven',
            'all_items'             => 'Alle videoer',
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array('slug' => 'videos', 'with_front' => false),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => 99,
            'menu_icon'           => 'dashicons-video-alt3',
            'show_in_rest'        => true,
            'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
        );

        register_post_type('videos', $args);
    }

    /**
     * Register Video App Taxonomy
     */
    public function register_video_app_taxonomy() {
        $labels = array(
            'name'              => 'App Filter',
            'singular_name'     => 'App',
            'search_items'      => 'Søk i apper',
            'all_items'         => 'Alle apper',
            'edit_item'         => 'Rediger app',
            'update_item'       => 'Oppdater app',
            'add_new_item'      => 'Legg til ny app',
            'new_item_name'     => 'Nytt app-navn',
            'menu_name'         => 'App Filter',
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'video-filter', 'with_front' => false),
            'show_in_rest'      => true,
        );

        register_taxonomy('video_app', array('videos'), $args);
    }

    /**
     * Insert default app terms
     */
    public function insert_default_app_terms() {
        $apps = array(
            'PlanIt'    => 'Visualize and plan your year in Teams',
            'Agenda'    => 'Streamline meetings with clear agendas',
            'Guidance'  => 'Step-by-step guidance to ensure quality',
            'Presence'  => 'Know where everyone is, when it matters',
            'Templates' => 'Ready-made templates for your needs',
        );

        foreach ($apps as $name => $description) {
            if (!term_exists($name, 'video_app')) {
                wp_insert_term($name, 'video_app', array(
                    'description' => $description,
                    'slug'        => 'app-' . sanitize_title($name),
                ));
            }
        }
    }

    /**
     * Add meta boxes for video fields
     */
    public function add_video_meta_boxes() {
        add_meta_box(
            'video_details',
            'Video Detaljer & Schema Markup',
            array($this, 'render_video_meta_box'),
            'videos',
            'normal',
            'high'
        );
    }

    /**
     * Render video meta box
     */
    public function render_video_meta_box($post) {
        wp_nonce_field('save_video_meta', 'video_meta_nonce');

        $youtube_url = get_post_meta($post->ID, '_video_youtube_url', true);
        $video_name = get_post_meta($post->ID, '_video_name', true);
        $video_description = get_post_meta($post->ID, '_video_description', true);
        $video_thumbnail_url = get_post_meta($post->ID, '_video_thumbnail_url', true);
        $video_upload_date = get_post_meta($post->ID, '_video_upload_date', true);
        $video_duration = get_post_meta($post->ID, '_video_duration', true);

        ?>
        <style>
            .video-meta-field { margin-bottom: 20px; }
            .video-meta-field label { display: block; font-weight: 600; margin-bottom: 5px; }
            .video-meta-field input[type="text"],
            .video-meta-field input[type="url"],
            .video-meta-field input[type="date"],
            .video-meta-field textarea { width: 100%; padding: 8px; }
            .video-meta-field .description { color: #666; font-style: italic; margin-top: 5px; }
            .video-meta-field.required label:after { content: " *"; color: #dc3232; }
            .video-preview { margin-top: 20px; padding: 20px; background: #f9f9f9; border: 1px solid #ddd; }
            .video-preview iframe { max-width: 100%; }
        </style>

        <div class="video-meta-fields">
            <h3>YouTube Embed</h3>
            
            <div class="video-meta-field required">
                <label for="video_youtube_url">YouTube Video URL</label>
                <input type="url" id="video_youtube_url" name="video_youtube_url" value="<?php echo esc_attr($youtube_url); ?>" placeholder="https://www.youtube.com/watch?v=XXXXXXXXX">
                <p class="description">Lim inn full YouTube URL. Embed-kode genereres automatisk.</p>
            </div>

            <?php if ($youtube_url): ?>
            <div class="video-preview">
                <h4>Forhåndsvisning:</h4>
                <?php echo $this->get_youtube_embed($youtube_url, 560, 315); ?>
            </div>
            <?php endif; ?>

            <hr style="margin: 30px 0;">
            
            <h3>VideoObject Schema Markup (for Google)</h3>
            <p style="color: #666;">Disse feltene brukes til å generere strukturert data for bedre synlighet i Google-søk.</p>

            <div class="video-meta-field required">
                <label for="video_name">Video Tittel (name)</label>
                <input type="text" id="video_name" name="video_name" value="<?php echo esc_attr($video_name); ?>">
            </div>

            <div class="video-meta-field required">
                <label for="video_description">Video Beskrivelse (description)</label>
                <textarea id="video_description" name="video_description" rows="4"><?php echo esc_textarea($video_description); ?></textarea>
            </div>

            <div class="video-meta-field required">
                <label for="video_thumbnail_url">Thumbnail URL (thumbnailUrl)</label>
                <input type="url" id="video_thumbnail_url" name="video_thumbnail_url" value="<?php echo esc_attr($video_thumbnail_url); ?>" placeholder="https://img.youtube.com/vi/VIDEO_ID/maxresdefault.jpg">
            </div>

            <div class="video-meta-field">
                <label for="video_upload_date">Publiseringsdato (uploadDate)</label>
                <input type="date" id="video_upload_date" name="video_upload_date" value="<?php echo esc_attr($video_upload_date); ?>">
            </div>

            <div class="video-meta-field">
                <label for="video_duration">Varighet - ISO 8601 (duration)</label>
                <input type="text" id="video_duration" name="video_duration" value="<?php echo esc_attr($video_duration); ?>" placeholder="PT5M30S">
            </div>
        </div>
        <?php
    }

    /**
     * Save video meta data
     */
    public function save_video_meta($post_id) {
        if (!isset($_POST['video_meta_nonce']) || !wp_verify_nonce($_POST['video_meta_nonce'], 'save_video_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = array(
            'video_youtube_url',
            'video_name',
            'video_description',
            'video_thumbnail_url',
            'video_upload_date',
            'video_duration'
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
    }

    /**
     * Extract YouTube video ID from URL
     */
    private function get_youtube_id($url) {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
        preg_match($pattern, $url, $matches);
        return isset($matches[1]) ? $matches[1] : '';
    }

    /**
     * Generate YouTube embed HTML
     */
    public function get_youtube_embed($url, $width = 560, $height = 315) {
        $video_id = $this->get_youtube_id($url);
        if (!$video_id) {
            return '';
        }

        return sprintf(
            '<iframe width="%d" height="%d" src="https://www.youtube.com/embed/%s" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>',
            intval($width),
            intval($height),
            esc_attr($video_id)
        );
    }

    /**
     * Output VideoObject schema markup in head
     */
    public function output_video_schema() {
        if (!is_singular('videos')) {
            return;
        }

        global $post;

        $youtube_url = get_post_meta($post->ID, '_video_youtube_url', true);
        $video_name = get_post_meta($post->ID, '_video_name', true);
        $video_description = get_post_meta($post->ID, '_video_description', true);
        $video_thumbnail_url = get_post_meta($post->ID, '_video_thumbnail_url', true);
        $video_upload_date = get_post_meta($post->ID, '_video_upload_date', true);
        $video_duration = get_post_meta($post->ID, '_video_duration', true);

        if (empty($video_name) || empty($video_description) || empty($video_thumbnail_url)) {
            return;
        }

        // Format uploadDate as ISO 8601 with timezone (required by Google)
        if (empty($video_upload_date)) {
            // Use post publish date with time
            $upload_datetime = get_the_date('c', $post); // 'c' = ISO 8601 format
        } else {
            // Convert stored date (Y-m-d) to ISO 8601 with timezone
            $date_obj = DateTime::createFromFormat('Y-m-d', $video_upload_date, wp_timezone());
            if ($date_obj) {
                $date_obj->setTime(12, 0, 0); // Set noon to avoid timezone edge cases
                $upload_datetime = $date_obj->format('c');
            } else {
                // Fallback to post date if parsing fails
                $upload_datetime = get_the_date('c', $post);
            }
        }

        $video_id = $this->get_youtube_id($youtube_url);

        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'VideoObject',
            'name' => $video_name,
            'description' => $video_description,
            'thumbnailUrl' => $video_thumbnail_url,
            'uploadDate' => $upload_datetime,
        );

        if ($video_id) {
            $schema['embedUrl'] = 'https://www.youtube.com/embed/' . $video_id;
            $schema['contentUrl'] = 'https://www.youtube.com/watch?v=' . $video_id;
        }

        if (!empty($video_duration)) {
            $schema['duration'] = $video_duration;
        }

        echo "\n<!-- Enable 365 VideoObject Schema Markup -->\n";
        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        echo "\n</script>\n";
    }

    /**
     * Get responsive YouTube embed for frontend
     */
    public static function get_responsive_youtube_embed($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $youtube_url = get_post_meta($post_id, '_video_youtube_url', true);
        if (!$youtube_url) {
            return '';
        }

        $instance = new self();
        $video_id = $instance->get_youtube_id($youtube_url);
        
        if (!$video_id) {
            return '';
        }

        return sprintf(
            '<div class="video-embed-container" style="position: relative; padding-bottom: 56.25%%; height: 0; overflow: hidden; max-width: 100%%;">
                <iframe style="position: absolute; top: 0; left: 0; width: 100%%; height: 100%%;" src="https://www.youtube.com/embed/%s" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>',
            esc_attr($video_id)
        );
    }
}

// Initialize
new Enable365_Videos_CPT();

// Helper function for templates
if (!function_exists('enable365_video_embed')) {
    function enable365_video_embed($post_id = null) {
        return Enable365_Videos_CPT::get_responsive_youtube_embed($post_id);
    }
}
