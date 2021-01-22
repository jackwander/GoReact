<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ShowFiles extends Component
{
    public $files;

    public function mount($files) {
      $this->files = $files;
    }

    public function render()
    {
      return view('livewire.show-files');
    }
}
