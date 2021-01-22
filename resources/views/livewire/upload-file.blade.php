<x-slot name="header">
  <h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Upload File') }}
  </h2>
</x-slot>
<div class="py-12">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6 bg-white border-b border-gray-200">
        @if (session()->has('message'))
        <div class="flex items-center bg-green-500 text-white text-sm font-bold px-4 py-3 mb-6 rounded" role="alert">
          <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/></svg>
          <p>{{ session('message') }}</p>
        </div>
        @endif
        <div class="mb-12 p-6 bg-white border rounded-md shadow-xl">
          <form wire:submit.prevent="save">
            <div class="mb-3">
              <label class="block">
                <span class="text-gray-700">File Name</span>
                <input type="text" wire:model="name" class="form-input mt-1 block w-full"required="">
              </label>
              <label class="block">
                <span class="text-gray-700">Description</span>
                <input type="text" wire:model="description" class="form-input mt-1 block w-full"required="">
              </label>
              <label class="block">
                <span class="text-gray-700">File</span>
                <input type="file" wire:model="file" class="form-input mt-1 block w-full">
              </label>
              <div>
                @error('file') <span class="text-sm text-red-500 italic">{{ $message }}</span>@enderror
              </div>
              <div wire:loading wire:target="file" class="text-sm text-gray-500 italic">Uploading...</div>
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Upload</button>
          </form>
        </div>

        <div class="flex flex-wrap -mx-2">
          @foreach($items as $file)
          <div class="p-2">
            <div class="w-full h-full border">
              <div class="py-8 px-8 max-w-sm mx-auto bg-white rounded-xl shadow-md space-y-2 sm:py-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-6">
                <div class="text-center space-y-2 sm:text-left">
                  <div class="space-y-0.5">
                    <p class="text-lg text-black font-semibold">
                      <a href="{{route('view',$file->id)}}">{{$file->filename}}</a>
                    </p>
                    <p class="text-gray-500 font-medium">
                      File Type: {{$file->mime}}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
