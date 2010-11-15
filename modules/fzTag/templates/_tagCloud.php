<h4><?php echo __('Tag cloud', array(), 'fzTag') ?></h4>
<ul class="fz-tag-cloud">
<?php foreach( $tags as $tag ): ?>
  <li class="fz-size-<?php echo $weightMap[$tag->getWeight()] ?>"><?php echo $tag ?></li>
<?php endforeach; ?>
</ul>