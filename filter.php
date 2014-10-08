<?php
// This file is part of the lectora filter
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *  Media plugin filtering
 *
 *  This filter will replace any links to a media file with
 *  a media plugin that plays that media inline
 *
 * @package    filter
 * @subpackage lectora
 * @copyright  2014 Bas Brands, www.sonsbeekmedia.nl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Automatic media embedding filter class.
 *
 * It is highly recommended to configure servers to be compatible with our slasharguments,
 * otherwise the "?d=600x400" may not work.
 *
 * @package    filter
 * @subpackage lectora
 * @copyright  2004 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class filter_lectora extends moodle_text_filter {

    private $endofmodule;

    public function filter($text, array $options = array()) {
        global $CFG, $PAGE, $COURSE, $DB, $USER;


        $resourcecmid = $PAGE->context->instanceid;

        $thissection = 0;

        $hascompletion = 0;

        if ($DB->get_record('course_modules_completion', array('coursemoduleid' => $resourcecmid, 'userid' => $USER->id, 'viewed' => 1))) {
            $DB->set_field('course_modules_completion', 'viewed', 0, array('coursemoduleid' => $resourcecmid, 'userid' => $USER->id));
        }
        if ($COURSE->id > 1) {
            if (course_format_uses_sections($COURSE->format)) {
                $modinfo = get_fast_modinfo($COURSE->id);
                $sections = $modinfo->get_section_info_all();
                $course = course_get_format($COURSE)->get_course();
                $numsections = $course->numsections;
                $completioninfo = new completion_info($COURSE);
                for ($i = 0; $i <= $numsections; $i++) {
                    if (!isset($modinfo->sections[$i])) {
                        continue;
                    }
                    foreach ($modinfo->sections[$i] as $cmid) {
                        if ($cmid == $resourcecmid) {
                            $thissection = $i;
                            $thismod = $modinfo->cms[$cmid];
                            if ($completioninfo->is_enabled($thismod)) {
                                $hascompletion = 1;
                            }
                        }
                    }
                }
            }
        }

        $this->returnurl = new moodle_url('/course/view.php', array('id' => $COURSE->id, 'section' => $thissection));


        if (!is_string($text) or empty($text)) {
            // non string data can not be filtered anyway
            return $text;
        }

        if (stripos($text, 'Lectora Online') === false) {
            // Performance shortcut - if not Lectora, do nothing.
            return $text;
        }

        if (stripos($text, 'lectora_module_completed') && $hascompletion ) {
            $text = str_replace('alt=lectora_module_completed', 'onclick="location.href=\'' . $this->returnurl . '\'"', $text);
            $DB->set_field('course_modules_completion', 'viewed', 1, array('coursemoduleid' => $resourcecmid, 'userid' => $USER->id));
            $this->endofmodule = html_writer::link($this->returnurl, 'Einde Module', array('class' => 'lectorabtn'));
        } else {
            $this->endofmodule = '';
        }

        $endlink = '/<IMG\send_of_lectora_module.*>/Uis';


        $text = str_replace('alt=end_of_lectora_module', 'onclick="location.href=\'' . $this->returnurl . '\'"', $text);

        $body = '/<body\s.*>(.*)<\/body>/Uis';

        $newtext = preg_replace_callback($body, array($this,'body_inject'), $text);

        $head = '/<head>(.*)<\/head>/Uis';

        $content = preg_replace_callback($head, array($this,'head_inject'), $newtext);

        return $content;
    }

    private function end_link(array $matches) {
        return '<A href=' . $this->returnurl . '>' . $matches[0] . '</A>';
    }

    private function body_inject(array $matches) {
        global $CFG, $OUTPUT, $COURSE, $PAGE;

        $content = $matches[1];

        $backgroundlocation = $OUTPUT->pix_url('lectorabg', 'theme');

        return '<body style="background: url('.$backgroundlocation.') repeat-y scroll center 0 transparent;">
                    <div id="page-content-wrapper">
                    <nav role="navigation" class="navbar navbar-default">
                        <div class="container-fluid navbar-inner">
                            <a class="navbar-brand" href="'.$CFG->wwwroot.'"><img src="'.$OUTPUT->pix_url('logo', 'theme').'"></a>
                        </div>
                    </nav>
                    <div class="contentback">
                        <div class="lectorapage">
                            '. $content . '
                        </div>
                    </div>
                </body>';
    }

    private function head_inject(array $matches) {
        global $CFG;

        $content = $matches[1];

        return '<head>
                '. $content . '
                <link rel="stylesheet" type="text/css" href="'. $CFG->wwwroot .'/theme/malmberg/style/lectora.css">
                </head>';
    }
}
