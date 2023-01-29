<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-splade-form :action="route('posts.store')" class="max-w-xl mx-auto p-4 bg-white rounded-md shadow-md">
                <x-splade-select name="category_id" :options="$categories" :label="__('Category')" />
                <x-splade-input name="title" :label="__('Title')" class="mt-4" />
                <x-splade-input name="slug" :label="__('Slug')" class="mt-4" />
                <x-splade-textarea name="description" :label="__('Description')" autosize class="mt-4" />
                <x-splade-submit class="mt-4" />
            </x-splade-form>
        </div>
    </div>
</x-app-layout>
