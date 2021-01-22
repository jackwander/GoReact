<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class UploadFile extends Component
{
    use WithFileUploads;
    public $file,$name,$description;
    public $items;

    /**
     * Description: 
     * Date: 
     **/
    public function mount() {
      $initial = auth()->user()->files;
      $this->items = $initial;
    }

    /**
     * Description: 
     * Date: 
     **/
    public function updatedFile() {
      $this->validate([
        'file'=>'mimes:mp4,jpg,pdf',
        'name'=>'string',
        'description'=>'string',
      ]);
    }

    /**
     * Description: Save
     * Date: 
     **/
    public function save() {
      $this->validate([
        'file' => 'mimes:mp4,jpg,pdf',
        'name'=>'string',
        'description'=>'string',
      ]);
      $url = $this->file->store('files','s3');
      \Storage::disk('s3')->setVisibility($url,'public');
      $item = auth()->user()->files()->create([
        'filename'=>$this->name,
        'description'=>$this->description,
        'url'=>$url,
        's3_name'=>basename($url),
        'mime'=> $this->file->extension()
      ]);
      $this->items->prepend($item);
      $this->file=null;
      $this->name=null;
      $this->description=null;
      session()->flash('message', 'The file is successfully uploaded!');
    }

    public function render()
    {
        return view('livewire.upload-file',['items'=>$this->items]);
    }
}
