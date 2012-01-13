<h1>Edit Series</h1>
<?=$this->Form->create('Series', array('action' => 'edit'))?>
<div class="column span-12">
    <div class="column-box">
    <?php 
    echo $this->Form->create('Series', array('action' => 'edit'));
    echo $this->Form->input('acronym');
    echo $this->Form->input('name');
    echo $this->Form->input('description');
    ?>

    </div>
</div>
<div class="column span-12 last">
    <div class="column-box">
    <?
    echo $this->Form->input('color');
    echo $this->Form->input('information');
    echo $this->Element('markdown_basics');
    echo $this->Form->input('is_current');
    ?>
    </div>
</div>
<?= $this->Form->end('Save')?>
