@extends('admin.layout')

@section('title', 'Posts')
@section('page-title', 'Posts Management')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900">All Posts</h3>
        <button onclick="openCreateModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>Add Post
        </button>
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="posts-table-body" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            <i class="fas fa-spinner fa-spin"></i> Loading...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="post-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Add Post</h3>
            </div>
            
            <form id="post-form" class="p-6" enctype="multipart/form-data">
                <input type="hidden" id="post-id">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" id="post-title" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Content</label>
                        <textarea id="post-body" rows="8" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="post-status" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Status</option>
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Image</label>
                            <input type="file" id="post-image" accept="image/*" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="mt-1 text-sm text-gray-500">Accepted formats: JPEG, PNG, JPG, GIF (max 2MB)</p>
                        </div>
                    </div>
                    
                    <div id="current-image-container" class="hidden">
                        <label class="block text-sm font-medium text-gray-700">Current Image</label>
                        <img id="current-image" src="" alt="Current post image" class="mt-2 w-32 h-32 object-cover rounded-md">
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let posts = [];
let currentUser = null;

document.addEventListener('DOMContentLoaded', function() {
    // Get current user from localStorage
    const userData = localStorage.getItem('admin_user');
    if (userData) {
        currentUser = JSON.parse(userData);
    }
    
    loadPosts();
    
    document.getElementById('post-form').addEventListener('submit', function(e) {
        e.preventDefault();
        savePost();
    });
});

function loadPosts() {
    axios.get('/api/post/viewAll')
        .then(response => {
            posts = response.data.data;
            renderPosts();
        })
        .catch(error => {
            console.error('Error loading posts:', error);
            showToast('Error loading posts', 'error');
        });
}

function renderPosts() {
    const tbody = document.getElementById('posts-table-body');
    
    if (posts.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No posts found</td></tr>';
        return;
    }
    
    tbody.innerHTML = posts.map(post => `
        <tr>
            <td class="px-6 py-4">
                <div>
                    <div class="text-sm font-medium text-gray-900">${post.title}</div>
                    <div class="text-sm text-gray-500">${post.body.substring(0, 100)}${post.body.length > 100 ? '...' : ''}</div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${post.user ? post.user.name : 'Unknown'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                    post.status === 'published' ? 'bg-green-100 text-green-800' :
                    post.status === 'draft' ? 'bg-yellow-100 text-yellow-800' :
                    'bg-gray-100 text-gray-800'
                }">
                    ${post.status}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${new Date(post.created_at).toLocaleDateString()}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button onclick="editPost(${post.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="deletePost(${post.id})" class="text-red-600 hover:text-red-900">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function openCreateModal() {
    document.getElementById('modal-title').textContent = 'Add Post';
    document.getElementById('post-form').reset();
    document.getElementById('post-id').value = '';
    document.getElementById('current-image-container').classList.add('hidden');
    document.getElementById('post-modal').classList.remove('hidden');
}

function editPost(postId) {
    const post = posts.find(p => p.id === postId);
    if (!post) return;
    
    document.getElementById('modal-title').textContent = 'Edit Post';
    document.getElementById('post-id').value = post.id;
    document.getElementById('post-title').value = post.title;
    document.getElementById('post-body').value = post.body;
    document.getElementById('post-status').value = post.status;
    
    // Show current image if exists
    if (post.image) {
        document.getElementById('current-image').src = '/' + post.image;
        document.getElementById('current-image-container').classList.remove('hidden');
    } else {
        document.getElementById('current-image-container').classList.add('hidden');
    }
    
    document.getElementById('post-modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('post-modal').classList.add('hidden');
}

function savePost() {
    const postId = document.getElementById('post-id').value;
    const formData = new FormData();
    
    formData.append('title', document.getElementById('post-title').value);
    formData.append('body', document.getElementById('post-body').value);
    formData.append('status', document.getElementById('post-status').value);
    formData.append('user_id', currentUser ? currentUser.id : 1); // Default to user ID 1 if not available
    
    const imageFile = document.getElementById('post-image').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }
    
    const url = postId ? '/api/post/update' : '/api/post/create';
    if (postId) {
        formData.append('id', postId);
    }
    
    axios.post(url, formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
    .then(response => {
        showToast(response.data.message);
        closeModal();
        loadPosts();
    })
    .catch(error => {
        console.error('Error saving post:', error);
        showToast('Error saving post', 'error');
    });
}

function deletePost(postId) {
    if (!confirm('Are you sure you want to delete this post?')) return;
    
    axios.post(`/api/post/${postId}/delete`)
        .then(response => {
            showToast(response.data.message);
            loadPosts();
        })
        .catch(error => {
            console.error('Error deleting post:', error);
            showToast('Error deleting post', 'error');
        });
}
</script>
@endsection 