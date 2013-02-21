<?php
function xmldb_qtype_multinumerical_upgrade($oldversion = 0) {
    global $DB;
    $dbman = $DB->get_manager();

    $result = true;

    if ($result && $oldversion < 2012110100) {

        // Define key questionid (foreign) to be dropped form question_multinumerical
        $table = new xmldb_table('question_multinumerical');
        $key = new xmldb_key('questionid', XMLDB_KEY_FOREIGN, array('questionid'), 'questionid', array('id'));

        // Launch drop key questionid
        $dbman->drop_key($table, $key);

        // Rename field questionid on table question_multinumerical to NEWNAMEGOESHERE
        $table = new xmldb_table('question_multinumerical');
        $field = new xmldb_field('questionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'id');

        // Launch rename field questionid
        $dbman->rename_field($table, $field, 'question');



        // Define key question (foreign) to be added to question_multinumerical
        $table = new xmldb_table('question_multinumerical');
        $key = new xmldb_key('question', XMLDB_KEY_FOREIGN, array('question'), 'question', array('id'));

        // Launch add key questionid
        $dbman->add_key($table, $key);

        // multinumerical savepoint reached
        upgrade_plugin_savepoint(true, 2012110100, 'qtype', 'multinumerical');
    }
}
