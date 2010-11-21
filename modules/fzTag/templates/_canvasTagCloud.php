<?php use_stylesheet('../fzTagPlugin/css/fz_tag.css'); ?>
<?php use_javascript('../fzTagPlugin/js/jquery.tagcanvas.min.js'); ?>
<h4><?php echo __('Tag cloud', array(), 'fzTag') ?></h4>
<canvas width="<?php echo $cloudOptions['width']; ?>" height="<?php echo $cloudOptions['height']; ?>" id="fz-tag-canvas">
</canvas>
<ul class="fz-tag-cloud" id="fz-tag-canvas-list">
    <?php foreach($tags as $tag): ?>
        <li class="fz-size-<?php echo $weightMap[$tag->getWeight()] ?>">
        <?php echo link_to($tag, 'fz_tag_show', $tag) ?>
    </li>
    <?php endforeach; ?>
</ul>
<script type="text/javascript">
    $(document).ready(function()
    {
        var tagCanvasOptions = {
            maxSpeed: <?php echo $cloudOptions['maxSpeed'] ?>,
            minSpeed: <?php echo $cloudOptions['minSpeed'] ?>,
            decel:<?php echo $cloudOptions['decel'] ?>,
            minBrightness: <?php echo $cloudOptions['minBrightness'] ?>,
            textColour: '<?php echo $cloudOptions['textColour'] ?>',
            textHeight: <?php echo $cloudOptions['textHeight'] ?>,
            textFont: '<?php echo $cloudOptions['textFont'] ?>',
            outlineColour: '<?php echo $cloudOptions['outlineColour'] ?>',
            outlineThickness: <?php echo $cloudOptions['outlineThickness'] ?>,
            outlineOffset: <?php echo $cloudOptions['outlineOffset'] ?>,
            pulsateTo: <?php echo $cloudOptions['pulsateTo'] ?>,
            pulsateTime: <?php echo $cloudOptions['pulsateTime'] ?>,
            depth: <?php echo $cloudOptions['depth'] ?>,
            initial: <?php echo $cloudOptions['initial'] ? $cloudOptions['initial'] : 'null' ?>,
            freezeActive: <?php echo $cloudOptions['freezeActive'] ? 'true' : 'false' ?>,
            reverse: <?php echo $cloudOptions['reverse'] ? 'true' : 'false' ?>,
            hideTags: <?php echo $cloudOptions['hideTags'] ? 'true' : 'false' ?>
        };
        if( ! $('#fz-tag-canvas').tagcanvas(tagCanvasOptions, 'fz-tag-canvas-list'))
        {
            // TagCanvas failed to load
            $('#myCanvasContainer').hide();
        }
        // your other jQuery stuff here...
    });
 </script>