<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class=" bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="px-6 pt-3 pb-16 bg-white border-b border-gray-200 ">
                    <div class="grid gap-4 md:grid-cols-3">
                        @forelse ($results as $item)
                        <div class="card mt-8 bg-gray-100 hover:bg-gray-200 py-5 px-4 rounded ">
                            <div class="font-bold">
                                <span class="text-xl ">{{ truncate($item->title, 50)}}</span>
                            </div>

                            <div class=" flex justify-between">
                                <div class="text-xs flex py-2 px-0">
                                    <span class="fa fa-user fa-light "></span>
                                    <span class="ml-1"> {{ getUserFirstname($item->user->name) }}</span>
                                </div>


                                <div class="text-xs flex py-2 px-0 align-middle">
                                    <span class="fa fa-clock fa-light "></span>
                                    <span class="ml-1">{{  getPostReadTime($item->description)}}</span>
                                </div>

                                <div class="text-xs flex py-2 px-0">
                                    <span class="fa fa-clock fa-light "></span>
                                    <span class="ml-1">{{  blogListingReadableTime($item->published_at) }}</span>
                                </div>
                            </div>

                            <p class="text-jusify">{{ truncate($item->description) }}</p>

                            <x-link-button href="{{ route('post-details', $item->slug) }}" class="mt-3 align-bottom">
                                Read more &rarr;
                            </x-link-button>
                        </div>
                        @empty
                        <div class="justify-center py-4">
                            <h2 class="text-3xl">No Post(s) Found.</h2>
                        </div>
                        @endforelse
                    </div>

                    <div class="justify-center mt-5">
                        {{$results->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
