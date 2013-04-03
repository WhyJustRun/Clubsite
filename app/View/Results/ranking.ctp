<header>
    <h2>Rankings</h2>
</header>
<div class="column span-12">
    <div class="column-box results-list">
        <h3>Current (<?=$start_date?> - <?=$end_date?>)</h3>
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>Club</th>
                    <th>Points</th>
                    <th>Events</th>
                </tr>
            </thead>
<? $counter = 1;
foreach($rankings as $ranking) {
    $name = $ranking["User"]["name"];
    $user_id = $ranking["User"]["id"];
    $points = $ranking[0]["points"];
    $count = $ranking[0]["count"];
    $club = $ranking["Club"]["acronym"];
?>
            <tr>
                <td><?=$counter?></td>
                <td><?=$this->Html->link($name,"/users/view/$user_id")?></td>
                <td><?=$club?></td>
                <td><? echo sprintf("%.2f",$points);?></td>
                <td><?=$count?></td>
            </tr>
<?$counter++;}?>
     </table>
 </div>
</div>
<div class="column span-12 last">
    <div class="column-box results-list">
        <h3>All time </h3>
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>Club</th>
                    <th>Points</th>
                    <th>Events</th>
                </tr>
            </thead>
<? $counter = 1;
foreach($rankingsAll as $ranking) {
    $name = $ranking["User"]["name"];
    $user_id = $ranking["User"]["id"];
    $points = $ranking[0]["points"];
    $count = $ranking[0]["count"];
    $club = $ranking["Club"]["acronym"];
?>
            <tr>
                <td><?=$counter?></td>
                <td><?=$this->Html->link($name,"/users/view/$user_id")?></td>
                <td><?=$club?></td>
                <td><? echo sprintf("%.2f",$points);?></td>
                <td><?=$count?></td>
            </tr>
<?$counter++;}?>
     </table>
 </div>
</div>

