@extends('layouts.default')

@section('content')
    <div class="container flex flex-row max-w-full">

        {{-- START - CUSTOMER PAGE CONTENT --}}
        <div class="container-fluid w-full py-3 px-0">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Receive </th>
                            <th scope="col" class="px-6 py-3">Product </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                            foreach ($customers as $customer){
                        ?>
                        <tr class="border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4"><?= $customer['first_name'] ?> <?= $customer['last_name'] ?> </td>
                            <td class="px-6 py-4"><?= $customer['email'] ?> </td>
                            <td class="px-6 py-4"></td>
                            <td class="px-6 py-4"><a
                                    href="{{ route('openCustomersDataProfile', ['id' => $customer['id']]) }}"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">View</a>
                            </td>
                        </tr>
                        <?php    
                            } 
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        {{-- END - CUSTOMER PAGE CONTENT --}}

    </div>
@endsection

@section('scripts')
    @parent

    <script>
        // function(){}
    </script>
@endsection
