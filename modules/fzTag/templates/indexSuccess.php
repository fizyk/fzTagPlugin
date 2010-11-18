<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<h1><?php echo __('Tags', array(), 'fzTag'); ?></h1>
<ul class="fz-tag-list">
<?php foreach($pager as $tag): ?>
    <li><span class="fz-tag-name"><?php echo $tag; ?></span>
        <span class="fz-tag-weight"><?php echo $tag->getWeight(); ?></span></li>
<?php endforeach; ?>
</ul>
<div class="fz-tag-pager">
    <?php echo link_to_if(($pager->getPage() > 1), 'Previous page',
                'fzTag/index',
                array('query_string' => 'page='.$pager->getPreviousPage())); ?>&nbsp;
    <?php echo link_to_if(($pager->getPage() < $pager->getLastpage()), 'Next page',
                'fzTag/index',
                array('query_string' => 'page='.$pager->getNextPage())); ?>
</div>