<!-- 
<div class="facebook-frame">
	<div class="fb-like-box" data-href="https://www.facebook.com/MultiJobDiakmunka" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>
</div>
-->

<?php if(!empty($settings['facebook_link']) || $settings['facebook_link'] != ''){ ?>
	<div class="fb-page" data-href="<?php echo $settings['facebook_link']; ?>" data-width="260" data-height="300" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false" data-show-posts="false"></div>
<?php }?>
  