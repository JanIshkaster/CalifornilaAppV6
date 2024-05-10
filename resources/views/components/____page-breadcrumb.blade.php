<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="page-title mb-2 p-0 text-left "><?=$title?></h3>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=(isset($subtitle))? $subtitle: ''?></li>
                    
                        <?php if(isset($subtitle_sub)){ ?> <li class="breadcrumb-item active" aria-current="page"><?php echo $subtitle_sub; } ?></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-md-6 col-4 align-self-center">

        </div>
    </div>
</div>