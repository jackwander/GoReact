<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Upload File') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <form wire:submit.prevent="save">
            <div>
              <x-label for="name" :value="__('File Name')" />
              <x-input id="name" class="block mt-1" type="text" name="name" :value="old('name')" required autofocus/>
            </div>
            <div class="mt-4">
              <input type="file" wire:model="file">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
