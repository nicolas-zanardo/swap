<?php


// ROLE USER
function isConnected(): bool
{
    return isset($_SESSION['user']);
}
function isAdmin(): bool
{
    return (isConnected() && ($_SESSION['user']['status'] == 1));
}

// Function message Flash
/**
 * @description add messages
 * @param string $message
 * @param string $class
 */
function add_flash(string $message, string $class) {
    if(!isset($_SESSION['messages'][$class])) {
        $_SESSION['messages'][$class] = array();
    }
    $_SESSION['messages'][$class][] = $message;
}

/**
 * @description show message
 * @param null $option
 * @return string
 */
function show_flash($option=null): string
{
    $messages = '';
    if(isset($_SESSION['messages'])) {
        foreach(array_keys($_SESSION['messages']) as $keyName) {
            $messages .= '<div class="alert-dismissible fade show alert alert-' . $keyName . '" role="alert"><button type="button" class="btn-close mx-3" data-bs-dismiss="alert" aria-label="Close"></button>' . implode('<br>', $_SESSION['messages'][$keyName]) . '</div>';
        }
    }

    if($option == 'reset') {
        unset($_SESSION['messages']);
    }

    return $messages;
}
