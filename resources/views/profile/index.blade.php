<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
    <div class="container mt-4">
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        @if (session('delete'))
            <div class="alert alert-danger">
                {{ session('delete') }}
            </div>
        @endif


        <h3 class="mb-4">{{ isset($profile) ? 'Edit Profile' : 'Add Profile' }}</h3>

        <form method="POST"
            action="{{ isset($profile) ? route('profile.update', $profile->id) : route('profile.store') }}"
            enctype="multipart/form-data">
            @csrf
            @if (isset($profile))
                @method('put')
            @endif

            <div class="row">
                <div class="col-md-4 mb-2">
                    <label for="name">Name:</label>
                    <input type="text" name="name" class="form-control"
                        value="{{ old('name', $profile->name ?? '') }}" required>
                </div>

                <div class="col-md-4 mb-2">
                    <label for="email">Email:</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', $profile->email ?? '') }}" required>
                </div>

                <div class="col-md-4 mb-2">
                    <label for="phone">Phone:</label>
                    <input type="number" name="phone" class="form-control"
                        value="{{ old('phone', $profile->phone ?? '') }}" required>
                </div>

                <div class="col-md-4 mb-2">
                    <label for="profile_pic">Profile Picture:</label>
                    <input type="file" name="profile_pic" class="form-control">
                    @if (isset($profile) && $profile->profile_pic)
                        <img src="{{ asset('storage/' . $profile->profile_pic) }}" width="60" class="mt-2 rounded">
                    @endif
                </div>

                <div class="col-md-4 mb-2">
                    <label for="resume">Resume:</label>
                    <input type="file" name="resume[]" class="form-control" multiple>
                    @if (isset($profile) && $profile->resume)
                        <div class="mt-2">
                            @php
                                $resumes = is_string($profile->resume)
                                    ? json_decode($profile->resume, true)
                                    : $profile->resume;
                            @endphp

                            @if (is_array($resumes))
                                @foreach ($resumes as $res)
                                    <a href="{{ asset('storage/' . $res) }}" target="_blank" class="d-block">View
                                        Resume</a>
                                @endforeach
                            @endif
                        </div>
                    @endif

                </div>
            </div>

            <button class="btn btn-success mt-3">
                {{ isset($profile) ? 'Update Profile' : 'Save Profile' }}
            </button>
        </form>

        <hr>

        <div class="row">
            <div class="row mt-tight">
                <div class="col-xl-12">
                    <div class="card border bg-light border-bottom">
                        <div class="card-header">
                            <div class="row w-100">
                                <div class="col-md-12 d-flex flex-wrap align-items-center gap-3">
                                    {{-- Index pagination --}}
                                    <form method="GET"
                                        class="d-flex align-items-center bg-light rounded px-2 py-1 shadow-sm">
                                        <label for="per_page" class="mb-0 me-2 fw-semibold text-dark small">Show</label>
                                        <select name="per_page" id="per_page"
                                            class="form-select form-select-sm w-auto rounded border-primary"
                                            style="border-width: 1px;" onchange="this.form.submit()">
                                            @foreach ([10, 25, 50, 100] as $size)
                                                <option value="{{ $size }}"
                                                    {{ request('per_page') == $size ? 'selected' : '' }}>
                                                    {{ $size }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="mb-0 ms-2 text-dark fw-semibold small">Entries</span>
                                    </form>
                                    {{-- Search Form --}}
                                    <form method="GET" action="{{ route('profile.index') }}"
                                        class=" search-form  d-flex align-items-center bg-light rounded px-2 py-1 shadow-sm ms-auto">
                                        <label for="search" class="mb-0 me-2 fw-semibold text-dark ">Search</label>
                                        <input type="text" name="search"
                                            class=" search-input  form-control form-control-sm rounded fw-semibold border-primary"
                                            style="border-width: 1px;" placeholder="Search by name..."
                                            value="{{ request('search') }}">
                                    </form>
                                    <div class="mb-3">
    <a href="{{ route('profiles.export', 'excel') }}" class="btn btn-success btn-sm">Export Excel</a>
</div>

                                </div>
                            </div>
                        </div>

                        <h4 class="mt-4">All Profiles</h4>
                        <table class="table table-bordered table-striped mt-2">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Profile Pic</th>
                                    <th>Resume</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($profiles as $profile)
                                    <tr>
                                        <td>{{ $profile->id }}</td>
                                        <td>{{ $profile->name }}</td>

                                        <td>{{ $profile->email }}</td>
                                        <td>{{ $profile->phone }}</td>
                                        <td>
                                            @if ($profile->profile_pic)
                                                <img src="{{ asset('storage/' . $profile->profile_pic) }}"
                                                    width="50">
                                            @endif

                                        </td>
                                        <td>
                                            @if (isset($profile) && $profile->resume)
                                                <div class="mt-2">
                                                    @php
                                                        $resumes = is_string($profile->resume)
                                                            ? json_decode($profile->resume, true)
                                                            : $profile->resume;
                                                    @endphp

                                                    @if (is_array($resumes))
                                                        @foreach ($resumes as $res)
                                                            <a href="{{ asset('storage/' . $res) }}" target="_blank"
                                                                class="d-block">View Resume</a>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            @endif



                                        </td>

                                        <td>
                                            <a href="{{ route('profile.edit', $profile->id) }}"
                                                class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('profile.destroy', $profile->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Delete this profile?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                    </div>

                    </table>
                </div>
            </div>
                    <div class="bg-white text-dark border-bottom">
                        {{ $profiles->links('pagination::bootstrap-4') }}
                    </div>
                <script>
                    let timer;
                    const searchInput = document.querySelector('.search-input');
                    const form = document.querySelector('.search-form');
                    searchInput.addEventListener('input', () => {
                        clearTimeout(timer);
                        timer = setTimeout(() => {
                            form.submit();
                        }, 1000);
                    });
                </script>
</body>

</html>
