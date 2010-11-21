<?php use_stylesheet('../fzTagPlugin/css/fz_tag.css'); ?>
<?php use_javascript('../fzTagPlugin/js/jquery.tagsphere.min.js'); ?>
<h4><?php echo __('Tag cloud', array(), 'fzTag') ?></h4>
<div class="fz-tag-cloud-3d" style="width: <?php echo $cloudOptions['width']; ?>px;
                                    height: <?php echo $cloudOptions['height']; ?>px">
    <ul class="fz-tag-cloud">
        <?php foreach( $tags as $tag ): ?>
            <li class="fz-size-<?php echo $weightMap[$tag->getWeight()] ?>">
                  <?php echo link_to( $tag, 'fz_tag_show', $tag,
                          array(
                              'rel'=> $tag->getWeight(),
                              'class' => 'fz-tag' )); ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<script type="text/javascript">
    var tag3dOption = {
        centrex: <?php echo ($cloudOptions['width']/2); ?>,
        centrey: <?php echo ($cloudOptions['height']/2); ?>,
        min_font_size: <?php echo $cloudOptions['min_font_size']; ?>,
        max_font_size: <?php echo $cloudOptions['max_font_size']; ?>,
        zoom: <?php echo $cloudOptions['zoom']; ?>
    };
    $('.fz-tag-cloud-3d').tagcloud(tag3dOption);
</script>
