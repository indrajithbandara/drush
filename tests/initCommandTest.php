<?php

namespace Unish;

/**
 *  Test to see if the `drush init` command does the
 *  setup that it is supposed to do.
 *
 *  @group base
 */
class initCommandCase extends CommandUnishTestCase {

  function testInitCommand() {
    // Call `drush core-init`
    $this->drush('core-init', array(), array('add-path' => TRUE, 'yes' => NULL, 'no-ansi' => NULL));
    $logOutput = $this->getErrorOutput();
    // First test to ensure that the command claimed to have made the expected progress
    $this->assertContains("Copied Drush bash customizations", $logOutput);
    $this->assertContains("Updated bash configuration file", $logOutput);
    // Next we will test to see if there is evidence that those
    // operations worked.
    $home = getenv("HOME");
    $this->assertFileExists("$home/.drush/drushrc.php");
    $this->assertFileExists("$home/.drush/drush.bashrc");
    $this->assertFileExists("$home/.bashrc");

    // Check to see if the .bashrc file sources our drush.bashrc file,
    // and whether it adds the path to self::getDrush() to the $PATH
    $bashrc_contents = file_get_contents("$home/.bashrc");
    $this->assertContains('drush.bashrc', $bashrc_contents);

    $this->assertContains(realpath(dirname(self::getDrush())), $bashrc_contents);
  }
}
