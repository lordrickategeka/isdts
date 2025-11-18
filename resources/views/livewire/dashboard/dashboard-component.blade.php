<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-base-100 border-b border-gray-200">
        <div class="flex items-center justify-between p-4">
            <div>
                <h1 class="text-2xl font-bold text-black">Dashboard</h1>
                <p class="text-sm text-gray-500">Welcome back, here's your overview</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Notifications -->
                <button class="btn btn-ghost btn-square">
                    <div class="indicator">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="badge badge-xs badge-primary indicator-item"></span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="card bg-base-100 shadow-md rounded-lg border border-gray-200">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Total Users</p>
                            <h3 class="text-3xl font-bold text-black">1,234</h3>
                            <p class="text-sm text-green-600 mt-1">↑ 12% from last month</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Projects -->
            <div class="card bg-base-100 shadow-md rounded-lg border border-gray-200">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Active Projects</p>
                            <h3 class="text-3xl font-bold text-black">56</h3>
                            <p class="text-sm text-green-600 mt-1">↑ 8% from last month</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Tasks -->
            <div class="card bg-base-100 shadow-md rounded-lg border border-gray-200">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Completed Tasks</p>
                            <h3 class="text-3xl font-bold text-black">892</h3>
                            <p class="text-sm text-green-600 mt-1">↑ 23% from last month</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="card bg-base-100 shadow-md rounded-lg border border-gray-200">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Total Revenue</p>
                            <h3 class="text-3xl font-bold text-black">$45.2K</h3>
                            <p class="text-sm text-red-600 mt-1">↓ 3% from last month</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Activity -->
            <div class="lg:col-span-2 card bg-base-100 shadow-md rounded-lg border border-gray-200">
                <div class="card-body">
                    <h2 class="card-title text-black mb-4">Recent Activity</h2>
                    <div class="space-y-4">
                        <!-- Activity Item -->
                        <div class="flex items-start gap-4 pb-4 border-b border-gray-200 last:border-0 last:pb-0">
                            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-black font-semibold">New user registered</p>
                                <p class="text-sm text-gray-500">John Smith joined the platform</p>
                                <p class="text-xs text-gray-400 mt-1">2 minutes ago</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 pb-4 border-b border-gray-200 last:border-0 last:pb-0">
                            <div class="w-10 h-10 bg-secondary rounded-full flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-black font-semibold">Project updated</p>
                                <p class="text-sm text-gray-500">Website redesign milestone completed</p>
                                <p class="text-xs text-gray-400 mt-1">1 hour ago</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 pb-4 border-b border-gray-200 last:border-0 last:pb-0">
                            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-black font-semibold">Task completed</p>
                                <p class="text-sm text-gray-500">Database optimization finished</p>
                                <p class="text-xs text-gray-400 mt-1">3 hours ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card bg-base-100 shadow-md rounded-lg border border-gray-200">
                <div class="card-body">
                    <h2 class="card-title text-black mb-4">Quick Actions</h2>
                    <div class="space-y-3">
                        <button class="btn btn-primary w-full justify-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create New Project
                        </button>
                        <button class="btn btn-outline btn-primary w-full justify-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            Add New User
                        </button>
                        <button class="btn btn-outline btn-primary w-full justify-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Generate Report
                        </button>
                    </div>

                    <div class="divider"></div>

                    <h3 class="font-semibold text-black mb-3">System Status</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Server Status</span>
                            <span class="badge badge-success">Online</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Database</span>
                            <span class="badge badge-success">Healthy</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">API Status</span>
                            <span class="badge badge-success">Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Projects Table -->
        <div class="card bg-base-100 shadow-md rounded-lg border border-gray-200 mt-6">
            <div class="card-body">
                <h2 class="card-title text-black mb-4">Recent Projects</h2>
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-black">Project Name</th>
                                <th class="text-black">Status</th>
                                <th class="text-black">Team</th>
                                <th class="text-black">Progress</th>
                                <th class="text-black">Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-200">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                            </svg>
                                        </div>
                                        <span class="font-semibold text-black">Website Redesign</span>
                                    </div>
                                </td>
                                <td><span class="badge badge-success">Active</span></td>
                                <td>
                                    <div class="avatar-group -space-x-2">
                                        <div class="avatar placeholder">
                                            <div class="w-8 bg-primary text-white"><span class="text-xs">JD</span></div>
                                        </div>
                                        <div class="avatar placeholder">
                                            <div class="w-8 bg-secondary text-white"><span class="text-xs">AB</span></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <progress class="progress progress-primary w-20" value="75" max="100"></progress>
                                        <span class="text-sm text-gray-600">75%</span>
                                    </div>
                                </td>
                                <td class="text-gray-600">Dec 15, 2025</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-secondary rounded-lg flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                            </svg>
                                        </div>
                                        <span class="font-semibold text-black">Mobile App Development</span>
                                    </div>
                                </td>
                                <td><span class="badge badge-warning">In Progress</span></td>
                                <td>
                                    <div class="avatar-group -space-x-2">
                                        <div class="avatar placeholder">
                                            <div class="w-8 bg-primary text-white"><span class="text-xs">CD</span></div>
                                        </div>
                                        <div class="avatar placeholder">
                                            <div class="w-8 bg-secondary text-white"><span class="text-xs">EF</span></div>
                                        </div>
                                        <div class="avatar placeholder">
                                            <div class="w-8 bg-primary text-white"><span class="text-xs">GH</span></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <progress class="progress progress-primary w-20" value="45" max="100"></progress>
                                        <span class="text-sm text-gray-600">45%</span>
                                    </div>
                                </td>
                                <td class="text-gray-600">Jan 20, 2026</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
