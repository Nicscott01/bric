<?php
/**
 *  Cookie banner template
 * 
 * 
 */
?>
<div id="cb-cookie-banner" class="alert alert-secondary text-center text-primary mb-0" role="alert">
  &#x1F36A; This website uses cookies to ensure you get the best experience on our website.
  <a href="<?php echo get_privacy_policy_url(); ?>" target="blank">Learn More</a>
  <button type="button" class="btn btn-primary btn-sm ms-3" onclick="window.cb_hideCookieBanner()">
    I Accept
  </button>
</div>
<!-- End of Cookie Banner -->