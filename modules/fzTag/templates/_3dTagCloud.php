<?php use_stylesheet('../fzTagPlugin/css/fz_tag.css'); ?>
<?php use_javascript('../fzTagPlugin/js/jquery.tagsphere.min.js'); ?>
<h4><?php echo __('Tag cloud', array(), 'fzTag') ?></h4>
<div class="fz-tag-cloud-3d" id="fz-tag-cloud-3d<?php echo $cloudOptions['cloud_id']; ?>" style="width: <?php echo $cloudOptions['width']; ?>px;
                                    height: <?php echo $cloudOptions['height']; ?>px;">
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
    $(document).ready(function()
    {
        $('#fz-tag-cloud-3d<?php echo $cloudOptions['cloud_id']; ?>').tagcloud({
            centrex: <?php echo ($cloudOptions['width']/2); ?>,
            centrey: <?php echo ($cloudOptions['height']/2); ?>,
            min_font_size: <?php echo $cloudOptions['min_font_size']; ?>,
            max_font_size: <?php echo $cloudOptions['max_font_size']; ?>,
            zoom: <?php echo $cloudOptions['zoom']; ?>,
            min_zoom: <?php echo $cloudOptions['min_zoom']; ?>,
            max_zoom: <?php echo $cloudOptions['max_zoom']; ?>,
            zoom_factor: <?php echo $cloudOptions['zoom_factor']; ?>,
            rotate_factor: <?php echo $cloudOptions['rotate_factor']; ?>,
            fps: <?php echo $cloudOptions['fps']; ?>,
            random_points: <?php echo $cloudOptions['random_points']; ?>
        });
        alert('Alert? I am working');
    });
</script>
