<?php

/**
 * Class to provide a calendar with additional data (tasks, milestones)
 *
 * @author Philipp Kiszka <info@o-dyn.de>
 * @name calendar
 * @package Collabtive
 * @version 2.0
 * @link http://collabtive.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class calendar {
    private $daysInMonth;
    private $daysLastMonth;
    private $weeksInMonth;
    private $firstDay;
    private $month;
    private $year;
    private $project;
    public $calendar;

    /**
     * Constructor
     *
     * @access protected
     */
    function __construct()
    {
        $this->calendar = array();
    }

    /**
     * Get the calendar array for a given month and year (and project)
     *
     * @param int $month Month without leading zero (e.g. 5 for March)
     * @param int $year Year in the format yyyy (e.g. 2008)
     * @param int $project Project ID (default: 0 => no project selected, so get the calendar for all projects)
     * @return array
     */
    public function getCal($month, $year, $project = 0)
    {
        $this->month = $month;
        $this->year = $year;
        // get number of days in the given and the previous month
        $this->daysInMonth = date("t", mktime(0, 0, 0, $month, 1, $year));
        $this->daysLastMonth = date("t", mktime(0, 0, 0, $month-1, 1, $year));
        // get first day of the month
        $this->firstDay = date("w", mktime(0, 0, 0, $month, 1, $year))-1;
        $tempDays = $this->firstDay + $this->daysInMonth;
        $this->weeksInMonth = ceil($tempDays / 7);

        $this->project = $project;

        return $this->buildCal();
    }

    /**
     * Populates the array of calendar information
     *
     * @return array
     */
    private function buildCal()
    {
        $counter = 0;
        $ms = new milestone();
        $tsk = new task();

        for($j = 0; $j < $this->weeksInMonth; $j++) {
            for($i = 0; $i < 7; $i++) {
                $counter++;
                $theday = $counter - $this->firstDay;

                if ($theday < 1) {
                    $this->calendar[$j][$i]["val"] = $this->daysLastMonth + $theday;
                    $this->calendar[$j][$i]["currmonth"] = 0;
                } elseif ($theday > $this->daysInMonth) {
                    $this->calendar[$j][$i]["val"] = $theday - $this->daysInMonth;
                    $this->calendar[$j][$i]["currmonth"] = 0;
                } else {
                    $miles = $ms->getTodayMilestones($this->month, $this->year, $theday, $this->project);
                    $milesnum = count($miles);

                    $tasks = $tsk->getTodayTasks($this->month, $this->year, $theday, $this->project);
                    $tasksnum = count($tasks);

                    $this->calendar[$j][$i] = array("val" => $theday,
                        "milestones" => $miles,
                        "milesnum" => $milesnum,
                        "tasks" => $tasks,
                        "tasksnum" => $tasksnum,
                        "currmonth" => 1
                        );
                }
            }
        }
        return $this;
    }
}

?>
