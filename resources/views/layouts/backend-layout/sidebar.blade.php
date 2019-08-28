<section>
                
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info" style="height:85px;padding-top:20px">
                <div class="image" style="position:relative;float:left">
                    <img src="{{ asset('adminbsb/images/user.png') }}" width="48" height="48" alt="User" />
                </div>
                <div class="info-container" style="top:5px">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Session::get('fullname') }}</div>
                    <div class="email">{{ Session::get('roles') }}</div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="javascript:void(0);"><i class="material-icons">person</i>Profile</a></li>
                            <li role="seperator" class="divider"></li>
                            <li><a href="javascript:void(0);"><i class="material-icons">group</i>Followers</a></li>
                            <li><a href="javascript:void(0);"><i class="material-icons">shopping_cart</i>Sales</a></li>
                            <li><a href="javascript:void(0);"><i class="material-icons">favorite</i>Likes</a></li>
                            <li role="seperator" class="divider"></li>
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="material-icons">input</i>Sign Out</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            @php $firstsegment = explode('/', '-'.parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) @endphp
            <div class="menu">
                <ul class="list">
                    <li class="header">MAIN NAVIGATION </li>
                    <li class="{{ $firstsegment[1] == 'dashboard'? ' active ' : ''}}" >
                        <a href="{{ route('dashboard') }}">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="{{ $firstsegment[1] == 'dashboard'? ' active ' : ''}}" >
                        <a href="{{ route('dashboard') }}">
                            <i class="material-icons">home</i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="{{ $firstsegment[1] == 'master'? ' active ' : ''}}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">view_module</i>
                            <span>Master Data</span>
                        </a>
                        <ul class="ml-menu">
                                <li class="{{ !isset($firstsegment[2])? '' : $firstsegment[2] == 'promotion'? ' active ' : ''}}">
                                    <a href="{{ route('master.promotion') }}"><i class="material-icons" style="margin-top:-2px;margin-right:7px">link</i> Promotion</a>
                                </li>
                                <li class="{{ !isset($firstsegment[2])? '' : $firstsegment[2] == 'product'? ' active ' : ''}}">
                                    <a href="{{ route('master.product') }}"><i class="material-icons" style="margin-top:-2px;margin-right:7px">link</i> Product</a>
                                </li>
                        </ul>
                    </li>
                    <li class="{{ $firstsegment[1] == 'setup'? ' active ' : ''}}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">view_module</i>
                            <span>Setup Management</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="{{ !isset($firstsegment[2])? '' : $firstsegment[2] == 'event'? ' active ' : ''}}">
                                <a href="{{ route('event') }}"><i class="material-icons" style="margin-top:-2px;margin-right:7px">link</i> Event Calendar</a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ $firstsegment[1] == 'ticket'? ' active ' : ''}}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">view_module</i>
                            <span>Transaction</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="{{ !isset($firstsegment[2])? '' : $firstsegment[2] == 'order'? ' active ' : ''}}">
                                <a href="{{ route('ticket.order') }}">
                                    <i class="material-icons">receipt</i>
                                    <span>Ordered Ticket</span>
                                </a>
                            </li>
                            <li class="{{ (!isset($firstsegment[2]) and $firstsegment[1] == 'ticket')? ' active ' : ''}}">
                                <a href="{{ route('ticket') }}">
                                    <i class="material-icons">receipt</i>
                                    <span>Generated Ticket</span>
                                </a>
                            </li>
                            <li class="{{ !isset($firstsegment[2])? '' : $firstsegment[2] == 'scan'? ' active ' : ''}}">
                                <a href="{{ route('ticket.scan') }}">
                                    <i class="material-icons">receipt</i>
                                    <span>Scan Ticket </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ $firstsegment[1] == 'report'? ' active ' : ''}}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">view_module</i>
                            <span>Report & Monitoring</span>
                        </a>
                        <ul class="ml-menu">
                                <li class="{{ !isset($firstsegment[2])? '' : $firstsegment[2] == 'dashboard_penjualan'? ' active ' : ''}}">
                                    <a href="{{ route('dashboard.penjualan') }}"><i class="material-icons" style="margin-top:-2px;margin-right:7px">link</i> Dashboard</a>
                                </li>
                                <li class="{{ !isset($firstsegment[2])? '' : $firstsegment[2] == 'sales'? ' active ' : ''}}">
                                    <a href="{{ route('report.sales') }}"><i class="material-icons" style="margin-top:-2px;margin-right:7px">link</i> Report Sales</a>
                                </li>
                                <li class="{{ !isset($firstsegment[2])? '' : $firstsegment[2] == 'customer'? ' active ' : ''}}">
                                    <a href="{{ route('report.customer') }}"><i class="material-icons" style="margin-top:-2px;margin-right:7px">link</i> Daftar Customer</a>
                                </li>
                        </ul>
                    </li>
                    <li class="{{ $firstsegment[1] == 'access'? ' active ' : ''}}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">view_module</i>
                            <span>Access Management</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="{{ !isset($firstsegment[2])? '' : $firstsegment[2] == 'user_management'? ' active ' : ''}}">
                                <a href="{{ route('setup.user') }}"><i class="material-icons" style="margin-top:-2px;margin-right:7px">link</i> Users</a>
                            </li>
                            <li class="{{ !isset($firstsegment[2])? '' : $firstsegment[2] == 'org_management'? ' active ' : ''}}">
                                <a href="{{ route('setup.org') }}"><i class="material-icons" style="margin-top:-2px;margin-right:7px">link</i> Organizations</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <?php /*<div class="legal">
                <div class="copyright">
                    &copy; 2016 - 2017 <a href="javascript:void(0);">Rajonet Indonesia</a>.
                </div>
                <!--<div class="version">
                    <b>Version: </b> 1.0.5
                </div>-->
            </div> */ ?>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
        <!-- Right Sidebar -->
        <aside id="rightsidebar" class="right-sidebar">
            <ul class="nav nav-tabs tab-nav-right" role="tablist">
                <li role="presentation" class="active"><a href="#skins" data-toggle="tab">SKINS</a></li>
                <li role="presentation"><a href="#settings" data-toggle="tab">SETTINGS</a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
                    <ul class="demo-choose-skin">
                        <li data-theme="red" class="active">
                            <div class="red"></div>
                            <span>Red</span>
                        </li>
                        <li data-theme="pink">
                            <div class="pink"></div>
                            <span>Pink</span>
                        </li>
                        <li data-theme="purple">
                            <div class="purple"></div>
                            <span>Purple</span>
                        </li>
                        <li data-theme="deep-purple">
                            <div class="deep-purple"></div>
                            <span>Deep Purple</span>
                        </li>
                        <li data-theme="indigo">
                            <div class="indigo"></div>
                            <span>Indigo</span>
                        </li>
                        <li data-theme="blue">
                            <div class="blue"></div>
                            <span>Blue</span>
                        </li>
                        <li data-theme="light-blue">
                            <div class="light-blue"></div>
                            <span>Light Blue</span>
                        </li>
                        <li data-theme="cyan">
                            <div class="cyan"></div>
                            <span>Cyan</span>
                        </li>
                        <li data-theme="teal">
                            <div class="teal"></div>
                            <span>Teal</span>
                        </li>
                        <li data-theme="green">
                            <div class="green"></div>
                            <span>Green</span>
                        </li>
                        <li data-theme="light-green">
                            <div class="light-green"></div>
                            <span>Light Green</span>
                        </li>
                        <li data-theme="lime">
                            <div class="lime"></div>
                            <span>Lime</span>
                        </li>
                        <li data-theme="yellow">
                            <div class="yellow"></div>
                            <span>Yellow</span>
                        </li>
                        <li data-theme="amber">
                            <div class="amber"></div>
                            <span>Amber</span>
                        </li>
                        <li data-theme="orange">
                            <div class="orange"></div>
                            <span>Orange</span>
                        </li>
                        <li data-theme="deep-orange">
                            <div class="deep-orange"></div>
                            <span>Deep Orange</span>
                        </li>
                        <li data-theme="brown">
                            <div class="brown"></div>
                            <span>Brown</span>
                        </li>
                        <li data-theme="grey">
                            <div class="grey"></div>
                            <span>Grey</span>
                        </li>
                        <li data-theme="blue-grey">
                            <div class="blue-grey"></div>
                            <span>Blue Grey</span>
                        </li>
                        <li data-theme="black">
                            <div class="black"></div>
                            <span>Black</span>
                        </li>
                    </ul>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="settings">
                    <div class="demo-settings">
                        <p>GENERAL SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Report Panel Usage</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Email Redirect</span>
                                <div class="switch">
                                    <label><input type="checkbox"><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                        <p>SYSTEM SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Notifications</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Auto Updates</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                        <p>ACCOUNT SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Offline</span>
                                <div class="switch">
                                    <label><input type="checkbox"><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Location Permission</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </aside>
        <!-- #END# Right Sidebar -->
    </section>