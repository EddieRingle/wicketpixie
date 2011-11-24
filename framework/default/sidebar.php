<?php
/**
 * WicketPixie
 * (c) 2006-2011 Eddie Ringle <eddie@eringle.net>
 * Provided by Chris Pirillo <chris@pirillo.com>
 *
 * Licensed under the New BSD License.
 */

if (is_active_sidebar(1)
    || is_active_sidebar(2)
    || is_active_sidebar(3)
    || is_active_sidebar(4)
    || is_active_sidebar(5)
    || is_active_sidebar(6)
    || is_active_sidebar(7)) { ?>
			<div id="sidebar" class="sidebar">
			    <?php wipi_before_sidebar(); ?>
				        <!-- sidebar_top -->
                <div class="sidebar_top">
                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar_top') ) : ?><?php endif;?>
                </div>
                <!-- /sidebar_top -->
                <!-- sidebar1 -->
                <div class="sidebar1">
	                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar1') ) : ?><?php endif; ?>
                </div>
                <!-- /sidebar1 -->
                <!-- sidebar2 -->
                <div class="sidebar2">	
	                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar2') ) : ?><?php endif; ?>
                </div>
                <!-- /sidebar2 -->
                <!-- sidebar3 -->
                <div class="sidebar3">
	                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar3') ) : ?><?php endif; ?>
                </div>
                <!-- /sidebar3 -->
                <!-- sidebar4 -->
                <div class="sidebar4">
	                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar4') ) : ?><?php endif; ?>
                </div>
                <!-- /sidebar4 -->
                <!-- sidebar5 -->
                <div class="sidebar5">
	                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar5') ) : ?><?php endif; ?>
                </div>
                <!-- /sidebar5 -->
                <!-- sidebar6 -->
                <div class="sidebar6">
	                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar6') ) : ?><?php endif; ?>
                </div>
                <!-- /sidebar6 -->
                <?php wipi_after_sidebar(); ?>
			</div>
<?php
}
?>
