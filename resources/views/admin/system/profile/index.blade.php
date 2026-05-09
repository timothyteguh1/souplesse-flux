<div>
    @section('title', 'Profile')

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card" x-data="{ activeTab: 'profile' }">
                        <div class="card-body">
                            <ul
                                class="nav nav-custom-light nav-border-top nav-border-top-primary nav-justified rounded card-header-tabs border"
                                role="tablist"
                            >
                                <li class="nav-item">
                                    <a
                                        class="nav-link"
                                        :class="activeTab == 'profile' && 'active'"
                                        data-bs-toggle="tab"
                                        href="#tabProfile"
                                        role="tab"
                                        @click="activeTab = 'profile'"
                                    >
                                        Profile
                                    </a>
                                </li>
                                @if (auth()->user()->perusahaan != null && auth()->user()->type == \App\Utilities\Constants\Const_Umum::USER_TYPE_OWNER)
                                    <li class="nav-item">
                                        <a
                                            class="nav-link"
                                            :class="activeTab == 'perusahaan' && 'active'"
                                            data-bs-toggle="tab"
                                            href="#tabPerusahaan"
                                            role="tab"
                                            @click="activeTab = 'perusahaan'"
                                        >
                                            Perusahaan
                                        </a>
                                    </li>
                                @endif

                                <li class="nav-item">
                                    <a
                                        class="nav-link"
                                        :class="activeTab == 'change_password' && 'active'"
                                        data-bs-toggle="tab"
                                        href="#tabChangePassword"
                                        role="tab"
                                        @click="activeTab = 'change_password'"
                                    >
                                        Change Password
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link"
                                        :class="activeTab == 'logged_in_sessions' && 'active'"
                                        data-bs-toggle="tab"
                                        href="#tabLoggedInSessions"
                                        role="tab"
                                        @click="activeTab = 'logged_in_sessions'"
                                    >
                                        Logged In Sessions
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <div class="tab-content">
                                <div
                                    class="tab-pane"
                                    :class="activeTab == 'profile' && 'active'"
                                    id="tabProfile"
                                    role="tabpanel"
                                >
                                    <livewire:admin.system.profile.umum />
                                </div>

                                @if (auth()->user()->perusahaan != null && auth()->user()->type == \App\Utilities\Constants\Const_Umum::USER_TYPE_OWNER)
                                    <div
                                        class="tab-pane"
                                        :class="activeTab == 'perusahaan' && 'active show'"
                                        id="tabPerusahaan"
                                        role="tabpanel"
                                    >
                                        <livewire:admin.system.profile.perusahaan />
                                    </div>
                                @endif

                                <div
                                    class="tab-pane"
                                    :class="activeTab == 'change_password' && 'active show'"
                                    id="tabChangePassword"
                                    role="tabpanel"
                                >
                                    <livewire:admin.system.profile.ganti-password />
                                </div>

                                <div
                                    class="tab-pane"
                                    :class="activeTab == 'logged_in_sessions' && 'active show'"
                                    id="tabLoggedInSessions"
                                    role="tabpanel"
                                >
                                    <livewire:admin.system.profile.logged-in-sessions />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</div>
