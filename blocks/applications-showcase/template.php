<?php
/**
 * Block Template: Applications Showcase
 */

// Check if the repeater field exists
$has_showcase_items = have_rows('showcase_repeater');

// Get the showcase title field
$showcase_title = get_field('showcase_title');

// Debug: Force field refresh in admin preview
if (is_admin() && !$showcase_title) {
    $showcase_title = get_field('showcase_title', get_the_ID());
}
?>

<div class="max-w-[1280px] mx-auto">
    <?php if ($showcase_title) : ?>
    <div class="mb-8 bg-white pt-8 pb-8">  
      <div class="wp-block-heading has-text-align-left text-[1.8rem] leading-[2.4rem] font-medium lg:text-[32px] lg:font-bold mb-4"><?php echo esc_html($showcase_title); ?></div>
    </div>
    <?php endif; ?>
    <div class="flex flex-col lg:flex-row gap-8 lg:gap-32">
      <div class="text-column w-full lg:w-1/2 space-y-24 lg:space-y-80">
        <?php 
        if ($has_showcase_items) :
            $first_image = null;
            $step_count = 0;
            $total_steps = 0;
            
            // First, count total steps
            while (have_rows('showcase_repeater')) : the_row();
                $total_steps++;
            endwhile;
            
            // Reset the loop
            reset_rows();
            
            // Loop through the repeater field
            while (have_rows('showcase_repeater')) : the_row();
                $step_count++;
                
                // Get field values
                $icon = get_sub_field('icon'); // Image array
                $title = get_sub_field('title'); // Text
                $text = get_sub_field('text'); // Text
                $button_link = get_sub_field('button_link'); // Text - URL
                $button_text = get_sub_field('button_text'); // Text

                $image = get_sub_field('image'); // Image array
                
                // Store the first image to use as default in the sticky image
                if (!$first_image && $image) {
                    $first_image = $image;
                }
                
                // Image URL for data attribute (for JavaScript to switch images)
                $image_url = isset($image['url']) ? esc_url($image['url']) : '';
                
                // Add margin-bottom to last step
                $last_step_style = ($step_count === $total_steps) ? ' style="margin-bottom: 6rem;"' : '';
        ?>
            <section class="step" data-image="<?php echo $image_url; ?>"<?php echo $last_step_style; ?>>
                <div>
                  <img decoding="async" src="<?php echo $image_url; ?>" alt="<?php echo isset($image['alt']) ? esc_attr($image['alt']) : ''; ?>" class="lg:hidden w-full rounded mb-4 object-contain">
                  <?php if ($icon) : ?>
                  <img decoding="async" width="<?php echo isset($icon['width']) ? esc_attr($icon['width']) : '339'; ?>" height="<?php echo isset($icon['height']) ? esc_attr($icon['height']) : '112'; ?>" src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo isset($icon['alt']) ? esc_attr($icon['alt']) : ''; ?>" class="mb-4 object-contain" style="width: 124px; height: auto;">
                  <?php endif; ?>
                  <div class="text-2xl lg:text-[32px] font-medium mb-5"><?php echo esc_html($title); ?></div>
                  <div class="text-xl font-medium mb-8"><?php echo esc_html($text); ?></div>

                  <?php if (get_sub_field('content')) : ?>
                  <div class="mb-8"><?php echo wp_kses_post(get_sub_field('content')); ?></div>
                  <?php endif; ?>
                  <a href="<?php echo esc_url(get_permalink() . $button_link); ?>" class="inline-block px-5 py-2 rounded-lg bg-[#AA1010] text-white text-base font-semibold shadow hover:bg-[#890404] transition mb-4"><?php echo esc_html($button_text); ?></a>
              </div>
            </section>
        <?php 
            endwhile;
        else : 
        ?>
            <section class="step">
                <div>
                  <div class="text-[32px] font-medium mb-3">Add showcase items in the block editor</div>
                  <div class="mb-8">Use the ACF fields to add applications to this showcase.</div>
                </div>
            </section>
        <?php endif; ?>
      </div>
      <div class="image-column hidden lg:block w-1/2">
        <?php if (isset($first_image) && $first_image) : ?>
        <img id="sticky-image" class="sticky top-[300px] w-full rounded object-contain" src="<?php echo esc_url($first_image['url']); ?>" alt="<?php echo isset($first_image['alt']) ? esc_attr($first_image['alt']) : ''; ?>">
        <?php else : ?>
        <img id="sticky-image" class="sticky top-[300px] w-full rounded object-contain" src="" alt="Showcase Image">
        <?php endif; ?>
      </div>
    </div>
</div>

<?php if (!is_admin()): ?>
<script>
// JavaScript to handle image switching when scrolling
document.addEventListener('DOMContentLoaded', function() {
    const steps = document.querySelectorAll('.step');
    const stickyImage = document.getElementById('sticky-image');
    
    if (steps.length === 0 || !stickyImage) return;
    
    // Set first image as default
    if (steps[0].dataset.image) {
        stickyImage.src = steps[0].dataset.image;
    }
    
    // Function to check which section is in view
    function checkVisibility() {
        for (let i = 0; i < steps.length; i++) {
            const rect = steps[i].getBoundingClientRect();
            const isInView = (
                rect.top >= 0 &&
                rect.top <= window.innerHeight / 2
            );
            
            if (isInView && steps[i].dataset.image) {
                stickyImage.src = steps[i].dataset.image;
                break;
            }
        }
    }
    
    // Listen for scroll events
    window.addEventListener('scroll', checkVisibility);
    
    // Initial check
    checkVisibility();
});
</script>
<?php endif; ?>
