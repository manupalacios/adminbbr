            <!-- Main Sidebar Container -->
            <aside class="main-sidebar elevation-4 sidebar-dark-info">
                <!-- Brand Logo -->
                <a href="{{ url('/') }}" class="brand-link">
                    <img src="{{ asset('img/AdminLTELogo.png') }}"
                    alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3"
                    style="opacity: .8">
                    <span class="brand-text font-weight-light">&nbsp;AdminBBR</span>
                </a>
                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar user (optional) -->
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="{{ asset('img/no_picture_male.jpg') }}" class="img-circle elevation-2" alt="User Image">
                        </div>
                        <div class="info">
                            <a href="#" class="d-block">Nombre de usuario</a>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                        with font-awesome or any other icon font library -->
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon far fa-tachometer-alt"></i>
                                    <p>
                                        Panel de control
                                        <i class="right fa fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Escritorio</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon far fa-calendar-alt"></i>
                                    <p>
                                        Nuevo Per&iacute;odo
                                        <i class="right fa fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('archivo.create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Subir archivo</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('archivo.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Listado archivos</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link" >
                                    <i class="fa fa-sign-out nav-icon"></i>
                                    <p>Cerrar sesi&oacute;n</p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>
