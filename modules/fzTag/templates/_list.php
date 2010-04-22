<h4><?php echo __('Tags', array(), 'fzTag') ?></h4>
<ul>
<?php foreach( $tags as $tag ): ?>
  <li><?php echo $tag ?></li>
<?php endforeach; ?>
</ul>