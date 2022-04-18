@section('seo')
<meta property='og:title' content='{{ $result->title }}'/>
<meta property='og:image' content='//media.example.com/ 1234567.jpg'/>
<meta property='og:description' content='{{ truncate($result->description) }}'/>
<meta property='og:url' content='{{ $url }}' />
@endsection

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $result->title }}
        </h2>

        <div class="flex justify-between mt-3">

            <span>
                <div class="text-xs flex py-2 px-0 align-middle">
                    <span class="fa fa-user fa-light "></span>
                    <span class="ml-1"> {{ $result->user->name }}</span>
                </div>
            </span>

            <span>
                <div class="text-xs flex py-2 px-0 align-middle">
                    <span class="fa fa-clock fa-light "></span>
                    <span class="ml-1">{{  getPostReadTime($result->description)}}</span>
                </div>
            </span>

            <span>
                <div class="text-xs flex py-2 px-0 inline-block align-middle">
                    <span class="fa fa-clock fa-light "></span>
                    <span class="ml-1">{{  blogListingReadableTime($result->published_at) }}</span>
                </div>
            </span>


        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class=" bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 pt-3 pb-16 bg-white border-b border-gray-200 ">

                    <div class="text-justify mt-3">
                        <p>{{ $result->description}} </p>
                    </div>

                    <div class="mt-8">
                        <x-share-links title="{{ $result->title}}" url="{{ $url}}"  />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
