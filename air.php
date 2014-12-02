<?php
    require_once(dirname(__FILE__)."/common.php");
    if (!isset($loginSession))
        doUnauthenticatedRedirect();
?>
<div class="container padded">
<p>Welcome, <?php echo htmlentities($loginSession->authenticatedUser->username); ?>!</p>
<p>Please select an action from the menu at the top of the screen. &amp;</p>
</div>