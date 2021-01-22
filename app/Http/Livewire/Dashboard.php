<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
  public $search;

  public function render()
  {
    if(is_null($this->search)) {
      $files = auth()->user()->files()->latest()->paginate(4);
    } else {
      $files = auth()->user()->files()->where('filename','like',$this->search.'%')
      ->orWhere('description','like',$this->search.'%')
      ->latest()->paginate(4);
    }
    return view('livewire.dashboard',['files'=>$files]);
  }
}
