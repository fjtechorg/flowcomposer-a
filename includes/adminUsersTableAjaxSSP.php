<?php
/**
 * Created by PhpStorm.
 * User: ABHISHEK
 * Date: 21-01-2019
 * Time: 14:44
 */
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

include_once __DIR__ . '/function.php';

// DB table to use
$table = 'smartbot_users';

// Table's primary key
$primaryKey = 'id';


if ($_SESSION['membership']['admin']===1) {

    //Where Conditions Array
    $whereAllConditions = [];


    // Array of database columns which should be read and sent back to DataTables.
    // The `db` parameter represents the column name in the database, while the `dt`
    // parameter represents the DataTables column identifier. In this case simple
    // indexes
    $columns = array(
        array(
            'db' => 'first_name',
            'dt' => 101
        ),
        array(
            'db' => 'last_name',
            'dt' => 102
        ),
        array(
            'db' => 'fb_id',
            'dt' => 103
        ),
        array(
            'db' => 'last_login_time',
            'dt' => 104
        ),
        array(
            'db' => 'last_login_ip',
            'dt' => 105
        ),
        array(
            'db' => 'id',
            'dt' => 0,
            'formatter' => function ($d, $row) {
                return '<input type="checkbox" value="'.$d.'">';
            }
        ),
        array(
            'db' => 'user_name',
            'dt' => 1,
            'formatter' => function ($d, $row) {
                if(!empty($row['fb_id'])){
                    $profileImage = 'https://graph.facebook.com/v3.2/'.$row['fb_id'].'/picture';
                }
                else{
                    $profileImage = '../images/user.png';
                }

                $image = '<img src="'.$profileImage.'" width="28px" height="28px">';
                $name = $row['first_name']." ".$row['last_name']." (".$d.")";
                return $image.' '.$name;
            }
        ),
        array(
            'db' => 'last_check',
            'dt' => 2,
            'formatter' => function ($d, $row) {
                $d = strtotime($d);
                return date("F j Y , g:i a", $d);
            }
        ),
        array(
            'db' => 'CONCAT_WS(" ",first_name,last_name)',
            'dt' => 4,
            'formatter' => function ($d, $row) {
                $userName =  $d." (".$row['user_name'].")";
                $edit = '<div class="btn-group">
                              <button data-toggle="dropdown" class="btn btn-default btn-sm dropdown-toggle" aria-expanded="false" style="background: white;color: grey;">Actions <span class="caret"></span></button>
                              <ul class="dropdown-menu pull-right">
                                 <li><a class="edit_profile" data-profile_id="'.$row['id'].'" >Edit User</a></li>
                                 <li><a class="edit_details" data-id="'.$row['id'].'" >Edit Details</a></li>
                                 <li><a class="user_login_history" data-name="' . $row[8] . '" data-id="' . $row['id'] . '" >Login History</a></li>
                                 <li><a class="takeover" data-profile_username="'.$row['user_name'].'" >Takeover</a></li>
                                 <li><a class="delete_profile" data-profile_id="'.$row['id'].'" data-profile_name="'.$userName.'" >Delete</a></li>
                              </ul>
                            </div>';
                return $edit;
            }
        ),
        array(
            'db' => 'email',
            'dt' => 3,
            'formatter' => function ($d, $row) {
                if($row['last_login_ip'] !== null) {
                    $time = date("F j Y , g:i a", $row['last_login_time']);
                    return $time . ' (' . $row['last_login_ip'] . ')';
                }
                else{
                    return '';
                }
            }
        ),
    );

    // SQL server connection information
    $sql_details = array(
        'user' => SB_DB_USER,
        'pass' => SB_DB_PASS,
        'db' => SB_DB_NAME,
        'host' => SB_DB_SERVER
    );

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * If you just want to use the basic configuration for DataTables with PHP
     * server-side, there is no need to edit below this line.
     */

    require __DIR__ . '/datatables/ssp.unescaped.class.php';
    echo( json_encode(
        SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $whereAllConditions)
    ));

}
