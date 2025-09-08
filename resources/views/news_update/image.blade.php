@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <span>
                <h4 class="alert-danger"></h4>
            </span>
            @foreach (['success', 'info', 'danger', 'warning'] as $msg)
                @if (Session::has($msg))
                    <div id="flash-message" class="alert alert-{{ $msg }}" role="alert">
                        {{ Session::get($msg) }}
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    {{-- <div class="app-content"> --}}
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <form action="{{ route('newsUpdate.popupImageUpload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <input type="file" name="image" class="form-control mb-2" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-secondary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br>
        <div class="card card-primary card-outline">
            <div class="card-header">
                <a href={{ route('getImageByFolder', ['path' => 'images/news-update/webp']) }}
                    class="btn btn-primary btn-flat">NewsUpdate</a>

                <a href={{ route('getImageByFolder', ['path' => 'images/cube/webp']) }}
                    class="btn btn-secondary btn-flat">Cube</a>

                <a href={{ route('getImageByFolder', ['path' => 'images/indian-panorama-cinema/webp']) }}
                    class="btn btn-info btn-flat">Indian Panorama</a>

                <a href={{ route('getImageByFolder', ['path' => 'images/cureted-section']) }}
                    class="btn btn-dark btn-flat">International Cinema</a>

                <a href={{ route('getImageByFolder', ['path' => 'images/thePeacock/poster']) }}
                    class="btn btn-primary btn-flat">Peacock Poster</a>

                <a href={{ route('getImageByFolder', ['path' => 'images/thePeacock']) }}
                    class="btn btn-secondary btn-flat">Peacock PDF'S</a>

                <a href={{ route('getImageByFolder', ['path' => 'press_release']) }} class="btn btn-success btn-flat">Press
                    Release</a>

                <a href={{ route('getImageByFolder', ['path' => 'images/master-class/webp']) }}
                    class="btn btn-info btn-flat">Master Class</a>
            </div>
        </div>
    </div>
    <br>

    <!-- Gallery -->
    <div class="app-content py-4">
        <div class="container-fluid">
            <div class="row g-4">
                @foreach ($images as $image)
                    @php
                        $url = $image['url'];
                        $extension = strtolower(pathinfo($url, PATHINFO_EXTENSION));
                        $fileName = basename($url);
                        $destination = $image['destination'];
                    @endphp

                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card shadow-sm border-0 h-100 position-relative">

                            <!-- Delete Button -->
                            <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 delete-btn"
                                data-destination="{{ $destination }}" data-filename="{{ $fileName }}" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>

                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                <img src="{{ $url }}" class="card-img-top rounded-top" alt="Image"
                                    style="height: 200px; object-fit: cover;" />
                            @elseif($extension === 'pdf')
                                <div class="d-flex justify-content-center align-items-center"
                                    style="height: 200px; background:#f8f9fa;">
                                    <i class="bi bi-file-earmark-pdf" style="font-size: 3rem; color: #dc3545;"></i>
                                </div>
                            @else
                                <div class="d-flex justify-content-center align-items-center"
                                    style="height: 200px; background:#f1f1f1;">
                                    <i class="bi bi-file-earmark" style="font-size: 3rem; color: #6c757d;"></i>
                                </div>
                            @endif

                            <div class="card-body text-center" style="background: rgba(255,255,255,0.95); padding: 1rem;">
                                <div class="d-flex justify-content-center align-items-center mb-2">
                                    @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <i class="bi bi-image me-2" style="font-size: 1.2rem; color:#0d6efd;"></i>
                                    @elseif($extension === 'pdf')
                                        <i class="bi bi-file-earmark-pdf me-2"
                                            style="font-size: 1.2rem; color:#dc3545;"></i>
                                    @else
                                        <i class="bi bi-file-earmark me-2" style="font-size: 1.2rem; color:#6c757d;"></i>
                                    @endif
                                    <span class="badge bg-secondary text-uppercase">{{ $extension }}</span>
                                </div>
                                <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-primary w-100 mb-2">
                                    View File
                                </a>
                                <button class="btn btn-sm btn-outline-secondary w-100 copy-btn"
                                    data-url="{{ $url }}">
                                    Copy URL
                                </button>
                            </div>

                            <style>
                                .card-body .badge {
                                    font-weight: 500;
                                    padding: 0.4em 0.8em;
                                    font-size: 0.85rem;
                                }

                                .card-body i {
                                    transition: transform 0.2s;
                                }

                                .card-body i:hover {
                                    transform: scale(1.2);
                                }
                            </style>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Gallery -->
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".delete-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                const destination = this.getAttribute("data-destination");
                const fileName = this.getAttribute("data-filename");
                const baseUrl = "{{ env('DELETE_IMAGE_BASE_URL') }}";
                const meta = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = meta ? meta.content : '';
                if (confirm(`Are you sure you want to delete ${fileName}?`)) {
                    fetch(baseUrl, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrfToken
                            },
                            body: JSON.stringify({
                                destination: destination,
                                file_name: fileName
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === "success") {
                                this.closest(".col-lg-3").remove();
                            } else {
                                alert(data.message || "Delete failed!");
                            }
                        })
                        .catch(err => console.error(err));
                }
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".copy-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                const fileUrl = this.getAttribute("data-url");
                if (!fileUrl) {
                    alert("No URL found to copy!");
                    return;
                }
                navigator.clipboard.writeText(fileUrl).then(() => {
                    this.textContent = "Copied!";
                    this.classList.remove("btn-outline-secondary");
                    this.classList.add("btn-success");
                    setTimeout(() => {
                        this.textContent = "Copy URL";
                        this.classList.remove("btn-success");
                        this.classList.add("btn-outline-secondary");
                    }, 2000);
                }).catch(err => {
                    console.error("Failed to copy text: ", err);
                    alert("Copy failed!");
                });
            });
        });
    });
</script>

