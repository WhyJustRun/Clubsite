<div class="page-header">
    <div class="pull-right">
        <a href="/officials/edit" class="btn btn-success"><i class="icon-plus icon-white"></i> Official</a>
    </div>
    <h1>List of officials</h1>
</div>

<div class="row">
    <div class="span8">
<table class="table table-striped table-bordered table-condensed">
    <thead>
        <th>Name</th>
        <th>Classification</th>
        <th>Date certified</th>
        <th></th>
    </thead>
    <?
    foreach($officials as $official) {
        $name   = $official["User"]["name"];
        $classification = $official["OfficialClassification"]["name"];
        $desc = $official["OfficialClassification"]["description"];
        if($desc != "") {
           $classification = $classification . " (" . $desc . ")";
        }
        $date = substr($official["Official"]["date"],0,10);
    ?>
    <tr>
        <td><?=$name?></td>
        <td><?=$classification?> </td>
        <td><?=$date?></td>
        <td>
           <a class="btn btn-mini btn-primary" href="/officials/edit/<?= $official["Official"]["id"] ?>">
              <i class="icon-cog icon-white"></i>
           </a>
        </td>
        <td>
            <?php
            echo $this->Form->create('Official', array('action' => 'delete', 'class' => 'thin-form'));
            echo $this->Form->hidden('id', array('value'=> $official["Official"]["id"])); ?>
            <button type="submit" class="btn btn-mini btn-danger">
                <i class="icon-trash icon-white"></i>
            </button>
            <?php
            echo $this->Form->end();
            ?>
        </td>
    </tr>
    <?}?>
</table>
</div>
</div>
