<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form method="PUT" :default="$category" :action="route('categories.update', $category->id)" class="max-w-xl mx-auto p-4 bg-white rounded-md shadow-md">
                <x-splade-input name="name" :label="__('Name')" />
                <x-splade-input name="slug" :label="__('Slug')" class="mt-4" />
                <x-splade-submit class="mt-4" />
            </x-splade-form>
        </div>
    </div>
</x-app-layout>
