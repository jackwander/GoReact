<div>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Upload File') }}
      </h2>
  </x-slot>

     {{-- The whole world belongs to you --}}

  <form wire:submit.prevent="save">
      <input type="file" wire:model="photo">

      @error('photo') <span class="error">{{ $message }}</span> @enderror

      <button type="submit">Save Photo</button>
  </form>
</div>
