@extends('admin.layout')

@section('title', 'Courses')
@section('page-title', 'Courses Management')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900">All Courses</h3>
        <button onclick="openCreateModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>Add Course
        </button>
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="courses-table-body" class="bg-white divide-y divide-gray-200">
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
<div id="course-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Add Course</h3>
            </div>
            
            <form id="course-form" class="p-6" enctype="multipart/form-data">
                <input type="hidden" id="course-id">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="course-name" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="course-description" rows="3" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="number" id="course-price" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Duration (hours)</label>
                            <input type="number" id="course-duration" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Level</label>
                        <select id="course-level" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Level</option>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Image</label>
                        <input type="file" id="course-image" accept="image/*" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="mt-1 text-sm text-gray-500">Accepted formats: JPEG, PNG, JPG, GIF (max 2MB)</p>
                    </div>
                    
                    <div id="current-image-container" class="hidden">
                        <label class="block text-sm font-medium text-gray-700">Current Image</label>
                        <img id="current-image" src="" alt="Current course image" class="mt-2 w-32 h-32 object-cover rounded-md">
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
let courses = [];

document.addEventListener('DOMContentLoaded', function() {
    loadCourses();
    
    document.getElementById('course-form').addEventListener('submit', function(e) {
        e.preventDefault();
        saveCourse();
    });
});

function loadCourses() {
    axios.get('/api/course/viewAll')
        .then(response => {
            courses = response.data.data;
            renderCourses();
        })
        .catch(error => {
            console.error('Error loading courses:', error);
            showToast('Error loading courses', 'error');
        });
}

function renderCourses() {
    const tbody = document.getElementById('courses-table-body');
    
    if (courses.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No courses found</td></tr>';
        return;
    }
    
    tbody.innerHTML = courses.map(course => `
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">
                <div>
                    <div class="text-sm font-medium text-gray-900">${course.name}</div>
                    <div class="text-sm text-gray-500">${course.description.substring(0, 50)}${course.description.length > 50 ? '...' : ''}</div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                    course.level === 'Beginner' ? 'bg-green-100 text-green-800' :
                    course.level === 'Intermediate' ? 'bg-yellow-100 text-yellow-800' :
                    'bg-red-100 text-red-800'
                }">
                    ${course.level}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                $${course.price}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${course.duration} hours
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button onclick="editCourse(${course.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="deleteCourse(${course.id})" class="text-red-600 hover:text-red-900">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function openCreateModal() {
    document.getElementById('modal-title').textContent = 'Add Course';
    document.getElementById('course-form').reset();
    document.getElementById('course-id').value = '';
    document.getElementById('current-image-container').classList.add('hidden');
    document.getElementById('course-modal').classList.remove('hidden');
}

function editCourse(courseId) {
    const course = courses.find(c => c.id === courseId);
    if (!course) return;
    
    document.getElementById('modal-title').textContent = 'Edit Course';
    document.getElementById('course-id').value = course.id;
    document.getElementById('course-name').value = course.name;
    document.getElementById('course-description').value = course.description;
    document.getElementById('course-price').value = course.price;
    document.getElementById('course-duration').value = course.duration;
    document.getElementById('course-level').value = course.level;
    
    // Show current image if exists
    if (course.image) {
        document.getElementById('current-image').src = '/' + course.image;
        document.getElementById('current-image-container').classList.remove('hidden');
    } else {
        document.getElementById('current-image-container').classList.add('hidden');
    }
    
    document.getElementById('course-modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('course-modal').classList.add('hidden');
}

function saveCourse() {
    const courseId = document.getElementById('course-id').value;
    const formData = new FormData();
    
    formData.append('name', document.getElementById('course-name').value);
    formData.append('description', document.getElementById('course-description').value);
    formData.append('price', document.getElementById('course-price').value);
    formData.append('duration', document.getElementById('course-duration').value);
    formData.append('level', document.getElementById('course-level').value);
    
    const imageFile = document.getElementById('course-image').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }
    
    const url = courseId ? '/api/course/update' : '/api/course/create';
    if (courseId) {
        formData.append('id', courseId);
    }
    
    axios.post(url, formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
    .then(response => {
        showToast(response.data.message);
        closeModal();
        loadCourses();
    })
    .catch(error => {
        console.error('Error saving course:', error);
        showToast('Error saving course', 'error');
    });
}

function deleteCourse(courseId) {
    if (!confirm('Are you sure you want to delete this course?')) return;
    
    axios.post(`/api/course/${courseId}/delete`)
        .then(response => {
            showToast(response.data.message);
            loadCourses();
        })
        .catch(error => {
            console.error('Error deleting course:', error);
            showToast('Error deleting course', 'error');
        });
}
</script>
@endsection 