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
 * Strings for component 'qtype_multinumerical', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package    qtype
 * @subpackage multinumerical
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Multinumerical (BETA)';
$string['answer'] = 'Votre réponse : {$a}';
$string['pleaseenterananswer'] = 'Please enter an answer';

$string['pluginname_link'] = 'question/type/multinumerical';
$string['pluginnameadding'] = 'Adding a Multinumerical question';
$string['pluginnameediting'] = 'Editing a Multinumerical question';
$string['pluginnamesummary'] = 'Allows to create a question whose correct answers may be many, governed by equations or inequations.';
$string['parameters'] = 'Parameters';
$string['conditions'] = 'Constraints';
$string['feedbackperconditions'] = 'Per constraint feedback';
$string['badfeedbackperconditionsyntax'] = 'Each line must be of the form : &quot;Feedback if condition true | Feedback if condition false&quot;';
$string['badnumfeedbackperconditions'] = 'The number of per constraint feedbacks can not be higher than the number of constraints';
$string['noncomputable'] = '(correct answers not computable automatically)';
$string['onlyforcalculations'] = 'Only for calculations';
$string['usecolorforfeedback'] = 'Use color for per constraint feedback';
$string['binarygrade'] = 'Grade calculation';
$string['gradebinary'] = 'All or nothing';
$string['gradefractional'] = 'Fractional';
$string['qtypeoptions'] = 'Multinumerical question type specific options';
$string['conditionnotverified'] = 'Unverified constraint';
$string['conditionverified'] = 'Verified constraint';
$string['displaycalc'] = 'Display calculation result';
$string['helponquestionoptions'] = 'For more information on this question type and the behaviour of the following options, please click the help button at the top of this form.';
$string['pluginname_help'] = <<<EOF
<h2>Principe de fonctionnement</h2>
<p>Une question de type &quot;multinumérique&quot; permet de poser une question dont
l'étudiant doit calculer la réponse, cette réponse étant composée de plusieurs
paramètres (numériques).</p>
<p><strong>Exemple de question :</strong> entrer <span style="font-family:monospace">X</span>
et <span style="font-family:monospace">Y</span> tels que</p>
<ul><li>X + Y &lt; 20</li><li>X * Y &gt; 35</li></ul>
<p>Il existe <em>a priori</em> plusieurs solutions à ce problème, et n'importe quelle
réponse répondant à ces deux conditions devrait pouvoir être considérée comme correcte.</p>

<p>Ce type de question permet donc de définir les paramètres demandés (ici,
<span style="font-family:monospace">X</span> et <span style="font-family:monospace">Y</span>)
et les contraintes auxquelles ces paramètres doivent répondre.</p>

<h2>Utilisation par l'enseignant</h2>

<ul>
	<li>Définir les paramètre standards de la question (catégorie, titre, texte de
	la question, poids, pénalité, feedback général).</li>
</ul><ul>
	<li>Entrer les paramètres à demander, séparés par des virgules (dans notre exemple,
	on entrerait &quot;<span style="font-family:monospace">X,Y</span>&quot;).<br />
	<strong>Note :</strong> on peut entrer des unités après chaque paramètre, soit par exemple
	&quot;<span style="font-family:monospace">X [m],Y [h]</span>&quot; (mettre un espace
	entre le nom du paramètre et son unité).</li>
</ul><ul>
	<li>Entrer les contraintes, séparées par un retour à la ligne ; dans notre exemple,
	on entrerait : <pre>X + Y &lt; 20
X * Y &gt; 35</pre>(les lignes vides seront ignorées)
    <p>Les opérateurs disponibles sont : <ul>
        <li>&quot;<span style="font-family:monospace">=</span>&quot; (égalité)</li>
        <li>&quot;<span style="font-family:monospace">&lt;</span>&quot; (inférieur à)</li>
        <li>&quot;<span style="font-family:monospace">&lt;=</span>&quot; (inférieur ou égal à)</li>
        <li>&quot;<span style="font-family:monospace">&gt;</span>&quot; (supérieur à)</li>
        <li>&quot;<span style="font-family:monospace">&gt;=</span>&quot; (supérieur ou égal à)</li>
        <li>l'opérateur d'intervalle :
            <pre><span style="font-family:monospace">X = [1;5]</span></pre> signifie que
            <span style="font-family:monospace">X</span> doit se trouver entre 1 et 5 compris, et
            <pre><span style="font-family:monospace">X = ]1;5[</span></pre> signifie que
            <span style="font-family:monospace">X</span> doit se trouver entre 1 et 5 non compris,
            (se référer à la définition des intervalles en mathématiques).
        </li>
    </ul></p></li>
</ul><ul>
	<li>Entrer si désiré un feedback pour chaque contrainte. Dans notre exemple, on
	pourrait par exemple entrer :
    <pre>OK : X + Y &lt; 20 | Non, X + Y &gt;= 20 !
OK : X * Y &gt; 35 | Non, X + Y &lt;= 35 !</pre>
    <p>Cette syntaxe permet d'afficher un feedback relatif à chaque contrainte, et pour
chaque feedback d'afficher un texte différent selon que la contrainte est remplie
ou non. Les lignes ne correspondant à aucune condition sont ignorées.</p>
    </li>
</ul><ul>
    <li>L'option &quot;Afficher le résultat du calcul&quot; permet de définir si le feedback
    par contrainte doit contenir une évaluation numérique de chacune des contraintes.
    L'affichage de cette évaluation numérique n'a lieu que si le feedback par contrainte
    (positif ou négatif, suivant la réponse de l'apprenant) contient du texte.<br />
    Si on choisit ici &quot;Seulement pour les calculs&quot;, ceci ne s'affichera pas pour les
    contraintes non calculées (de type <span style="font-family:monospace">X&nbsp;>&nbsp;5</span>), afin
    de ne pas donner la solution à l'apprenant.</li>
</ul><ul>
    <li>L'option &quot;Calcul des points&quot; permet de définir si une réponse
    partiellement correcte (remplissant une partie des contraintes seulement) doit obtenir
    une partie des points, ou aucun point.</li>
</ul>

<h2>Utilisation par l'apprenant</h2>

<p>L'apprenant se voit présenté le texte de la question, et un nombre de champs
égal au nombre de paramètres demandés.</p>
<p>Il entre alors une valeur dans chaque champ, et se voit alors présenté un feedbcak
(positif ou négatif) pour chaque contrainte (si ceux-ci ont été définis par l'enseignant).</p>
<p>Le feedback général s'affiche quelle que soit la réponse.</p>
EOF;
