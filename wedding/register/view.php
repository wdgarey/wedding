<?php
include ("../includes/header.php");
?>
<header>
  <div class="header-content">
    <div class="header-content-inner">
      <h1 id="homeHeading">RSVP</h1>
      <hr />
<?php
if (isset($msg) && strlen($msg) > 0) {
?>
  <h3><span style="color:red; background-color:white;"><?php echo($msg); ?></span></h3>
<?php
}
?>
    </div>
<?php
if ($loggedIn) {
?>
      <section style="padding:0px;">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-6 col-md-6 text-center">
                    <div class="service-box regdiv">
                      <h3>Accept Invitation</h3>
                      <hr />
                      <form action="index.php?action=guestupdate" method="POST" onsubmit="return validateAcceptForm();">
                        <input type="hidden" name="keycode" value="<?php echo($keycode); ?>" />
                        <h4><span style="font-style:italic;font-weight:bold;"><?php echo($alias); ?></span>, graciously <span style="font-style:italic;">accepts</span>.</h4>
                        <br />
                        <label class="inlabel">Party's Size: </label><input type="number" name="partysize" id="partysize" min="1" max="10" step="1" style="color:black" class="inbox" placeholder="(# of attendees)" value="<?php echo($partySize); ?>" required />
                        <br />
                        <label class="inlabel">Email: </label><input type="email" name="email" id="email" style="color:black" placeholder="me@there.com" class="inbox" value="<?php echo($email); ?>" required />
                        <br />
                        <label class="inlabel">Phone: </label><input type="text" name="phone" id="phone" maxlength=10 style="color:black" class="inbox" placeholder="##########" value="<?php echo($phone); ?>" required />
                        <br />
                        <br />
                        <input type="submit" name="accept" value="Accept" style="color: black;" />
                      </form>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 text-center">
                    <div class="service-box regdiv">
                      <h3>Decline Invitation</h3>
                      <hr />
                      <form action="index.php?action=guestupdate" method="POST" onsubmit="return confirm('Are you sure you wish to decline?');">
                        <input type="hidden" name="keycode" value="<?php echo($keycode); ?>" />
                        <h4><span style="font-style:italic;font-weight:bold;"><?php echo($alias); ?></span>, kindly <span style="font-style:italic;">declines</span>.</h4>
                        <br />
                        <label class="inlabel"> </label>
                        <br />
                        <label class="inlabel"> </label>
                        <br />
                        <label class="inlabel"> </label>
                        <br />
                        <br />
                        <input type="submit" name="decline" value="Decline" style="color: black;" />
                      </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
} else {
?>
<form action="index.php?action=loginprocess" method="POST">
  <label>After logging-in you will be able to accept or decline the invitation.</label>
  <br />
  <br />
  <label>Keycode: </label><input type="number" name="keycode" style="color:black" value="<?php echo($keycode); ?>" required />
  <br />
  <br />
  <input type="submit" value="Login" style="color:black"></input>
</form>
<?php
}
?>
  </div>
</header>
<?php
include ("../includes/footer.php");
?>
