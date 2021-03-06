<header>
    <nav class="fixnav-sp view_sp">
        <div class="inner">
            <p class="logo"><a href="<?php echo url_for('@homepage')?>"><img src="/images/common/favicon.ico" alt=""><span>AutoSMS
							</span></a></p>
            <p class="obj_menuBtn menuopen"><a href="javascript: void(0);"><i class="fa fa-bars"
                                                                              aria-hidden="true"></i></a></p>
        </div>
    </nav><!-- /#fixnav-sp -->
    <div class="box_header">
        <div class="top-header view_pc-tab">
            <div class="container">
                <div class="col_r">
                    <ul class="lang">

                    </ul>
                </div>
            </div>
        </div>
        <!-- end top header -->

        <div class="header-main view_pc-tab">
            <div class="container">
                <div class="menu_global">
                    <h1 class="logo"><a href="<?php echo url_for('@homepage')?>" class="over"><img style="width: 90px;"
                                                                            src="/images/common/favicon.ico" alt="Báo bận thông minh">
                            <span style="padding-left: 20px !important;">AutoSMS</span></a></h1>
                    <ul class="nav_global">
                        <li><a href="<?php echo url_for('@homepage')?>">TRANG CHỦ</a></li>
                        <li><a href="<?php echo url_for('@about')?>">GIỚI THIỆU</a></li>
                        <li><a href="<?php echo url_for('@guide')?>">HƯỚNG DẪN SỬ DỤNG</a></li>
                        <li><a href="<?php echo url_for('@policy')?>">CHÍNH SÁCH DỊCH VỤ</a></li>
                        <li><a href="<?php echo url_for('@createProgram') ?>">TẠO LỊCH</a></li>
                        <li class="form_search">
                            <input type="text" placeholder="Search...">
                            <div class="search"></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <?php if(!empty($displayBanner)): ?>
        <div class="con_main">
            <div class="container">
                <div class="box_txt">
                    <p class="txt customMB">Dịch vụ Tin nhắn báo bận tự động – Auto SMS</p>
                    <h2>Thể hiện cá tính</h2>
                    <p class="txt">Tin nhắn báo bận thông minh</p>
                    <p style="width:278px;" class="btn"><a href="<?php echo url_for('@createProgram') ?>">Tạo lịch báo
                            bận <i class="ic"><img src="/images/common/ic_arrow.png" alt=""></i></a></p>
                </div>
                <p class="img"><img src="/images/home/img_main-pc.png" alt="" class="view_pc-tab"><img
                            src="/images/home/img_main-sp.png" alt="" class="view_sp"></p>
            </div>
        </div>
        <?php endif ?>
    </div>
</header>