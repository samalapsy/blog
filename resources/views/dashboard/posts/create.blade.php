@section('head')


@endsection

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class=" justify-center">

                        <h4 class="bg-blue-300 text-gray-600 bold py-5 px-4">
                            <span class="fa fa-info-circle"></span> Kindly read and cross check these content becuase
                            edit priviledge is disabled.</h4>

                        <!-- Validation Errors -->
                        <x-auth-validation-errors class="mb-4" :errors="$errors" />

                        <form method="POST" action="{{ route('dashboard.posts.store') }}"
                            onsubmit="informUser(event); return;">
                            @csrf
                            <div class="mt-8">
                                <x-label for="title" :value="__('Title')" required />
                                <x-input id="title" class="block mt-1 w-full" type="text" name="title"
                                    :value="old('title')" required autofocus />
                            </div>

                            <div class="mt-8">
                                <x-label for="description" :value="__('Description')" required />
                                <x-textarea id="editor" rows="20" class="block mt-1 w-full" type="text"
                                    name="description" placeholder="Description" required autofocus value="{{ old('description') }}" />
                            </div>

                            <div class="mt-8">
                                <x-label for="publication_date" :value="__('Publication Date')" required />
                                <small class="italic text-blue-800 bold py-5"> Kindly read and cross check these content
                                    becuase edit priviledge is disabled.</small>

                                <x-input id="publication_date" class="block mt-1 w-full" type="datetime-local"
                                    datetime="DD-MM-YYYYThh:mm" name="publication_date" :value="old('publication_date')"
                                    required autofocus value="{{  old('publication_date')}}"
                                    onfocus="autoSetCurrentDateAndTime(event);" />
                            </div>

                            <div class="flex items-center justify-center mt-4">
                                <x-button class="ml-3 py-3">
                                    {{ __('Submit') }}
                                </x-button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function autoSetCurrentDateAndTime(e) {
            let now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            e.target.value = now.toISOString().slice(0, 16);
        }

        function informUser(e) {
            e.preventDefault();
            swal({
                    title: "Are you sure?",
                    text: "Once submitted, you will not be able to edit or delete this post!",
                    icon: "warning",
                    buttons: true,
                    buttons: ["Review Content", "Yes, Submit"],
                    dangerMode: false,
                })
                .then((submit) => {
                    if (submit) {
                        e.target.submit();
                    }
                });
        }

    </script>
    @endpush

</x-app-layout>
