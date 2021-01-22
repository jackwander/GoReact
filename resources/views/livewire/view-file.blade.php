<x-slot name="header">
  <h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __($file->filename) }}
  </h2>
</x-slot>

<div class="py-12">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <a href="{{url()->previous()}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Back</a>

      <div class="p-6 bg-white border-b border-gray-200 ">
        <center>
        @if ($file->mime == 'jpg')
          <img src="{{ \Storage::disk('s3')->url($file->url) }}" width="1280px" height="720px">
        @endif

        @if ($file->mime == 'mp4')
          <video width="1280" height="720" controls autoplay="">
                <source src="{{\Storage::disk('s3')->url($file->url) }}" type="video/mp4">
          </video>
        @endif
        @if ($file->mime == 'pdf')
          <object  width="816" height="720" type="application/pdf" data="{{\Storage::disk('s3')->url($file->url)}}?#zoom=85&scrollbar=0&toolbar=0&navpanes=0">
            <p>The PDF cannot be displayed.</p>
          </object>
        @endif
        </center>
        <br/>
      </div>

    </div>
  </div>
</div>
