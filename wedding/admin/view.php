<?php
include ("../includes/header.php");
?>
<?php
if ($this->isLoggedIn()) {
?>
<section class="text-center">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-lg-offset-2 text-center">
        <h2 class="section-heading">Guests</h2>
        <hr class="light">
        <table border="1" class="datatable" style="margin:auto;">
          <caption style="text-align:left">
            <h4>Total number of RSVP'd attendees: <?php echo($attending); ?></h4>
            <h4>Total number of invitations: <?php echo($total); ?></h4>
            <h4>Total number of invitation responses: <?php echo($responded . " (" . $respondedPerc . "%)");?></h4>
            <h4>Total number of invitations accepted: <?php echo($accepted . " (" . $acceptedPerc . "%)");?></h4>
            <h4>Total number of invitations rejected: <?php echo($denied . " (" . $deniedPerc . "%)");?></h4>
          </caption>
          <tr class="datatable">
            <th class="datatable">Keycode</th>
            <th class="datatable">Name</th>
            <th class="datatable">Attending</th>
            <th class="datatable">Party Size</th>
            <th class="datatable">Email</th>
            <th class="datatable">Phone</th>
          </tr>
<?php
  foreach ($guests as $guest) {
?>
          <tr class="datatable">
            <td class="datatable"><?php echo(htmlspecialchars($guest['keycode'])); ?></td>
            <td class="datatable"><?php echo(htmlspecialchars($guest['alias'])); ?></td>
            <td class="datatable"><?php if (is_null($guest['attending'])) { echo('N/A'); } else if ($guest['attending'] == true) { echo('Yes'); } else { echo('No'); } ; ?></td>
            <td class="datatable"><?php echo(htmlspecialchars($guest['partysize'])); ?></td>
            <td class="datatable"><?php echo(htmlspecialchars($guest['email'])); ?></td>
            <td class="datatable"><?php echo(htmlspecialchars($guest['phone'])); ?></td>
          </tr>
<?php
  }
?>
        </table>
      </div>
    </div>
  </div>
</section>
<?php
} else {
?>
<section>
  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-lg-offset-2 text-center">
        <h2 class="section-heading">Login</h2>
        <hr class="light">
        <form action="index.php?action=loginprocess" method="POST">
          <label>Username: </label><input type="text" name="username" style="color:black" value="<?php echo($username); ?>" required />
          <br />
          <label>Password: </label><input type="password" name="password" style="color:black" value="<?php echo($password); ?>" required />
          <br />
          <br />
          <input type="submit" value="Login" style="color:black" />
<?php
if (isset($msg) && strlen($msg) > 0) {
?>
          <h3><span style="color:red; background-color:white;"><?php echo(htmlspecialchars($msg)); ?></span></h3>
<?php
}
?>
        </form>
      </div>
    </div>
  </div>
</section>
<?php
}
?>
<?php
include ("../includes/footer.php");
?>
