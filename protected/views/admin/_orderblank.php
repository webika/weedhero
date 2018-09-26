<?php
$timezone = array(
            'Australia/Adelaide'            => 'Australia - Adelaide',
            'Australia/Brisbane'            => 'Australia - Brisbane',
            'Australia/Broken_Hill'         => 'Australia - Broken Hill',
            'Australia/Currie'              => 'Australia - Currie',
            'Australia/Darwin'              => 'Australia - Darwin',
            'Australia/Eucla'               => 'Australia - Eucla',
            'Australia/Hobart'              => 'Australia - Hobart',
            'Australia/Lindeman'            => 'Australia - Lindeman',
            'Australia/Lord_Howe'           => 'Australia - Lord Howe',
            'Australia/Melbourne'           => 'Australia - Melbourne',
            'Australia/Perth'               => 'Australia - Perth',
            'Australia/Sydney'              => 'Australia - Sydney',
            'America/Atikokan'              => 'Canada - Atikokan',
            'America/Blanc-Sablon'          => 'Canada - Blanc-Sablon',
            'America/Cambridge_Bay'         => 'Canada - Cambridge Bay',
            'America/Creston'               => 'Canada - Creston',
            'America/Dawson'                => 'Canada - Dawson',
            'America/Dawson_Creek'          => 'Canada - Dawson Creek',
            'America/Edmonton'              => 'Canada - Edmonton',
            'America/Glace_Bay'             => 'Canada - Glace Bay',
            'America/Goose_Bay'             => 'Canada - Goose Bay',
            'America/Halifax'               => 'Canada - Halifax',
            'America/Inuvik'                => 'Canada - Inuvik',
            'America/Iqaluit'               => 'Canada - Iqaluit',
            'America/Moncton'               => 'Canada - Moncton',
            'America/Montreal'              => 'Canada - Montreal',
            'America/Nipigon'               => 'Canada - Nipigon',
            'America/Pangnirtung'           => 'Canada - Pangnirtung',
            'America/Rainy_River'           => 'Canada - Rainy River',
            'America/Rankin_Inlet'          => 'Canada - Rankin Inlet',
            'America/Regina'                => 'Canada - Regina',
            'America/Resolute'              => 'Canada - Resolute',
            'America/St_Johns'              => 'Canada - St Johns',
            'America/Swift_Current'         => 'Canada - Swift Current',
            'America/Thunder_Bay'           => 'Canada - Thunder Bay',
            'America/Toronto'               => 'Canada - Toronto',
            'America/Vancouver'             => 'Canada - Vancouver',
            'America/Whitehorse'            => 'Canada - Whitehorse',
            'America/Winnipeg'              => 'Canada - Winnipeg',
            'America/Yellowknife'           => 'Canada - Yellowknife',
            'Europe/London'                 => 'United Kingdom',
            'America/Adak'                  => 'USA - Adak',
            'America/Anchorage'             => 'USA - Anchorage',
            'America/Boise'                 => 'USA - Boise',
            'America/Chicago'               => 'USA - Chicago',
            'America/Denver'                => 'USA - Denver',
            'America/Detroit'               => 'USA - Detroit',
            'Pacific/Honolulu'              => 'USA - Honolulu',
            'America/Indiana/Indianapolis'  => 'USA - Indiana / Indianapolis',
            'America/Indiana/Knox'          => 'USA - Indiana / Knox',
            'America/Indiana/Marengo'       => 'USA - Indiana / Marengo',
            'America/Indiana/Petersburg'    => 'USA - Indiana / Petersburg',
            'America/Indiana/Tell_City'     => 'USA - Indiana / Tell City',
            'America/Indiana/Vevay'         => 'USA - Indiana / Vevay',
            'America/Indiana/Vincennes'     => 'USA - Indiana / Vincennes',
            'America/Indiana/Winamac'       => 'USA - Indiana / Winamac',
            'America/Juneau'                => 'USA - Juneau',
            'America/Kentucky/Louisville'   => 'USA - Kentucky / Louisville',
            'America/Kentucky/Monticello'   => 'USA - Kentucky / Monticello',
            'America/Los_Angeles'           => 'USA - Los Angeles',
            'America/Menominee'             => 'USA - Menominee',
            'America/Metlakatla'            => 'USA - Metlakatla',
            'America/New_York'              => 'USA - New York',
            'America/Nome'                  => 'USA - Nome',
            'America/North_Dakota/Beulah'   => 'USA - North Dakota / Beulah',
            'America/North_Dakota/Center'   => 'USA - North Dakota / Center',
            'America/North_Dakota/New_Salem'=> 'USA - North Dakota / New Salem',
            'America/Phoenix'               => 'USA - Phoenix',
            'America/Shiprock'              => 'USA - Shiprock',
            'America/Sitka'                 => 'USA - Sitka',
            'America/Yakutat'               => 'USA - Yakutat',
        );
$payment_type=array('credit_card'=>'Credit Card','cash'=>'Cash');
$payment_params =  unserialize($model->params);
$promo_code = unserialize($model->promo_code);
?>
<table border="0" width="100%" style="border-bottom: 2px solid #000; margin-bottom: 15px;">
    <tr>
        <td align="left" valign="bottom">
            <i>Order from</i><br>
            <b><span style="font-size: 20px;"><?php echo $model->location->name ?></span></b><br>
            <i>Referred by </i><b><?php echo preg_replace('#^https?://#', '', $_SERVER['SERVER_NAME']); ?>
            </b>
        </td>
        <td align="right" valign="bottom">
            <i>Sent at</i><br>
            <b><span style="font-size: 20px;">
                    <?php echo date('m/d/Y h:i:s a', strtotime($model->time_ordered)) . ' ' . $timezone[$payment_params['timezone']]; ?>
                </span></b><br>
            <i>Online order</i>
        </td>
    </tr>
</table>
<table width="100%" style="border-spacing: 5px 5px; border-collapse: separate">
    <tr>
        <td align="left" valign="top" width="20%" style="padding: 10px; border: 1px solid #000; font-size: 16px;">
            <b><span style="font-size: 20px;">Customer info</span></b><br><br>
            <?php if ($model->customer) { ?>
                <?php if ($model->customer->name) $cname = $model->customer->name; ?>
                <?php if ($model->customer->surname) $csurname = $model->customer->surname; ?>
                <?php if ($model->customer->c_mail) $c_mail = $model->customer->c_mail; ?>
                <?php if ($model->customer->phone) $cphone = $model->customer->phone; ?>
                Name : <b><?php echo $cname . ' '; ?> <?php echo $csurname . ' '; ?> </b><br>
                Contact : <?php echo '<b>' . $c_mail . '</b><br>'; ?>
                <?php echo '<b>' . $cphone . '</b><br>'; ?>
            <?php } ?>
        </td>
        <td align="left" valign="top" width="80%" style="padding: 10px; border: 1px solid #000; font-size: 16px;">
            <b><span style="font-size: 20px;">Order info</span></b><br><br>
            Order #: <b><?php echo $model->id; ?> </b><br>
            Type : <b> <?php echo $model->delivery_type; ?>  </b><br>
            <?php if(!empty($promo_code['code'])){ ?>
            Promo code: <b><?php echo $promo_code['code'] ?></b><br>
            <?php } ?>
            Delivery Address : <b> <?php
            if ($model->addresses != NULL) {
                echo $model->addresses->address. ',  ';
                echo $model->addresses->city . ', ';
                if (!empty($model->addresses->state))
                    echo $model->addresses->state . '  ';
                echo $model->addresses->location . '  ';
            }
            ?> </b><br>
            <?php if(!empty($model->notes)){ ?>
            Delivery instructions: <?php echo $model->notes; ?><br>
            <?php } ?>
            Time: <?php
                $deliverydate = date('m/d/Y h:i:s a', strtotime($model->time_delivery)) . ' ' . $timezone[$payment_params['timezone']];
                if (strtotime($model->time_delivery) < strtotime($model->time_ordered)) {
                    echo '<b>ASAP</b>';
                } else {
                    echo $deliverydate;
                }
            ?><br>
            Payment : <b> <?php if($model->payment == 0){ echo 'Pay by ';} else {echo 'Paid by ';} echo $payment_type[$model->payment_type]; ?> </b><br>
        </td>
    </tr>
</table>
<div style="padding:5px;">
    <table width="100%" class="orderlet" style="border: 1px solid #000; font-size: 16px; border-collapse: collapse">
        <tr>
            <th width="40" style="border: 1px solid #000;"><b>Qty.</b></th>
            <th style="border: 1px solid #000;"><b>Item</b></th>
            <th width="120" style="border: 1px solid #000;"><b>Price</b></th>
        <tr>
            <?php
            $glob_sum = 0;
            $food_bev = 0;
            $promo_disc = 0;
            $total_tax = 0;
            $tips = 0;
            ?>
            <?php
            if ($model->items) {
                $slicearray = array(3=>'left',1=>'full',2=>'right');
                foreach ($model->items as $item) {
                    $attributes = unserialize($item->attribs);
                    $sum_item = 0;
                    $attrib_list = '';
                    $sum_item+=(float) $item->item_price;
                    $quantity = (int) $attributes['quantity'];
                    $origitem = MMItem::model()->findByPk($item->itemid);
                    if($origitem != NULL){
                    if($origitem->type == 1) $item->item_name .=' ( '.Helpers::getPizzaCut((int) $attributes['cut']).' ) ';}
                    foreach ($attributes as $key => $attr) {
                        if (is_array($attr)) {
                            if(!empty($attr['size'])){
                                if($attr['size'] == 2 || $attr['size'] == 3) $attr['price']=$attr['price']/2;
                                $attr['name'] .= ' ( '.$slicearray[$attr['size']].' )';
                            }
                            $sum_item+=(float) $attr['price'];
                            if ($key == 0) {
                                $attrib_list.=$attr['name'] . ' $' . number_format((float) $attr['price'], 2, '.', ' ') . ' ';
                            } else
                                $attrib_list.='| ' . $attr['name'] . ' $' . number_format((float) $attr['price'], 2, '.', ' ') . ' ';
                        }
                    }
                    ?>
                <tr>
                    <td align="center"><?php echo $quantity; ?></td>
                    <td><?php echo $item->item_name;
                    if (!empty($attrib_list))
                        echo' ( ' . $attrib_list . ' )';
                    if (!empty($item->instructions))
                        echo' | <b>Instructions:</b> ' . $item->instructions;
                    ?></td>
                    <td align="center"><b><?php echo '$' . number_format($sum_item*(int)$quantity, 2, '.', ' '); ?></b></td>
                </tr>
        <?php
        $glob_sum+=(float) ((float) $sum_item * (int) $quantity);
    }
}
?>
        <tr>
            <td></td>
            <td align="right"><b>Food & Bev Total:</b></td>
            <td align="center">$<?php
                $food_bev = $glob_sum;
                echo number_format($food_bev, 2, '.', ' ');
?></td>
        </tr>
        <?php
        $discount = $payment_params['discount'];
        if ((int) $discount > 0) {
            if ($food_bev > 0) {
                $discount = ($discount * $food_bev) / 100;
            } else
                $discount = 0;

            $glob_sum-=$discount;
            ?>
            <tr>
                <td></td>
                <td align="right"><b>Discount:</b></td>
                <td align="center">$<?php echo number_format($discount, 2, '.', ' ') ?></td></tr>
                <?php } ?>
            <?php if( (float) $promo_code['amount'] > 0){
                if($promo_code['type'] == 0){
                    $promo_disc=$promo_code['amount'];
                } else {
                    $promo_disc=((float) $promo_code['amount'] * (float) ($food_bev-$discount)) / 100;
                }
                $glob_sum-=$promo_disc;
                ?>
                <tr>
                    <td></td>
                    <td align="right"><b>Promo code:</b></td>
                    <td align="center">$<?php echo number_format($promo_disc, 2, '.', ' ') ?></td>
                </tr>
            <?php } ?>
        <tr>
            <td></td>
            <td align="right"><b>Sales Tax:</b></td>
            <td align="center">$<?php
                $taxrate = $taxrate = $payment_params['tax'];
                if ($glob_sum > 0) {
                    $total_tax = ($glob_sum * $taxrate) / 100;
                } else
                    $total_tax = 0;
                $glob_sum+=$total_tax;
                $tax_only = $total_tax;
                echo number_format($total_tax, 2, '.', ' ');
                ?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td align="right"><b>Total with tax:</b></td>
            <td align="center">$<?php $total_tax = $glob_sum;
                echo number_format($total_tax, 2, '.', ' ');
                ?></td>
        </tr>
        <?php if($model->delivery_type != 'Pickup') { ?>
        <tr>
            <td></td>
            <td align="right"><b>Delivery charge:</b></td>
            <td align="center">$<?php echo number_format($payment_params['delivery'], 2, '.', ' '); ?></td>
        </tr>
        <?php } ?>
        <?php
            $tipamount = 0;
                switch ($model->tip_type) {
                    case 'percent':
                        if ($food_bev > 0) {
                            $tipamount = ($model->tip * $food_bev) / 100;
                        } else
                            $tipamount = 0;
                        break;
                    case 'amount':
                        $tipamount = $model->tip;
                        break;
                    default:
                        $tipamount = 0;
                        break;
                }
                if($tipamount > 0){
        ?>
        <tr>
            <td></td>
            <td align="right"><b>Tips:</b></td>
            <td align="center">$<?php echo number_format($tipamount, 2, '.', ' ');
                ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td></td>
            <td align="right"><b>Total:</b></td>
            <td align="center">$<?php
                $glob_sum+=$tipamount;
                if($model->delivery_type != 'Pickup'){
                $glob_sum+=$payment_params['delivery'];
                }
                echo number_format($glob_sum, 2, '.', ' ');
                ?></td>
        </tr>
    </table>
<?php if ($full) { ?>
        <table border="0" width="100%"><tr><td width="25%">
                    <table style="margin-top: 15px;" border="0" width="100%">
                        <tr>
                            <td><b><span style="font-size: 16px;">Store Copy</span></b><br><br></td>
                        </tr>
                        <tr>
                            <td align="right" style="font-size: 12px;">
                                Order #: <b><?php echo $model->id; ?> </b><br>
                                Type : <b> <?php echo $model->delivery_type; ?>  </b><br>
                                Delivery Address : <b> <?php
                                if ($model->addresses != NULL) {
                                    echo $model->addresses->address . ',  ';
                                    echo $model->addresses->city . ', ';
                                    if (!empty($model->addresses->state))
                                        echo $model->addresses->state . '  ';
                                    echo $model->addresses->location . ' ';
                                }
                                ?> </b><br>
                                <?php if(!empty($model->notes)){ ?>
                                    Delivery instructions: <?php echo $model->notes; ?><br>
                                <?php } ?>
                                Time: <?php
                                if (strtotime($model->time_delivery) < strtotime($model->time_ordered)) {
                                    echo '<b>ASAP</b>';
                                } else {
                                    echo $deliverydate;
                                }
                                ?><br>
                                Payment : <b> <?php if($model->payment == 0){ echo 'Pay by ';} else {echo 'Paid by ';} echo $payment_type[$model->payment_type]; ?> </b><br>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="bottom" style="border-bottom:2px solid #000;"><br><b><span style="font-size: 14px;">Driver</span></b></td>
                        </tr>
                        <tr>
                            <td align="left" valign="bottom" style="border-bottom:2px solid #000;"><br><b><span style="font-size: 14px;">Cust. Signature:</span></b></td>
                        </tr>
                    </table></td><td width="75%" valign="top">
                    <table width="100%" style="border: 1px solid #000; margin-top: 10px;" cellpadding="5">
                        <?php
                        $glob_sum1 = 0;
                        if ($model->items) {
                            foreach ($model->items as $item) {
                                $attributes = unserialize($item->attribs);
                                $sum_item = 0;
                                $attrib_list = '';
                                $sum_item+=(float) $item->item_price;
                                $quantity = $attributes['quantity'];
                                foreach ($attributes as $key => $attr) {
                                    if (is_array($attr)) {
                                        if(!empty($attr['size'])){
                                            if($attr['size'] == 2 || $attr['size'] == 3) $attr['price']=$attr['price']/2;
                                            $attr['name'] .= ' ( '.$slicearray[$attr['size']].' )';
                                        }
                                        $sum_item+=(float) $attr['price'];
                                        if ($key == 0) {
                                            $attrib_list.=$attr['name'] . ' $' . number_format((float) $attr['price'], 2, '.', ' ') . ' ';
                                        } else
                                            $attrib_list.='| ' . $attr['name'] . ' $' . number_format((float) $attr['price'], 2, '.', ' ') . ' ';
                                    }
                                }
                                ?>
                                <tr>
                                    <td width="40" align="center"><b><?php echo $quantity; ?></b></td>
                                    <td><?php echo $item->item_name;
                    if (!empty($attrib_list))
                        echo' ( ' . $attrib_list . ' )';
                    if (!empty($item->instructions))
                        echo' | <b>Instructions:</b> ' . $item->instructions;
                    ?></td>
                                    <td width="120" align="right"><b><?php echo '$' . $sum_item*(int)$quantity; ?></b></td>
                                </tr>
            <?php
            $glob_sum1+=(float) $sum_item * (int) $quantity;
        }
    }
    ?>
                        <tr>
                            <td colspan="3" valign="top">
                                <table border="0" style="border-top: 1px solid #000" width="100%">
                                    <tr>
                                        <td style="border-right: 1px solid #000">
                                            Food & Bev Total: <?php echo '$' . number_format($food_bev, 2, '.', ' '); ?>  <?php if ($discount > 0) echo '| Discount: $' . number_format($discount, 2, '.', ' '); ?> <?php if($promo_disc > 0) echo '| Promo code: $'. number_format($promo_disc, 2, '.', ' '); ?> | Sales Tax: <?php echo '$' . number_format($tax_only, 2, '.', ' '); ?> | Total with tax: <?php echo '$' . number_format($total_tax, 2, '.', ' '); ?> <?php if($model->delivery_type != 'Pickup'){ echo ' | Delivery charge: $' . number_format($payment_params['delivery'], 2, '.', ' '); }?> <?php if($tipamount >0){ echo ' | Tips: $' . number_format($tipamount, 2, '.', ' '); }?>
                                        </td>
                                        <td width="120" align="center">
                                            <span style="font-size:18px; margin: 2px; display: block">
                                                Total:<br>
                                                <b><?php echo '$' . number_format($glob_sum, 2, '.', ' '); ?></b>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
<?php } ?>
</div>