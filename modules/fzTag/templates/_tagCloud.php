<?php use_stylesheet('../fzTagPlugin/css/fz_tag.css'); ?>
<h4><?php echo __('Tag cloud', array(), 'fzTag') ?></h4>
<ul class="fz-tag-cloud" id="fz-tag-cloud<?php echo $cloudOptions['cloud_id']; ?>">
<?php foreach( $tags as $tag ): ?>
  <li class="fz-size-<?php echo $weightMap[$tag->getWeight()] ?>">
          <?php echo link_to( $tag, 'fz_tag_show', $tag) ?>
  </li>
<?php endforeach; ?>
</ul>