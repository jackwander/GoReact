    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                  <label class="block">
                    <input type="text" wire:model.debounce.500ms="search" class="form-input mt-1 block w-full rounded" required="" placeholder="Search">
                  </label>
                  <div class="flex flex-wrap">
                    <div class="flex flex-wrap px-6">
                      @foreach($files as $file)
                      <div class="w-full lg:w-1/2 md:px-4 lg:px-6 py-5">
                        <div class="bg-white border hover:shadow-xl">
                          <a href="{{route('view',$file->id)}}" class="">
                            @if ($file->mime == 'jpg')
                              <img src="{{ \Storage::disk('s3')->url($file->url) }}" alt=""class="h-56 w-full border-white border-8">
                            @endif

                            @if ($file->mime == 'mp4')
                              <video controls="" class="h-56 w-full border-white border-8" poster="{{URL::asset('play-button.png')}}">
                              </video>
                            @endif
                            @if ($file->mime == 'pdf')
                              <img src="{{URL::asset('pdf.svg')}}" alt=""class="h-56 w-full border-white border-8">
                            @endif
                          </a>
                          <div class="px-4 py-4 md:px-10">
                            <h1 class="font-bold text-lg">
                              {{$file->filename}}
                            </h1>
                            <p class="py-4">
                              {{$file->description}}
                            </p>
                            <div class="flex flex-wrap pt-8">
                              <div class="w-full md:w-1/3 text-sm font-medium">
                                {{date('F d, Y', strtotime($file->created_at))}}
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      @endforeach
                    </div>
                    {{ $files->links() }}
                  </div>
                </div>
            </div>
        </div>
    </div>
