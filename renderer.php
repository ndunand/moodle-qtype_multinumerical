<?php
// This file is part of Moodle - http://moodle.org/
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
 * Multinumerical question renderer class.
 *
 * @package    qtype
 * @subpackage multinumerical
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * Generates the output for Multinumerical questions.
 *
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_multinumerical_renderer extends qtype_renderer {
    public function formulation_and_controls(question_attempt $qa,
            question_display_options $options) {

        $question = $qa->get_question();

//echo '<pre>'.print_r($qa, true).'</pre>';
        $currentanswer = array();
        $inputattributes = array();
        $input = array();

        $questiontext = $question->format_questiontext($qa);
        $result = html_writer::tag('div', $questiontext, array('class' => 'qtext'));
        $result .= html_writer::start_tag('div', array('class' => 'ablock'));

        $parameters = explode(',', $question->parameters);
        foreach ($parameters as $parameter) {
            $parameter = trim($parameter);
            if (preg_match('/^(\w+).*(\[[^]]+\])$/', $parameter, $matches)) {
                $parameter_name = $matches[1];
                $unity = $matches[2];
            }
            else {
                $unity = '';
            }
            $inputname['param_'.$parameter_name] = $qa->get_qt_field_name('answer_'.$parameter_name);
            $currentanswer['param_'.$parameter_name] = $qa->get_last_qt_var('answer_'.$parameter_name);
            $inputattributes['param_'.$parameter_name] = array(
                'type' => 'text',
                'name' => $inputname['param_'.$parameter_name],
                'value' => $currentanswer['param_'.$parameter_name],
                'id' => $inputname['param_'.$parameter_name],
                'size' => 40,
            );
            if ($options->readonly) {
                $inputattributes['param_'.$parameter_name]['readonly'] = 'readonly';
            }
            $input['param_'.$parameter_name] = html_writer::empty_tag('input', $inputattributes['param_'.$parameter_name]);
            $result .= html_writer::tag('div', $parameter . ' : ', array('class' => 'paramname'));
            $result .= html_writer::tag('div', $input['param_'.$parameter_name], array('class' => 'answer'));
        }

        $result .= html_writer::end_tag('div');
        $result .= html_writer::tag('div', '', array('class' => 'clearer'));

        if ($qa->get_state() == question_state::$invalid) {
            $result .= html_writer::nonempty_tag('div',
                    $question->get_validation_error(array('answer' => $currentanswer)),
                    array('class' => 'validationerror'));
        }

        return $result;
    }

    public function specific_feedback(question_attempt $qa) {
        $question = $qa->get_question();
        $response = array();
        foreach ($question->get_parameters() as $param) {
            $response['answer_'.$param] = $qa->get_last_qt_var('answer_'.$param);
        }
        $question->compute_feedbackperconditions($response);
        return $question->computedfeedbackperconditions;
    }

    public function correct_response(question_attempt $qa) {
        return '';
    }
}