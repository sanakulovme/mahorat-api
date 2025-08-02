@extends('admin.layout')

@section('title', 'Applications')
@section('page-title', 'Applications Management')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Course Applications</h3>
        <p class="text-sm text-gray-600 mt-1">Applications submitted from the website contact form</p>
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="applications-table-body" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            <i class="fas fa-spinner fa-spin"></i> Loading...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Application Detail Modal -->
<div id="application-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Application Details</h3>
            </div>
            
            <div class="p-6">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <p id="modal-fullname" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p id="modal-email" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <p id="modal-phone" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Course</label>
                            <p id="modal-course" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Message</label>
                        <p id="modal-message" class="mt-1 text-sm text-gray-900 whitespace-pre-wrap"></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Submitted</label>
                        <p id="modal-date" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Close
                    </button>
                    <button onclick="deleteApplication()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Delete Application
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let applications = [];
let selectedApplicationId = null;

document.addEventListener('DOMContentLoaded', function() {
    loadApplications();
});

function loadApplications() {
    axios.get('/api/application/viewAll')
        .then(response => {
            applications = response.data.data;
            renderApplications();
        })
        .catch(error => {
            console.error('Error loading applications:', error);
            showToast('Error loading applications', 'error');
        });
}

function renderApplications() {
    const tbody = document.getElementById('applications-table-body');
    
    if (applications.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No applications found</td></tr>';
        return;
    }
    
    tbody.innerHTML = applications.map(app => `
        <tr>
            <td class="px-6 py-4">
                <div>
                    <div class="text-sm font-medium text-gray-900">${app.fullname}</div>
                    <div class="text-sm text-gray-500">${app.email}</div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${app.course}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${app.phone}
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-900">
                    ${app.message.length > 50 ? app.message.substring(0, 50) + '...' : app.message}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${new Date(app.created_at).toLocaleDateString()}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button onclick="viewApplication(${app.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                    <i class="fas fa-eye"></i>
                </button>
                <button onclick="deleteApplication(${app.id})" class="text-red-600 hover:text-red-900">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function viewApplication(applicationId) {
    const application = applications.find(app => app.id === applicationId);
    if (!application) return;
    
    selectedApplicationId = applicationId;
    
    document.getElementById('modal-fullname').textContent = application.fullname;
    document.getElementById('modal-email').textContent = application.email;
    document.getElementById('modal-phone').textContent = application.phone;
    document.getElementById('modal-course').textContent = application.course;
    document.getElementById('modal-message').textContent = application.message;
    document.getElementById('modal-date').textContent = new Date(application.created_at).toLocaleString();
    
    document.getElementById('application-modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('application-modal').classList.add('hidden');
    selectedApplicationId = null;
}

function deleteApplication(applicationId) {
    if (!applicationId) {
        applicationId = selectedApplicationId;
    }
    
    if (!applicationId) return;
    
    if (!confirm('Are you sure you want to delete this application?')) return;
    
    axios.post(`/api/application/${applicationId}/delete`)
        .then(response => {
            showToast(response.data.message);
            closeModal();
            loadApplications();
        })
        .catch(error => {
            console.error('Error deleting application:', error);
            showToast('Error deleting application', 'error');
        });
}
</script>
@endsection 