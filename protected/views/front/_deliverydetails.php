<div id="wpmm-item-details-header">
    <p id="wpmm-item-details-name"><?php echo $model->name; ?></p>
</div>
<div id="wpmm-item-details-content">
    <div id="wpmm-item-details-content-right" class="noimage" style="padding-top: 0px; padding-bottom: 0px; line-height: 15px;">
        <table border="0">
            <?php if (!empty($model->address)) { ?>
                <tr><td class="wpmm-color-text"><b>Address: </b></td><td><?php
            echo $model->address;
                ?></td></tr> <?php
                }
            ?>
            <?php if (!empty($model->phone)) { ?>
                <tr><td class="wpmm-color-text"><b>Phone: </b></td><td><?php
            echo $model->phone;
                ?></td></tr> <?php
                }
            ?>
            <?php if (!empty($model->fax)) { ?>
                <tr><td class="wpmm-color-text"><b>Fax: </b></td><td><?php
            echo $model->fax;
            ?></td></tr> <?php
        }
            ?>
            <?php if (!empty($model->l_email)) { ?>
                <tr><td class="wpmm-color-text"><b>E-mail: </b></td><td><?php
            echo $model->l_email;
                ?></td></tr> <?php
        }
            ?>
        </table>
    </div>
</div>
<div id="wpmm-item-details-more">
    <div style="padding-left:8px;padding-right:8px;">
<?php if (!empty($options['delivery_charge'])) { ?>
            <strong class="wpmm-color-text">Delivery Charge: </strong><?php
    echo '$' . $options['delivery_charge'];
    ?><br> <?php
    }
?>
        <?php if (!empty($options['min_order'])) { ?>
            <strong class="wpmm-color-text">Minimum order amount: </strong><?php
        echo '$' . $options['min_order'];
            ?><br> <?php
    }
        ?>
            <?php
            $map = unserialize($model->gmap);
            ?>
        <script>
            jQuery(document).ready(function(){
                var mapOptions = {
                    center: new google.maps.LatLng(<?php echo $map['map-center-lat'] ?>, <?php echo $map['map-center-long'] ?>),
                    zoom: <?php echo $map['map-zoom'] ?>,
                    disableDefaultUI: true,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                var map = new google.maps.Map(document.getElementById("wpmm-google-map"),
                mapOptions);
                map.setOptions({draggable: true, zoomControl: true, scrollwheel: true, disableDoubleClickZoom: true});
                var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(<?php echo $map['mapmark-center-lat'] ?>,<?php echo $map['mapmark-center-long'] ?>),
                            map: map,
                            title:"<?php echo $model->name; ?>"
                        });
                google.maps.event.trigger(map, 'resize');
            });
        </script>
        <div id="wpmm-google-map" style="width: 100%; height: 200px;"></div>
        <span class="wpmm-color-text"><b>Allowed zipcodes:</b> </span> <?php $ziplist = unserialize($model->zip_list); foreach($ziplist as $key => $zip){
            if($key == 0){
                echo $zip;
            }else{
                echo ', '.$zip;
            }
        } ?>
</div>
<div id="wpmm-item-details-footer">
    <a href="#" id="wpmm-item-details-close">Close</a>
</div>
<script>
    if (!mobile) {
        jQuery('#wpmm-item-details').draggable({ handle: "#wpmm-item-details-header" });
    }
    jQuery('#wpmm-item-details-close').on('click', function() {
        jQuery('#wpmm-item-details').hide();
        if (mobile) {
            jQuery('#wpmm-footer').show();
            if (jQuery("#wpmm-footer a.ui-btn-active").parent('li').hasClass('ui-block-a')) {
                jQuery('#wpmm-form').hide();
                jQuery('#wpmm .ui-collapsible-set').show();
            } else if (jQuery("#wpmm-footer a.ui-btn-active").parent('li').hasClass('ui-block-b')) {
                jQuery('#wpmm .ui-collapsible-set').hide();
                jQuery('#wpmm-form').show();
            }
        }
        return false;
    });
</script>
