@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Courses Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-graduation-cap text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900" id="courses-count">-</h3>
                <p class="text-gray-600">Total Courses</p>
            </div>
        </div>
    </div>

    <!-- Posts Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-newspaper text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900" id="posts-count">-</h3>
                <p class="text-gray-600">Total Posts</p>
            </div>
        </div>
    </div>

    <!-- Applications Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-file-alt text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900" id="applications-count">-</h3>
                <p class="text-gray-600">Applications</p>
            </div>
        </div>
    </div>

    <!-- Users Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900" id="users-count">-</h3>
                <p class="text-gray-600">Total Users</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Applications -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Recent Applications</h3>
            <a href="/admin/applications" class="text-indigo-600 hover:text-indigo-900 text-sm">View All</a>
        </div>
        <div class="p-6">
            <div id="recent-applications" class="space-y-4">
                <div class="text-center text-gray-500">
                    <i class="fas fa-spinner fa-spin text-2xl"></i>
                    <p class="mt-2">Loading...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Posts -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Recent Posts</h3>
            <a href="/admin/posts" class="text-indigo-600 hover:text-indigo-900 text-sm">View All</a>
        </div>
        <div class="p-6">
            <div id="recent-posts" class="space-y-4">
                <div class="text-center text-gray-500">
                    <i class="fas fa-spinner fa-spin text-2xl"></i>
                    <p class="mt-2">Loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-8 bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="/admin/courses" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Add New Course</h4>
                    <p class="text-sm text-gray-600">Create a new course</p>
                </div>
            </a>
            
            <a href="/admin/posts" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="p-2 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Add New Post</h4>
                    <p class="text-sm text-gray-600">Create a new blog post</p>
                </div>
            </a>
            
            <a href="/admin/applications" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="p-2 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <i class="fas fa-eye"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">View Applications</h4>
                    <p class="text-sm text-gray-600">Check new applications</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
});

function loadDashboardData() {
    // Load counts
    Promise.all([
        axios.get('/api/course/viewAll'),
        axios.get('/api/post/viewAll'),
        axios.get('/api/application/viewAll')
    ])
    .then(responses => {
        const courses = responses[0].data.data;
        const posts = responses[1].data.data;
        const applications = responses[2].data.data;

        // Update counts
        document.getElementById('courses-count').textContent = courses.length;
        document.getElementById('posts-count').textContent = posts.length;
        document.getElementById('applications-count').textContent = applications.length;

        // Load recent applications
        loadRecentApplications(applications.slice(0, 5));
        
        // Load recent posts
        loadRecentPosts(posts.slice(0, 5));
    })
    .catch(error => {
        console.error('Error loading dashboard data:', error);
        showToast('Error loading dashboard data', 'error');
    });
}

function loadRecentApplications(applications) {
    const container = document.getElementById('recent-applications');
    
    if (applications.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-center">No applications yet</p>';
        return;
    }

    container.innerHTML = applications.map(app => `
        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <div>
                <h4 class="font-medium text-gray-900">${app.fullname}</h4>
                <p class="text-sm text-gray-600">${app.course}</p>
                <p class="text-xs text-gray-500">${new Date(app.created_at).toLocaleDateString()}</p>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    New
                </span>
            </div>
        </div>
    `).join('');
}

function loadRecentPosts(posts) {
    const container = document.getElementById('recent-posts');
    
    if (posts.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-center">No posts yet</p>';
        return;
    }

    container.innerHTML = posts.map(post => `
        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <div>
                <h4 class="font-medium text-gray-900">${post.title}</h4>
                <p class="text-sm text-gray-600">${post.user ? post.user.name : 'Unknown'}</p>
                <p class="text-xs text-gray-500">${new Date(post.created_at).toLocaleDateString()}</p>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                    post.status === 'published' ? 'bg-green-100 text-green-800' : 
                    post.status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 
                    'bg-gray-100 text-gray-800'
                }">
                    ${post.status}
                </span>
            </div>
        </div>
    `).join('');
}
</script>
@endsection 