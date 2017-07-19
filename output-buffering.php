<?php
/**
 * Output Buffering
 *
 * Buffers the entire WP process, capturing the final output for manipulation.
 */

ob_start();

add_action('shutdown', function() {
    $final = '';

    // We'll need to get the number of ob levels we're in, so that we can iterate over each, collecting
    // that buffer's output into the final output.
    $levels = ob_get_level();

    for ($i = 0; $i < $levels; $i++)
    {
        $final .= ob_get_clean();
    }

    // Apply any filters to the final output
    echo apply_filters('final_output', $final);
}, 0);
?>
