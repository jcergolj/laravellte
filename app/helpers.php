<?php

/**
 * Flash success message.
 *
 * @param  string  $message
 * @return void
 */
function msg_success($message)
{
    session()->flash('flash', ['message' => $message, 'level' => 'success']);
}

/**
 * Flash error message.
 *
 * @param  string  $message
 * @return void
 */
function msg_error($message)
{
    session()->flash('flash', ['message' => $message, 'level' => 'danger']);
}
