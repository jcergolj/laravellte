<?php

namespace App\Http\Livewire;

trait CanFlash
{
    /**
     * Dispatch browser danger flash event.
     *
     * @param  string  $message
     * @return void
     */
    protected function dispatchFlashDangerEvent($message)
    {
        return $this->dispatchBrowserEvent('flash', [
            'level' => 'alert-danger',
            'message' => $message,
        ]);
    }

    /**
     * Dispatch browser success flash event.
     *
     * @param  string  $message
     * @return void
     */
    protected function dispatchFlashSuccessEvent($message)
    {
        return $this->dispatchBrowserEvent('flash', [
            'level' => 'alert-success',
            'message' => $message,
        ]);
    }
}
