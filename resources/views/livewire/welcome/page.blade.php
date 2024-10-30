<div>
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="card hovercard text-center">
                <div class="cardheader"></div>
                <div class="user-image">

                </div>
                <div class="info">
                    <div class="row">
                        <div class="col-sm-6 col-lg-4 order-sm-1 order-xl-0">

                        </div>
                        <div class="col-sm-12 col-lg-4 order-sm-0 order-xl-1 pb-5">
                            <h4>Welcome</h4>
                            <div class="user-designation">
                                <div class="title h3"><a target="_blank" href="">{{ Auth()->user()->name }}</a></div>
                                <div class="desc">
                                    {{ app('fun')->getCurrentRole()}}
                                </div>
                                <div>
                                    <i class="fa fa-envelope"></i> {{Auth()->user()->email}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 order-sm-2 order-xl-2">

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>