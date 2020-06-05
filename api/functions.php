<?php
function getAction()
{
    if (isset($_GET['action'])) {
        return $_GET['action'];
    } else {
        return 'read';
    }
}

function checkOptions($action)
{
    $options = ['create', 'read', 'update', 'delete', 'search'];
    if (in_array($action, $options)) {
        return true;
    } else {
        return false;
    }
}

function secureInput($data)
{
    return stripcslashes(trim(htmlspecialchars($data)));
}

function isDataEmpty($data)
{
    if (!empty($data)) {
        return true;
    } else {
        return false;
    }
}
function getRequestData($action, $data)
{
    switch ($action) {
        case 'create':
        case 'update':
            $user = array(
                'first_name' => secureInput($data->first_name),
                'last_name' => secureInput($data->last_name),
                'username' => secureInput($data->username),
                'email' => secureInput($data->email),
            );
            return json_encode($user);
            break;
        case 'delete':
            $user = array(
                'email' => secureInput($data->email),
            );
            return json_encode($user);
            break;
        case 'search':
            $data = array(
                'text' => secureInput($data->search_text),
            );
            return json_encode($data);
        default:
            # code...
            break;
    }
}
