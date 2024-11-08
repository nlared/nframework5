<?
class Apps{
	function notification_menu(){
		return '<!-- Start::header-element -->
                                <div class="header-element dropdown-center header-shortcuts-dropdown">
                                    <!-- Start::header-link|dropdown-toggle -->
                                    <a href="javascript:void(0);" class="header-link dropdown-toggle"
                                        data-bs-toggle="dropdown" id="notificationDropdown" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon"
                                            viewBox="0 0 24 24">
                                            <path d="M0 0h24v24H0V0z" fill="none" />
                                            <path
                                                d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z" />
                                        </svg>
                                    </a>
                                    <!-- End::header-link|dropdown-toggle -->
                                    <!-- Start::main-header-dropdown -->
                                    <div class="main-header-dropdown header-shortcuts-dropdown dropdown-menu pb-0 dropdown-menu-end"
                                        aria-labelledby="notificationDropdown">
                                        <div class="p-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="mb-0 fs-17 fw-semibold">Related Apps</p>
                                            </div>
                                        </div>
                                        <div class="dropdown-divider my-0"></div>
                                        <div class="main-header-shortcuts py-1 px-4" id="header-shortcut-scroll">
                                            <div class="row">
                                                <div class="col-4 p-1 mt-0">
                                                    <a href="chat.html">
                                                        <div class="text-center p-3 related-app border rounded-2">
                                                            <span>
                                                                <i
                                                                    class="mdi mdi-message-outline text-primary fs-24"></i>
                                                            </span>
                                                            <span class="d-block fs-12 text-dark">Chat</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-4 p-1 mt-0">
                                                    <a href="file-manager.html">
                                                        <div class="text-center p-3 related-app border rounded-2">
                                                            <span>
                                                                <i
                                                                    class="mdi mdi-file-multiple-outline text-info fs-24"></i>
                                                            </span>
                                                            <span class="d-block fs-12 text-dark">Files</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-4 p-1 mt-0">
                                                    <a href="calendar2.html">
                                                        <div class="text-center p-3 related-app border rounded-2">
                                                            <span>
                                                                <i
                                                                    class="mdi mdi-calendar-range-outline text-warning fs-24"></i>
                                                            </span>
                                                            <span class="d-block fs-12 text-dark">Calendar</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-4 p-1 mt-0">
                                                    <a href="settings.html">
                                                        <div class="text-center p-3 related-app border rounded-2">
                                                            <span>
                                                                <i class="mdi mdi-cog-outline text-info fs-24"></i>
                                                            </span>
                                                            <span class="d-block fs-12 text-dark">Settings</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-4 p-1 mt-0">
                                                    <a href="faq.html">
                                                        <div class="text-center p-3 related-app border rounded-2">
                                                            <span>
                                                                <i
                                                                    class="mdi mdi-help-circle-outline text-success fs-24"></i>
                                                            </span>
                                                            <span class="d-block fs-12 text-dark">Help</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-4 p-1 mt-0">
                                                    <a href="profile.html">
                                                        <div class="text-center p-3 related-app border rounded-2">
                                                            <span>
                                                                <i
                                                                    class="mdi mdi-account-outline text-primary fs-24"></i>
                                                            </span>
                                                            <span class="d-block fs-12 text-dark">Profile</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-4 p-1 mt-0">
                                                    <a href="about.html">
                                                        <div class="text-center p-3 related-app border rounded-2">
                                                            <span>
                                                                <i class="mdi mdi-phone text-primary fs-24"></i>
                                                            </span>
                                                            <span class="d-block fs-12 text-dark">Contact</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-4 p-1 mt-0">
                                                    <a href="rating.html">
                                                        <div class="text-center p-3 related-app border rounded-2">
                                                            <span>
                                                                <i
                                                                    class="mdi mdi-comment-quote-outline text-secondary fs-24"></i>
                                                            </span>
                                                            <span class="d-block fs-12 text-dark">Feeback</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-4 p-1 mt-0">
                                                    <a href="login.html">
                                                        <div class="text-center p-3 related-app border rounded-2">
                                                            <span>
                                                                <i class="mdi mdi-logout text-warning fs-24 "></i>
                                                            </span>
                                                            <span class="d-block fs-12 text-dark">Logout</span>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-3 border-top">
                                            <div class="d-grid">
                                                <a href="javascript:void(0);" class="btn btn-primary">View All</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End::main-header-dropdown -->
                                </div>
                                <!-- End::header-element -->';
	}
}