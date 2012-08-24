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
 * Multinumerical question definition class.
 *
 * @package    qtype
 * @subpackage multinumerical
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * Represents a Multinumerical question.
 *
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_multinumerical_question extends question_graded_automatically {

    public function get_expected_data() {
        $return = array();
        foreach ($this->get_parameters() as $parameter) {
            $return['answer_'.$parameter] = PARAM_RAW_TRIMMED;
        }
//        echo '<pre>'; print_r($return); echo '</pre>';
        return $return;
    }

    public function summarise_response(array $response) {
        $return = array();
        foreach ($this->get_parameters() as $parameter) {
            $return[] = $parameter . ' = ' . $response['answer_'.$parameter];
        }
        return implode(' ; ', $return);
    }

    public function get_parameters() {
        $parameters = explode(',', $this->parameters);
        foreach ($parameters as &$parameter) {
            $parameter = trim($parameter);
            if (preg_match('/^(\w+)/', $parameter, $matches)) {
                $parameter = $matches[1];
            }
        }
//        echo '<pre>' . print_r($parameters, true) . '</pre>'; die();
        return $parameters;
    }

    private function get_conditions() {
        $conditions = explode("\n", $this->conditions);
        foreach ($conditions as &$condition) {
            $condition = trim($condition);
        }
        return $conditions;
    }

    private function get_feedbackperconditions() {
        $feedbackperconditions = explode("\n", $this->feedbackperconditions);
        foreach ($feedbackperconditions as &$feedbackpercondition) {
            $feedbackpercondition = trim($feedbackpercondition);
        }
        return $feedbackperconditions;
    }

    public function compute_feedbackperconditions(array $response) {
    	global $CFG;
    	$score = 0;
    	$feedbackperconditions = $this->get_feedbackperconditions();
    	$conditionsfullfilled = array();
    	$feedbackperconditions_computed = array();
     	$conditions = $this->get_conditions();
    	foreach ($conditions as $conditionid => $condition) {
    		$feedbackforthiscondition = explode('|', $feedbackperconditions[$conditionid]);
    		$values = '';
    		if ($this->check_condition($condition, $values, $response)) {
    			$score++;
    			$conditionsfullfilled[] = 1;
    			if (strlen(trim($feedbackforthiscondition[0])) > 0) {
    				if ($this->usecolorforfeedback) {
	    				$feedbackperconditions_computed[$conditionid] = '<span style="color:#090">';
	    				$feedbackperconditions_computed[$conditionid] .= (preg_match('/(usepackage{color})/', $CFG->filter_tex_latexpreamble)) ? (preg_replace('/(.*)\$\$(.*)\$\$(.*)/', '${1}\$\$\\textcolor{green}{${2}}\$\$${3}', $feedbackforthiscondition[0])) : ($feedbackforthiscondition[0]);
	    				$feedbackperconditions_computed[$conditionid] .= '</span>';
    				}
    				else {
    					$feedbackperconditions_computed[$conditionid] = $feedbackforthiscondition[0];
    				}
     			}
    			else {
    				unset($feedbackperconditions[$conditionid]);
    			}
    		}
    		else {
    			$conditionsfullfilled[] = 0;
    			if (isset($feedbackforthiscondition[1]) && strlen(trim($feedbackforthiscondition[1])) > 0) {
    				if ($this->usecolorforfeedback) {
	    				$feedbackperconditions_computed[$conditionid] = '<span style="color:#f00">';
	    				$feedbackperconditions_computed[$conditionid] .= (preg_match('/(usepackage{color})/', $CFG->filter_tex_latexpreamble)) ? (preg_replace('/(.*)\$\$(.*)\$\$(.*)/', '${1}\$\$\\textcolor{red}{${2}}\$\$${3}', $feedbackforthiscondition[1])) : ($feedbackforthiscondition[1]);
	    				$feedbackperconditions_computed[$conditionid] .= '</span>';
    				}
    				else {
    					$feedbackperconditions_computed[$conditionid] = $feedbackforthiscondition[1];
    				}
    			}
    			else {
    				unset($feedbackperconditions[$conditionid]);
    			}
    		}
    		if ($this->displaycalc && isset($feedbackperconditions[$conditionid]) && (!preg_match('/^\s*([A-Za-z]+\d*)\s*[=|<|>].*$/', $condition, $matches) || $this->displaycalc == 1)) {
    		    $feedbackperconditions_computed[$conditionid] .= '<ul><li>'.$values.'</li></ul>';
    		}
    	}
    	$this->computedfeedbackperconditions = implode('<br /><br />', $feedbackperconditions_computed);
    	return $score;
    }

    private function check_condition($condition, &$values, $response) {
//        echo '<pre>R : '; print_r($response); echo '</pre>';
        global $CFG;
        $values = '';
        $interval = false;
        $operators = array('<=', '>=', '<', '>', '='); // ND : careful with operators relative positions here, see following foreach()
        foreach ($operators as $operator) {
            $operatorposition = strpos($condition, $operator);
            if ($operatorposition !== false) {
                $conditionsides = explode($operator, $condition);
                $left = trim($conditionsides[0]);
                $right = trim($conditionsides[1]);
                break;
            }
        }
        include_once($CFG->dirroot.'/question/type/multinumerical/math.class.php');
        $math = new EvalMath();
        $math->suppress_errors = true;
        // filling variables :
        foreach ($response as $param => $value) {
        	// EvalMath n'aime pas les noms de variables avec majuscules
        	$math->evaluate(strtolower(substr($param, 7)).'='.$value);
        }
        $leftvalue = $math->evaluate($left);
        if ($operator == '=') {
            $operator = '==';
            $matches = array();
            if (preg_match('/^\s*([A-Z]*[a-z]*\w*)\s*=\s*([\[|\]])(.+);(.+)([\[|\]])$/', $condition, $matches)) {
                    $interval = true;
                    $operator = "";
                    $rightvalue = ($matches[2] == "[") ? (">=") : (">");
                    $val1 = $math->evaluate($matches[3]);
                    $val2 = $math->evaluate($matches[4]);
                    $rightvalue .= $val1 . " && " . $leftvalue;
                    $rightvalue .= ($matches[5] == "]") ? ("<=") : ("<");
                    $rightvalue .= $val2;
            }
        }
        if (!$interval) {
            $rightvalue = $math->evaluate($right);
            $values .= number_format($leftvalue,2,'.',"'").' '.$operator.' '.number_format($rightvalue,2,'.',"'");
        }
        else {
            $values .= $leftvalue.' = '.$matches[2].number_format($val1,3,'.',"'").';'.number_format($val2,3,'.',"'").$matches[5];
        }
//die(__LINE__.' : return('.$leftvalue.$operator.$rightvalue.');');
    	if (strlen($leftvalue) > 0 && isset($operator) && strlen($rightvalue) > 0 && eval('return('.$leftvalue.$operator.$rightvalue.');')) {
    	    $valuesspan = '<span';
    	    $valuesspan .= ($this->usecolorforfeedback) ? (' style="color:#090"') : ('');
    	    $valuesspan .= '>'.get_string('conditionverified', 'qtype_multinumerical').' : '.$values.'</span>';
    	    $values = $valuesspan;
    	    //if ($USER->id == 1064) { echo "\ntrue</pre>"; }
          return true;
        }
        $valuesspan = '<span';
        $valuesspan .= ($this->usecolorforfeedback) ? (' style="color:#f00"') : ('');
        $valuesspan .= '>'.get_string('conditionnotverified', 'qtype_multinumerical').' : '.$values.'</span>';
        $values = $valuesspan;
        //if ($USER->id == 1064) { echo "\nfalse : ".$leftvalue.$operator.$rightvalue."</pre>"; }
        return false;
    }

    public function is_complete_response(array $response) {
        foreach($this->get_parameters() as $param) {
            if (!array_key_exists('answer_'.$param, $response) || (!$response['answer_'.$param] && $response['answer_'.$param] !== '0')) {
//                die('param missing : '.$param);
                return false;
            }
        }
        return true;
    }

    public function get_validation_error(array $response) {
        if ($this->is_gradable_response($response)) {
            return '';
        }
        return get_string('pleaseenterananswer', 'qtype_multinumerical');
    }

    public function is_same_response(array $prevresponse, array $newresponse) {
        foreach($this->get_parameters() as $param) {
            if (!question_utils::arrays_same_at_key_missing_is_blank($prevresponse, $newresponse, 'answer_'.$param)) {
                return false;
            }
        }
        return true;
    }

    public function check_file_access($qa, $options, $component, $filearea,
            $args, $forcedownload) {
        if ($component == 'question' && $filearea == 'answerfeedback') {
            $currentanswer = $qa->get_last_qt_var('answer');
            $answer = $qa->get_question()->get_matching_answer(array('answer' => $currentanswer));
            $answerid = reset($args); // itemid is answer id.
            return $options->feedback && $answerid == $answer->id;

        } else if ($component == 'question' && $filearea == 'hint') {
            return $this->check_hint_file_access($qa, $options, $args);

        } else {
            return parent::check_file_access($qa, $options, $component, $filearea,
                    $args, $forcedownload);
        }
    }

    public function get_correct_response(){
        $parameters = $this->get_parameters();
        $response = array();
        foreach ($parameters as $parameter) {
            $response['answer_'.$parameter] = get_string('noncomputable','qtype_multinumerical');
        }
        return $response;
    }

    public function grade_response(array $response){
    	$score = $this->compute_feedbackperconditions($response);
        $fraction = $score / sizeof($this->get_conditions());
//        echo '<pre> SCORE : '; print_r($score); echo '</pre>';
//        echo '<pre> nbConditions : '; print_r($this->get_conditions()); echo '</pre>';
//        echo '<pre> FRACTION : '; print_r($score / sizeof($this->get_conditions())); echo '</pre>';
//        echo '<pre> FRACTION : '; print_r($fraction); echo '</pre>';
        if ($this->binarygrade) {
            $fraction = floor($fraction);
        }
//        die('f='.$fraction);
        return array($fraction, question_state::graded_state_for_fraction($fraction));
    }
}
