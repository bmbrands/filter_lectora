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
        global $CFG, $PAGE;

        if (!is_string($text) or empty($text)) {
            // non string data can not be filtered anyway
            return $text;
        }

        if (stripos($text, 'Lectora Online') === false) {
            // Performance shortcut - if not Lectora, do nothing.
            return $text;
        }

        if (stripos($text, 'end_of_lectora_module')) {
            $this->endofmodule = html_writer::tag('a', 'Einde Module', array('class' => 'lectorabtn'));
        } else {
            $this->endofmodule = '';
        }

        $body = '/<body\s.*>(.*)<\/body>/Uis';

        $newtext = preg_replace_callback($body, array($this,'body_inject'), $text);

        $head = '/<head>(.*)<\/head>/Uis';

        $content = preg_replace_callback($head, array($this,'head_inject'), $newtext);

        return $content;
    }

    private function body_inject(array $matches) {
        global $CFG, $OUTPUT, $COURSE;

        $content = $matches[1];

        $backgroundlocation = $OUTPUT->pix_url('lectorabg', 'theme');

        return '<body style="background: url('.$backgroundlocation.') repeat-y scroll center 0 transparent;">
                    <div id="page-content-wrapper">
                    <nav role="navigation" class="navbar navbar-default">
                        <div class="container-fluid navbar-inner">
                            <a class="navbar-brand" href="'.$CFG->wwwroot.'"><img src="'.$OUTPUT->pix_url('logo', 'theme').'"></a>
                            '.$OUTPUT->user_menu().'
                            '.$this->endofmodule.'

                        </div>
                    </nav>
                    <div class="contentback">
                    </div>

                    <div class="lectorapage">
                        '. $content . '
                    </div>
                </body>';
    }

    private function head_inject(array $matches) {
        global $CFG;

        $content = $matches[1];

        return '<head>
                '. $content . '
                <link rel="stylesheet" type="text/css" href="'. $CFG->wwwroot .'/theme/vermeer/style/lectora.css">
                </head>';
    }
}
