@extends('layouts.default')

@section('content')
    <div class="container flex flex-row max-w-full">

        <div class="row w-full">
            <div class="col-lg-12 col-xlg-12 col-md-12">
                <div
                    class="card w-full block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                    <div class="card-body profile-card">
                        <div class="row mt-4 text-center">
                            <?php if(isset($customerDataProfile)) { ?>
                            <div class="col-6">
                                <h4
                                    class="card-title mt-2 mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    {{ ucfirst($customerDataProfile['first_name']) }}
                                    {{ ucfirst($customerDataProfile['last_name']) }}
                                </h4>
                                <h6 class="card-subtitle font-normal text-gray-700 dark:text-gray-400">
                                    {{ $customerDataProfile['email'] ?? '' }}
                                </h6>
                                <h6 class="card-subtitle font-normal text-gray-700 dark:text-gray-400">
                                    {{ $customerDataProfile['phone'] ?? '' }}
                                </h6>

                                <div class="col-12 mt-2">
                                    <?php  foreach($customerDataProfile['addresses'] as $row){?>
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="card-text font-normal text-gray-700 dark:text-gray-400">Address
                                                :<?= $row['address1'] ?>
                                                <?= $row['address2'] ?>
                                                <?= $row['city'] ?>
                                                <?= $row['province'] ?>
                                                <?= $row['country'] ?></p>
                                        </div>
                                    </div>
                                    <?php break; ?>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="col-6 text-center justify-content-center mt-3">
                                <div class="col-12">
                                    <a href="#" class="btn btn-success mx-auto mx-md-0 text-black m-2">
                                        <h5> <i class="mdi me-2 mdi-note-plus"> </i> Add Products </h5>
                                    </a>
                                    <a href="#" class="btn btn-warning mx-auto mx-md-0 text-black m-2">

                                        <h5> <i class="mdi me-2 mdi-eye"> </i> View All Products </h5>
                                    </a>
                                    <a href="#" class="btn btn-info mx-auto mx-md-0 text-black m-2">

                                        <h5> <i class="mdi me-2 mdi-codepen"> </i> Boxed the Products </h5>
                                    </a>

                                </div>

                            </div>


                            <?php } else  {?>
                            <h4 class="card-title mt-2">
                                Data not available
                            </h4>
                            <?php } ?>




                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    @parent

    <script>
        // function(){}
    </script>
@endsection
