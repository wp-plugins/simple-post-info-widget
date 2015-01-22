<?php
/*
Plugin Name: Simple Post Info Widget
Plugin URI: http://tjcafferkey.me/
Description: By simply entering your posts ID number you can directly display your specific post information on your WordPress sidebar in widget form.
Author: Tom Cafferkey
Version: 1.0
Author URI: http://tjcafferkey.me/
*/

class PostInfoWidget extends WP_Widget
{
  function PostInfoWidget()
  {
    $widget_ops = array('classname' => 'PostInfoWidget', 'description' => 'Displays post information using post ID' );
    $this->WP_Widget('PostInfoWidget', 'Post Specific Widget', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'show_title' => '', 'post_id' => '', 'show_image' => '', 'show_name' => '', 'show_excerpt' => '', 'show_link' => '' ) );
    $title = $instance['title'];
    $post_id = $instance['post_id'];
    $show_title = $instance['show_title']; 
    $show_image = $instance['show_image'];
    $show_name = $instance['show_name'];
    $show_excerpt = $instance['show_excerpt'];
    $show_link = $instance['show_link'];
?>

  <p>
  <label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label>
  </p>

  <p>
  <label for="<?php echo $this->get_field_id('post_id'); ?>">Post ID: <input class="widefat" id="<?php echo $this->get_field_id('post_id'); ?>" name="<?php echo $this->get_field_name('post_id'); ?>" type="text" value="<?php echo attribute_escape($post_id); ?>" /></label>
  </p>

  <p><input class="checkbox" type="checkbox" <?php checked($instance['show_title'], 'on'); ?> id="<?php echo $this->get_field_id('show_title'); ?>" name="<?php echo $this->get_field_name('show_title'); ?>" /> Overide post name with widget title</p>
  <p><input class="checkbox" type="checkbox" <?php checked($instance['show_image'], 'on'); ?> id="<?php echo $this->get_field_id('show_image'); ?>" name="<?php echo $this->get_field_name('show_image'); ?>" /> Show posts featured image</p>
  <p><input class="checkbox" type="checkbox" <?php checked($instance['show_name'], 'on'); ?> id="<?php echo $this->get_field_id('show_name'); ?>" name="<?php echo $this->get_field_name('show_name'); ?>" /> Show posts name</p>
  <p><input class="checkbox" type="checkbox" <?php checked($instance['show_excerpt'], 'on'); ?> id="<?php echo $this->get_field_id('show_excerpt'); ?>" name="<?php echo $this->get_field_name('show_excerpt'); ?>" /> Show posts excerpt</p>
  <p><input class="checkbox" type="checkbox" <?php checked($instance['show_link'], 'on'); ?> id="<?php echo $this->get_field_id('show_link'); ?>" name="<?php echo $this->get_field_name('show_link'); ?>" /> Link widget to post</p>

<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['post_id'] = $new_instance['post_id'];
    $instance['show_title'] = $new_instance['show_title'];
    $instance['show_image'] = $new_instance['show_image'];
    $instance['show_name'] = $new_instance['show_name'];
    $instance['show_excerpt'] = $new_instance['show_excerpt'];
    $instance['show_link'] = $new_instance['show_link'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;

    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
    $postInfo = get_post($instance['post_id']); // Post variable

      ?>
    
      <div class="post-widget">

        <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $instance['post_id'] ), 'single-post-thumbnail' ); ?>

        <?php if($instance['show_image']) { ?>
          <div class="post-image" style="background-image:url(<?php echo $image[0]; ?>);"></div>
        <?php } ?>

        <?php if($instance['show_title']) { ?>
          <div class="property-name"><?php echo $title; ?></div>
        <?php } elseif($instance['show_name']) { ?>
          <div class="post-name"><?php echo $postInfo->post_title; ?></div>
        <?php } ?>

        <?php if($instance['show_excerpt']) { ?>
          <div class="post-excerpt"><?php echo mb_strimwidth($postInfo->post_content, 0, 100, "..."); ; ?></div>
        <?php } ?>

        <?php if($instance['show_link']) { ?>
          <button class="cta"><a href="<?php echo get_permalink($postInfo->ID); ?>">More Property Details</a></button>
        <?php } ?>

      </div>

      <?php

    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("PostInfoWidget");') );?>