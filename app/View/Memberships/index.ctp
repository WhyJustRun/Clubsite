<header>
    <h1>Memberships</h1>
</header>
    <div class="col-sm-4">
        <div class="column-box">
            <?php
            $date = new DateTime();
            $year = $date->format('Y');
            $currDate = $date->format('Y-m-d H:i:s');
            echo $this->Form->create('Membership', array('url' => 'edit'));
            echo $this->Form->input('user_id');
            echo $this->Form->input('year', array('type'=>'text', 'label'=>'Membership year', 'value'=>$year));
            echo $this->Form->input('created', array('type' => 'text', 'value'=>$currDate));
            //echo $this->Form->end(array('label'=>'Add membership', 'div'=>'right submit padded'));
            echo $this->Form->end('Add membership');
            //echo $this->Form->end(array('label' => 'Add membership', 'div' => 'right submit padded'));?>
        </div>
    </div>

    <div class="col-sm-8">
        <div class="results-list">
            <?php
            // Ideally I would have an array of memberships for each year, but
            // I couldn't figure out the correct way to use the 'find' command in
            // the controller. Therefore, I'm using an insane hack to show
            // memberships by year. This method assumes the memberships are sorted
            // descendingly by year.
            $startYear = 999999;
            $prevYear = $startYear;
            foreach($memberships as $membership) {
                $currYear = $membership["Membership"]["year"];
                if($currYear != $prevYear) {
                    if($prevYear != $startYear) {
                        // Close the previous membership table, if this is not the first year?>
                        </table>
                    <?php }
                    // We have moved on to a new year, therefore show a new header?>
                    <h3>Membership year: <?php echo $currYear?></h3>
            <table>
                <thead>
                    <tr><th>Name</th><th></th><th></th></tr>
                </thead>
                <?php }
                $prevYear = $currYear;?>
                    <tr>
                        <td><?php echo $membership["User"]["name"]?></td>
                        <?php //<td><?= $this->Html->link('Edit', '/memberships/edit/'.$membership["Membership"]["id"], array('class' => 'button'));</td>?>
                        <td><?php echo $this->Html->link('Edit', '/memberships/edit/'.$membership["Membership"]["id"], array('class' => 'button'));?></td>
                        <td>
                        <?php
                            echo $this->Form->create('Membership', array('url' => 'delete'));
                            echo $this->Form->hidden('id', array('value'=> $membership["Membership"]["id"]));
                            echo $this->Form->submit('Remove', array('div'=>array('class'=>'unsubmit')));
                            echo $this->Form->end();
                        ?>
                        </td>
                    </tr>
                <?php }?>
            </table>
        </div>
    </div>
