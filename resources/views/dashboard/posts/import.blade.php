<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Blog Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if(Session::get('message'))
                        <x-alert-panel message="{{ Session::get('message') }}"  type="{{ Session::get('type') }}" />
                    @endif

                    Please note that post imported cannot be updated or deleted. Your blog post would be imported from this url: <pre>{{ config('blog.remote_blog_post_import_url') }}</pre>.

                    <form method="POST" action="{{ route('dashboard.import-posts.store') }}">
                        @csrf
                        <div class="flex items-center justify-center mt-4">
                            <x-button class="ml-3" name="import_post" value="continue">
                                <span class="fa fa-download mr-2"></span> {{  __('Import Now') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
