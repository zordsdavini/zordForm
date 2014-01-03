<?php
function zordform_view_list($arguments){
    $forms = zordform_get_all_forms();
    echo '<table class="widefat fixed" cellspacing="0">
    <thead>
        <tr>
            <th width="20px">ID</th>
            <th>Name</th>
            <th>Formula</th>
            <th>Comment</th>
        </tr>
    </thead>
    <tbody>';
    if ($forms) {
        foreach ($forms as $form) {
            echo '<tr>
                <td>'.$form['id'].'</td>
                <td>
                    <strong>
                        <a href="admin.php?page=zordform&view=form&form_id='.$form['id'].'">'.$form['name'].'</a>
                    </strong>
                    <div class="row-action-div">
                    <div class="row-action">
                        <span class="edit">
                            <a href="admin.php?page=zordform&view=form&form_id='.$form['id'].'">Edit</a>
                            |
                        </span>
                        <span class="results">
                            <a href="admin.php?page=zordform&view=results&form_id='.$form['id'].'">Results</a>
                            |
                        </span>
                        <span class="trash zordform-delete">
                            <a href="#" ref="'.$form['id'].'">Delete</a>
                        </span>
                    </div>
                    </div>
                </td>
                <td>'.$form['formula'].'</td>
                <td>'.$form['comment'].'</td>
            </tr>';
        }
    } else {
        echo '<tr><td colspan="4">There is no forms</td></tr>';
    }
    echo '</tbody>
    <tfoot>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Formula</th>
            <th>Comment</th>
        </tr>
    </tfoot>
    </table>';
}

function zordform_view_edit($arguments){
    $form_id = $_REQUEST['form_id'];
    $form = zordform_get_form_by_id($form_id);

    echo '<div id="zordform-content">
        <div class="widget"><input class="wide-input" name="name" required value="'.($form ? $form['name'] : '').'" /></div>
        <div>Formula</div>
        <div class="help-text">Formula should be entered as ex. [[aaa]] * 2 + [[bbb]], where aaa and bbb is field names</div>
        <div class="widget"><input class="wide-input" name="formula" required value="'.($form ? $form['formula'] : '').'" /></div>
        <div>Comment</div>
        <div class="widget"><textarea class="wide-textarea" name="comment">'.($form ? $form['comment'] : '').'</textarea></div>

        <fieldset>
        <legend>Fields</legend>';

    if ($form) {
        $fields = zordform_get_fields_by_form_id($form_id);
        foreach ($fields as $field) {
            echo '<div ref="'.$field['id'].'">
                <table class="field" width="100%">
                    <tr> 
                        <th>Name</th>
                        <td><input name="field['.$field['id'].'][name]" value="'.$field['name'].'" required /></td>
                    </tr>
                    <tr> 
                        <th>Type</th>
                        <td><select name="field['.$field['id'].'][type]" required>
                            <option value="float" '.($field['type'] == 'float' ? 'selected' : '').'>Float</option>
                            <option value="int" '.($field['type'] == 'int' ? 'selected' : '').'>Int</option>
                            </select>
                        </td>
                    </tr>
                    <tr> 
                        <th>Default value</th>
                        <td>
                            <input name="field['.$field['id'].'][setted]" value="'.$field['setted'].'" required />
                            <span class="remove-field button" ref="'.$field['id'].'">Remove field</span>
                        </td>
                    </tr>
                </table><hr /></div>';
        }
    }
        
    echo '<span id="add-field" class="button">Add field</span></p>
        </fieldset>

        <p> <input type="submit" value="Save" class="button button-primary button-large" /></p>
    </div>';
}

function zordform_view_results($arguments){
    $form_id = $_REQUEST['form_id'];
    $form = zordform_get_form_by_id($form_id);
    if ($form) {
        echo '<table class="widefat fixed" cellspacing="0">
            <thead> <tr> 
                <th width="20px">ID</th>
                <th>Result</th>';

        $fields = zordform_get_fields_by_form_id($form_id);
        foreach ($fields as $field) {
            echo '<th>'.$field['name'].'</th>';
        }
        echo '  <th>Date</th>
                <th>IP</th>
            </tr> </thead> <tbody>';

        $results = zordform_get_results_by_form_id($form_id);
        foreach ($results as $result) {
            echo '<tr>
                <td>'.$result['id'].'</td>
                <td><b>'.$result['result'].'</b></td>';
            foreach ($fields as $field) {
                echo '<td>'.$result[$field['name']].'</td>';
            }
            echo '  <td>'.$result['date'].'</td>
                    <td>'.$result['ip'].'</td>
                </tr>';
        }

        echo '</tbody>
            <tfoot> <tr> 
                <th>ID</th>
                <th>Result</th>';
        foreach ($fields as $field) {
            echo '<th>'.$field['name'].'</th>';
        }
        echo '  <th>Date</th>
                <th>IP</th>
            </tr>
        </tfoot>
        </table>';
    }
}

?>
