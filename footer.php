<footer class="footer" role="contentinfo">
	<div class="footer_light">
		<div class="footer_light_inner">
			<div class="row">
				<div class="small-6 columns">
					<?php get_template_part("inc/footer","socials"); ?>
				</div>
				<div class="small-6 columns">
					<?php get_template_part("inc/footer","call"); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="footer_dark">
		<div class="row">
			<div class="medium-6 columns">
				<?php get_template_part("inc/footer","credits"); ?>
			</div>
			<div class="medium-6 columns">
				<?php get_template_part("inc/footer","payments"); ?>
			</div>
		</div>
	</div>

	<div class="footer_support">
		<span>Design & Development by</span> <span class="benyehuda"></span>
	</div>

</footer>

</div><!-- /wrapper -->

<?php get_template_part("inc/popup","branches"); ?>

<?php wp_footer(); ?>

</body>

</html>
