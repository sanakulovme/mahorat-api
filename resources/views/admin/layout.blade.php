<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Education Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-indigo-800 text-white w-64 min-h-screen flex flex-col">
            <div class="p-4">
                <h1 class="text-2xl font-bold">Education Center</h1>
                <p class="text-indigo-200 text-sm">Admin Panel</p>
            </div>
            
            <nav class="flex-1 px-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.courses') }}" class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors">
                            <i class="fas fa-graduation-cap mr-3"></i>
                            Courses
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.posts') }}" class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors">
                            <i class="fas fa-newspaper mr-3"></i>
                            Posts
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.applications') }}" class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors">
                            <i class="fas fa-file-alt mr-3"></i>
                            Applications
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="p-4 border-t border-indigo-700">
                <button onclick="logout()" class="w-full flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Logout
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div id="toast" class="fixed top-4 right-4 z-50 hidden">
        <div class="bg-white border-l-4 border-green-500 shadow-lg rounded-lg p-4 max-w-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900" id="toast-message"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set up axios defaults
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Add auth token to requests if available
        const token = localStorage.getItem('admin_token');
        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        }

        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const messageEl = document.getElementById('toast-message');
            
            messageEl.textContent = message;
            toast.classList.remove('hidden');
            
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }

        // Logout function
        function logout() {
            axios.post('/api/user/logout')
                .then(() => {
                    localStorage.removeItem('admin_token');
                    localStorage.removeItem('admin_user');
                    window.location.href = '/admin/login';
                })
                .catch(() => {
                    localStorage.removeItem('admin_token');
                    localStorage.removeItem('admin_user');
                    window.location.href = '/admin/login';
                });
        }

        // Check authentication on page load
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('admin_token');
            if (!token && window.location.pathname !== '/admin/login') {
                window.location.href = '/admin/login';
            }
        });
    </script>

    @yield('scripts')
</body>
</html> 