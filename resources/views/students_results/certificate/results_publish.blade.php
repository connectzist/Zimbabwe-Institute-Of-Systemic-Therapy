<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publish Certificate Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .logo-container img {
            max-width: 400px; 
            height: auto;
        }
    </style>
</head>
<body>

<div class="logo-container">
    <img src="{{ asset('images/Connect.logo.jpg') }}" alt="Connect ZIST Logo">
</div>

<!-- Success Section -->
@if (session('success'))
    <div class="alert alert-success text-center" role="alert" style="margin-left: auto; margin-right: auto; width: 50%;">
        {{ session('success') }}
    </div>
@endif

<!-- Error Section -->
@if ($errors->any())
    <div class="alert alert-danger text-center" role="alert" style="margin-left: auto; margin-right: auto; width: 50%;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Hint Section -->
<div class="alert alert-warning text-center" role="alert" style="margin-left: auto; margin-right: auto; width: 80%;">
    <strong>Note:</strong> Make sure all students are recorded for the selected module for results to be published.
</div>

<!-- Buttons and Table Container -->
<div class="container mt-5">
    <div class="button-box mb-4" style="border: 1px solid #ddd; border-radius: 10px; padding: 20px; background-color: #f8f9fa;">
        
        <!-- Row: Select Module + Publish Button -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <!-- Select Module -->
           <form method="GET" action="{{ route('certificate_publish') }}" id="moduleForm" >
                <div class="form-group mb-0 me-2" style="flex: 1; max-width: 300px;">
                    {{-- <label for="module_id" style="font-size: 0.9rem;"><strong>Module</strong></label> --}}
                <select name="module_id" id="module_id" class="form-select-sm" required>
                    <option value="" disabled {{ request('module_id') ? '' : 'selected' }}>-- Select Module --</option>
                    @foreach ($modules as $module)
                        <option value="{{ $module->id }}" {{ request('module_id') == $module->id ? 'selected' : '' }}>
                            {{ $module->module_name }}
                        </option>
                    @endforeach
                </select>

                </div>
            </form>

            <!-- Publish Button -->
            <div class="mt-2">
                <button id="publishButton" class="btn btn-primary btn-sm" style="border-radius: 30px; padding: 6px 12px; font-weight: bold; background-color: #007bff; border: none;">
                    {{ $isPublished ? 'Unpublish' : 'Publish' }}
                </button>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-2 text-end">
            <a href="{{ route('students_results.students_results') }}" class="btn btn-secondary btn-sm" style="border-radius: 30px; padding: 6px 12px;">
                Back
            </a>
        </div>
    </div>

    <!-- Table Section -->
        @if(request('module_id'))
            <div class="card-body mt-4">
                <table class="table table-hover table-bordered" id="list">
                    <thead>
                        <tr>
                            <th>Candidate Number</th>
                            <th>Course Name</th>
                            <th>Module</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studentsData as $data)
                            <tr>
                                <td>{{ $data['candidate_number'] }}</td> 
                                <td>{{ $data['course_name'] }}</td>      
                                <td>{{ $data['course_module'] }}</td> 
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info text-center mt-1" style="margin-left: auto; margin-right: auto; width: 50%;">
                <strong>Info:</strong> Please select a module from the dropdown to view the student records.
            </div>
        @endif

</div>

<!-- Modal -->
<div class="modal fade" id="publishModal" tabindex="-1" aria-labelledby="publishModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="publishModalLabel">{{ $isPublished ? 'Are you sure you want to Unpublish?' : 'Are you ready to Publish?' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $isPublished ? 'This will unpublish all the results.' : 'This process will take some time as we are making sure all records for each student are added correctly.' }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="confirmPublish">{{ $isPublished ? 'Unpublish' : 'Publish' }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/hideError.js') }}"></script>

<script>
    document.getElementById('publishButton').onclick = function() {
        var myModal = new bootstrap.Modal(document.getElementById('publishModal'));
        myModal.show();
    };

        document.getElementById('module_id').addEventListener('change', function() {
        document.getElementById('moduleForm').submit();
    })

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('publishButton').onclick = function () {
            const myModal = new bootstrap.Modal(document.getElementById('publishModal'));
            myModal.show();
        };

        document.getElementById('confirmPublish').onclick = function () {
            const selectedModuleId = document.getElementById('module_id').value;
            if (!selectedModuleId) {
                alert("Please select a module before publishing.");
                return;
            }

            const baseUrl = "{{ route($isPublished ? 'certificate.unpublish' : 'certificate.publish') }}";
            window.location.href = baseUrl + "?module_id=" + encodeURIComponent(selectedModuleId);
        };
    });
</script>

</body>
</html>
