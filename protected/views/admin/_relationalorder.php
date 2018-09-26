<?php
if($model == 'No address provided.'){
    echo $model;
} else { ?>
<table class="tbset table-bordered table-striped" style="margin:5px; width: 99%;">
    <tr>
        <th>City</th>
        <th>State</th>
        <th>Address</th>
        <th>Zip code</th>
    </tr>
    <tr>
        <td><?php echo $model->addresses->city; ?></td>
        <td><?php echo $model->addresses->state; ?></td>
        <td>
            <?php if($model->addresses->address) {echo $model->addresses->address.'.';} ?>
        </td>
        <td><?php echo $model->addresses->location; ?></td>
    </tr>
</table>
<?php } ?>