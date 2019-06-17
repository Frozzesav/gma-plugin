<!-- Нужно поменять value YES на meta -->

<?php
$spec = array(
	1 => 'Фортепиано', 
	2 => 'Народные инструменты', 
	3 => 'Струнно-смычковые инструменты', 
	4 => 'Духовые и ударные инструменты', 
	5 => 'Вокал', 
	6 => 'Класическая гитара', 
	7 => 'Композиторы', 
	8 => 'Музыковедение');

                

function my_user_field( $user ) {
?>
    <h3><?php _e('More About You'); ?></h3>
    <table class="form-table">
        
        <th>
                <?php _e('Specialty'); ?>
            </th>
            <tr>
            <td>             

                <!-- <label><input type="checkbox" name="test" value="" /> Test</label><br />
                <label><input type="checkbox" name="test" value="" /> Test</label><br /> -->


            </td>
        </tr>
 
    </table>
<?php 
}



function my_save_custom_user_profile_fields( $user_id ) {
    // if ( !current_user_can( 'edit_user', $user_id ) )
    //     return FALSE;

    // update_usermeta( $user_id, 'dealing', $_POST['dealing'] );
    // update_usermeta( $user_id, 'company', $_POST['company'] );

    global $spec;
    foreach($spec as $key => $value) {
        $code = "specialty_".$key;
        update_user_meta( $user_id, "specialty", [1,2,3] );
    }
}

add_action( 'show_user_profile', 'my_user_field' );
add_action( 'edit_user_profile', 'my_user_field' );
// add_action( 'personal_options_update', 'my_save_custom_user_profile_fields' );
// add_action( 'edit_user_profile_update', 'my_save_custom_user_profile_fields' );