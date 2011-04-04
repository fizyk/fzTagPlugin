<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<h1><?php echo __('Tags', array(), 'fzTag'); ?></h1>
<div class="fz-tag-list-header">
    <?php echo __('Sort by:', array(), 'fzTag'); ?>
    <span class="name header-<?php echo $sortParameters['by'] == 'name' 
                ? ($sortParameters['order'] == 'desc' ? 'desc' : 'asc' )
                : ''; ?>">
    <?php
        echo link_to( __('Name'), 'fz_tag', 
                array(
                    'page'=> $pager->getPage(),
                    'by' => 'name',
                    'order' => 
                            ($sortParameters['order'] == 'desc'
                                && $sortParameters['by'] == 'name')
                            ? 'asc' : 'desc')
                ) ?>
    </span>
    <span class="weight header-<?php echo $sortParameters['by'] == 'weight'
                ? ($sortParameters['order'] == 'desc' ? 'desc' : 'asc' )
                : ''; ?>">
    <?php
        echo link_to( __('Weight'), 'fz_tag',
                array(
                    'page'=> $pager->getPage(),
                    'by' => 'weight',
                    'order' =>
                            ($sortParameters['order'] == 'asc'
                                && $sortParameters['by'] == 'weight')
                            ? 'desc' : 'asc')
                ) ?>
    </span>
</div>
<ul class="fz-tag-list">
<?php foreach($pager as $tag): ?>
    <li><span class="fz-tag-name"><?php echo link_to( $tag, 'fz_tag_show', $tag, array('class' => 'fz-tag')); ?>
        <i>(<?php echo $tag->getWeight(); ?>)</i></span></li>
<?php endforeach; ?>
</ul>
<div class="fz-tag-pager">
    <?php $sortParameters = $sortParameters->getRawValue() ?>
    <?php echo link_to_if(($pager->getPage() > 1), 'Previous page',
                'fz_tag',
                array_merge( array('page' => $pager->getPreviousPage()), $sortParameters)); ?>&nbsp;
    <?php echo link_to_if(($pager->getPage() < $pager->getLastpage()), 'Next page',
                'fz_tag',
                array_merge(array('page' => $pager->getNextPage()), $sortParameters)); ?>
</div>