<?php
/*
 * Manages notification of the deadline tasks to users
 * This script act also as autocron but can be called from a server cron-job too
 * 
 * @authors Daniel Farinati and Marco Tomazzoni
 * @name notify.php
 * @version 1.0
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */

require_once("init.php");

// Retrive last check date
$sel = $conn->prepare("SELECT settingsValue FROM settings WHERE settingsKey = 'lastcheckdate'");
$selStmt = $sel->execute();
$LastCheckDate = $sel->fetchAll();

// Check last check date and, if older than a day, do what there is to do. Otherwise, do nothing.
if (time() - $LastCheckDate[0]['settingsValue']  > 86400) { // Last check date > 24H ago

  // Update last check date
  $sel = $conn->prepare("UPDATE settings SET settingsValue = '".time()."' WHERE settingsKey = 'lastcheckdate'");
  $selStmt = $sel->execute();

  $userid = array();
  
  // Who should be notified?
  $sel = $conn->prepare("SELECT id,email FROM user WHERE tasknotify = 'yes'");
  $selStmt = $sel->execute();
  while ($users = $sel->fetch()) {
      array_push($userid, $users);
  }
  
  //create objects
  $project = new project();
  $task = new task();
  
  for($i=0;$i<count($userid);$i++) { // cycle on users
      $myprojects = $project->getMyProjects($userid[$i]['id']);   // Give the user's projects
      for($j=0;$j<count($myprojects);$j++){   // Cycle on projects
          $sender = FALSE;
          $mailtext = $langfile["hello"].",<br/>".$langfile["taskduenotify"]." ".$myprojects[$j]['name'].":<br/><ul>";
          $mytask = $task->getProjectTasks($myprojects[$j]['ID']);    // return the tasks for every project
          sort($mytask);
          for($w=0;$w<count($mytask);$w++){   // Cycle on tasks
              $diffdate = $mytask[$w]['end'] - time();    // difference between now and the expiry date of the task
              $difflastdate = $mytask[$w]['end'] - $LastCheckDate[0][0];  // difference between task's expiry date and the date of last check
              if($difflastdate>0){
                  $remaining_days = round(($diffdate/(86400)),0); // calculating the remaining days
                  $remaining_days2 = round(($difflastdate/(86400)),0); //calculation of days left from last control
                  if ($remaining_days <= $settings["taskmailnotify"] AND $settings["taskmailnotify"] <= $remaining_days2){
                      $sender = TRUE; // set variable to send mail
                      $mailtext .= "<li>".$mytask[$w]['title']."</li>";  // prepare mail body
                  }
              }
          }
          if ($sender) {  // Check whether to send email
//              print("<br/>".$userid[$i]['email']."<br/><br/>".$mailtext);
              $themail = new emailer($settings);    
              $themail->send_mail($userid[$i]['email'],$langfile["notifyduedate"]." ".$myprojects[$j]['name'],$mailtext);   // send mail
          }
      }
  }
}
?>