<!--begin::Menu-->
<div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true" id="kt_menu_notifications">
    <!--begin::Heading-->
    <div class="d-flex flex-column bgi-no-repeat rounded-top"
        style="background-image:url('http://127.0.0.1:8000/assets/media/misc/menu-header-bg.jpg')">
        <!--begin::Title-->
        <h3 class="text-white fw-semibold px-9 mt-10 mb-6">
            Notifications
            <span class="fs-8 opacity-75 ps-3">{{ auth()->user()->unreadNotifications->count() }} unread</span>
        </h3>
        <!--end::Title-->
        <!--begin::Tabs-->
        <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9">
            <li class="nav-item">
                <a class="nav-link text-white opacity-75 opacity-state-100 pb-4 active" data-bs-toggle="tab"
                    href="#kt_topbar_notifications_1">All Notifications</a>
            </li>
        </ul>
        <!--end::Tabs-->
    </div>
    <!--end::Heading-->
    <!--begin::Tab content-->
    <div class="tab-content">
        <!--begin::Tab panel-->
        <div class="tab-pane fade show active" id="kt_topbar_notifications_1" role="tabpanel">
            <!--begin::Items-->
            <div class="scroll-y mh-325px px-8">
                @forelse(auth()->user()->notifications()->latest()->take(10)->get() as $notification)
                    <!--begin::Item-->
                    <div
                        class="d-flex flex-stack my-2 py-5 {{ $notification->read_at === null ? 'bg-light-primary rounded' : '' }}">
                        <!--begin::Section-->
                        <div class="d-flex align-items-center px-5">
                            <!--begin::Title-->
                            <div class="mb-0 me-2">
                                <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">New Booking</a>
                                <div class="text-gray-500 fs-7">
                                    Request By {{ $notification->data['user_name'] }}
                                    {{ \Carbon\Carbon::parse($notification->data['start_date'])->format('Y-m-d') }} to
                                    {{ \Carbon\Carbon::parse($notification->data['end_date'])->format('Y-m-d') }}
                                </div>
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Section-->
                        <!--begin::Label-->
                        <span
                            class="badge {{ $notification->read_at === null ? 'bg-light-primary' : '' }} fs-8">{{ $notification->created_at->diffForHumans() }}</span>
                        <!--end::Label-->
                    </div>
                    <!--end::Item-->
                @empty
                    <div class="text-center text-gray-500 py-4">
                        No notifications found
                    </div>
                @endforelse
            </div>
            <!--end::Items-->
            <!--begin::View more-->
            <div class="py-3 text-center border-top">
                <a href="#" class="btn btn-color-gray-600 btn-active-color-primary">
                    View All {!! getIcon('arrow-right', 'fs-5') !!}
                </a>
            </div>
            <!--end::View more-->
        </div>
        <!--end::Tab panel-->
    </div>
    <!--end::Tab content-->
</div>
<!--end::Menu-->
