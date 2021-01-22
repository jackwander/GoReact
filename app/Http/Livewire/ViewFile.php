<?php

namespace App\Http\Livewire;

use App\Models\File;
use Livewire\Component;

class ViewFile extends Component
{
  public $file;
  public function mount($file_id) {
    $this->file = File::find($file_id);
  }

  public function render()
  {
      return view('livewire.view-file')->with(['file',$this->file]);
  }
}
