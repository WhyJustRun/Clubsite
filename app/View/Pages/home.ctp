<?php $this->set('title_for_layout', Configure::read("Club.name")); ?>
<div class="three-column">
	<article class="column span-8">
      <div class="padded">
        <?= $this->ContentBlock->render('general_information'); ?>
      </div>
	</article>
	
	<article class="column span-8">
		<header>
		<h2>Club Events</h2>
		</header>
		<?php echo $this->element('Events/box-list', array('filter' => 'upcoming', 'limit' => '5')); ?>
		
		<h3>Past</h3>
		<?php echo $this->element('Events/box-list', array('filter' => 'past', 'limit' => '5')); ?>
        
        <div style="text-align: center">
            <?= $this->element('Events/add_link'); ?>
        </div>
	</article>
	
	<article class="column span-8 last">
		<header>
		<h2>News</h2>
		</header>
		
		<?= $this->FacebookGraph->feed('news', array('limit' => 5)); ?>
      
	</article>
</div>
