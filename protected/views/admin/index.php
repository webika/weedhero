    <div id="mm-inner-login">

        <?php  $this->widget('AdminLogin'); ?>
    </div>
<script>
    jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 3) +
                                                $(window).scrollTop()) + "px");
    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
                                                $(window).scrollLeft()) + "px");
    return this;
}
    jQuery(document).ready(function() {
            jQuery(window).resize(function() {
                jQuery("#mm-inner-login").center();
            });
            jQuery("#mm-inner-login").center();
    });
</script>
