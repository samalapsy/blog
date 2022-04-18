<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight justify-between">
                {{ __('Blog') }}
            </h2>
            <x-link-button href="{{route('dashboard.posts.create' )}}" class="mb-4">
                {{ __('Create New Post') }}
            </x-link-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class=" bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 ">

                    <div class="inline">

                        <div class="mt-5 md:mt-0 md:col-span-2">

                            <!-- Filter -->
                            <div class="mb-4">
                                @if($results)
                                <form method="GET" action="{{ route('dashboard.posts.index') }}">
                                    <div class="grid grid-cols-6 gap-4">
                                        <div class="col-span-3">
                                            <x-label for="title" :value="__('Sort By Publication Date')" />
                                            <select id="publication_date" name="publication_date"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="" @selected(isset($query_parameters['publication_date']) && $query_parameters['publication_date']=='' )> &ndash; Select &ndash;</option>
                                                <option value="asc" @selected(isset($query_parameters['publication_date']) && strtolower($query_parameters['publication_date'])=='asc' )>Ascending</option>
                                                <option value="desC" @selected(isset($query_parameters['publication_date']) && strtolower($query_parameters['publication_date'])=='desc' )>Descending</option>
                                            </select>
                                        </div>

                                        <div class="col-span-4">
                                            
                                            <x-link-button href="{{route('dashboard.posts.index' )}}" class="mb-4 inline-flex items-center px-4 py-2 border rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 disabled:opacity-25 transition ease-in-out duration-150 bg-red-500">
                                                {{ __('Reset Filter') }}
                                            </x-link-button>

                                            <x-button class="">
                                                {{ __('Filter') }}
                                            </x-button>
                                        </div>
                                    </div>
                                </form>
                                @endif
                            </div>

                            <table class="w-full">
                                <thead class="bg-gray-400 py-3">
                                    <tr class="py-5">
                                        <th class="w-20 py-3">#</th>
                                        <th>Title</th>
                                        <th>Publication Date</th>
                                        <th>Published At</th>
                                        <th>Status</th>
                                        <th>Created On</th>
                                        <th class="w-30">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($results as $key => $item)
                                    <tr>
                                        <td class="py-4">
                                            {{ ($results->currentPage() - 1)  * $results->perPage() + $loop->iteration }}
                                        </td>

                                        <td> {{ $item->title }}</td>
                                        <td> {{ $item->publication_date?->format('D, j M Y @ H:m:s ') ?? 'Scheduled' }}
                                        </td>
                                        <td> {{ $item->published_at?->diffForHumans() ?? 'Pending'}}</td>
                                        <td> {{ $item->is_published ? 'Published' : 'Pending' }}</td>
                                        <td> {{ $item->created_at?->diffForHumans() }}</td>
                                        <td>
                                            <a title="View Post Details"
                                                href="{{ route('dashboard.posts.show', $item) }}"><span
                                                    class="fa fa-eye p-2 border-2 rounded hover:bg-gray-400 mr-2"></span></a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td aria-colspan="6" colspan="6" class="text-center pt-4">
                                            <h2 class="text-3xl">No Post(s) Found.</h2>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="mt-4">
                                        <td colspan="6">{{ $results->links() }}</td>
                                    </tr>
                                </tfoot>
                            </table>



                        </div>
                    </div>
                </div>
            </div>
</x-app-layout>
