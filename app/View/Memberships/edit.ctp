<h1>Membership</h1>
<?php 
echo $this->Form->create('Membership', array('action' => 'edit', 'class' => 'form-horizontal'));
echo $this->Form->input('user_id');
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->input('year', array('type'=>'text', 'label'=>'Membership year'));
echo $this->Form->input('created', array('type' => 'text'));
echo $this->Form->end('Update');
$this->append('secondaryScripts');
?>
<script type="text/javascript">
$(function() {
    $('#MembershipCreated').datetimepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: 'hh:mm:ss',
    });
});

</script>
<?php $this->end(); ?>
