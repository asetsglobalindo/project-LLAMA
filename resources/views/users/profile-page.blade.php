@extends('layouts.app')

@section('title', 'Profile Page')

@section('content')
    <div class="min-h-screen py-24 bg-gray-100 lg:px-14">
        <div class="py-3 text-start">
            <x-heading-title title="Account Setting" />
        </div>

        <br>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
            <div class="flex bg-white w-full rounded-lg shadow">
                <div>
                    <ul class="flex flex-col w-full" id="default-styled-tab" data-tabs-toggle="#default-styled-tab-content"
                        data-tabs-active-classes="text-custom-primary hover:text-custom-primary dark:text-purple-950 dark:hover:text-purple-500 border-purple-600 dark:border-purple-500"
                        data-tabs-inactive-classes="dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300"
                        role="tablist">
                        <li class="me-2 w-full" role="presentation">
                            <button class="inline-block p-4 border-b-2 rounded-t-lg" id="profile-styled-tab"
                                data-tabs-target="#styled-profile" type="button" role="tab" aria-controls="profile"
                                aria-selected="false">Profile Setting</button>
                        </li>
                        <li class="me-2" role="presentation">
                            <button
                                class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                                id="dashboard-styled-tab" data-tabs-target="#styled-dashboard" type="button" role="tab"
                                aria-controls="dashboard" aria-selected="false">Password</button>
                        </li>
                        <li class="me-2" role="presentation">
                            <button
                                class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                                id="settings-styled-tab" data-tabs-target="#styled-settings" type="button" role="tab"
                                aria-controls="settings" aria-selected="false">Wishlist</button>
                        </li>
                        <li class="me-2" role="presentation">
                            <button
                                class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                                id="settings-styled-tab" data-tabs-target="#styled-delete-user" type="button"
                                role="tab" aria-controls="settings" aria-selected="false">Delete Account</button>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-span-2 p-2 flex w-full bg-white rounded-lg shadow" id="default-styled-tab-content">
                <div class="hidden p-4  w-full" id="styled-profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div>
                        <form class="flex justify-between items-center" action="#" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div>
                                <div class="flex items-center gap-4">
                                    <img class="w-14 h-14 rounded-full"
                                        src="https://images.pexels.com/photos/975250/pexels-photo-975250.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                                        alt="">
                                    <div class="font-medium dark:text-white">
                                        <div class="font-bold">{{ Auth::user()->name }}</div>
                                        <div class="text-sm text-gray-500 ">{{ Auth::user()->email }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="file" src="" alt="upload-image" class="text-xs rounded-lg border">

                            <br>
                            <button type="submit" class="bg-custom-primary py-1 px-3 text-sm text-white rounded-lg">
                                Save settings
                            </button>
                        </form>
                    </div>
                </div>
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-dashboard" role="tabpanel"
                    aria-labelledby="dashboard-tab">
                    <div class="flex justify-center items-center ">
                        <div class="flex items-center gap-3">
                            <h1>In the development stage. </h1>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24">
                                    <path fill="none" stroke="#5a0088" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 17H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v8M3 13h13m-8 8h4m-2-4l-.5 4m7.501-2a2 2 0 1 0 4 0a2 2 0 1 0-4 0m2-3.5V17m0 4v1.5m3.031-5.25l-1.299.75m-3.463 2l-1.3.75m0-3.5l1.3.75m3.463 2l1.3.75" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-settings" role="tabpanel"
                    aria-labelledby="settings-tab">
                    <div class="flex justify-center items-center ">
                        <div class="flex items-center gap-3">
                            <h1>In the development stage. </h1>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24">
                                    <path fill="none" stroke="#5a0088" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 17H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v8M3 13h13m-8 8h4m-2-4l-.5 4m7.501-2a2 2 0 1 0 4 0a2 2 0 1 0-4 0m2-3.5V17m0 4v1.5m3.031-5.25l-1.299.75m-3.463 2l-1.3.75m0-3.5l1.3.75m3.463 2l1.3.75" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-delete-user" role="tabpanel"
                    aria-labelledby="">
                    <p class="text-sm text-gray-500 dark:text-gray-400">DELETE ACCOUNT</p>

                    <div class="flex justify-between gap-3 items-center">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Are you sure you want to delete your
                                account?</p>
                        </div>
                        <form action="{{ route('delete-profile', Auth::user()->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-500 py-1 px-3 text-sm text-white rounded-lg">
                                Delete Account
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endSection
