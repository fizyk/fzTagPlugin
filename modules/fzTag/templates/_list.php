<h4><?php echo __('Tags', array(), 'fzTag') ?></h4>
<ul>
<?php foreach( $tags as $tag ): ?>
  <li><?php echo link_to( $tag, 'fz_tag_show', $tag) ?></li>
<?php endforeach; ?>
</ul>