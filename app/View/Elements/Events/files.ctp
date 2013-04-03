<?
$imageFileName = Configure::read('Event.dir') . $id.'.html';
if(file_exists($imageFileName)) {?>
    <div class="results-list">
        <?= $this->Media->linkedFile("Result", $id) ?> 
    </div>
    <?
}
?>

